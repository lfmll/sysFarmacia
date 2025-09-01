<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Ajuste;
use App\Models\PuntoVenta;
use App\Models\Agencia;
use App\Models\Empresa;
use App\Models\Cuis;
use App\Models\Cufd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use App\Models\Parametro;
use App\Models\Codigo;
use App\Models\Leyenda;
use App\Models\Catalogo;
use App\Models\ActividadDocumento;
use App\Helpers\BitacoraHelper;
use Illuminate\Support\Facades\Session;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cajas=Caja::all();
        $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        
        return view('caja.index',['cajas'=>$cajas,'dias'=>$dias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $caja=new Caja();
        $fecha=Carbon::now('America/La_Paz')->toDateString();
        $hora_inicio = Carbon::now('America/La_Paz')->format('H:i');
        return view('caja.create',['caja'=>$caja, 'fecha'=>$fecha, 'hora_inicio'=>$hora_inicio]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {          
        $sesion = Session::all();
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlCodigos = $ajuste->wsdl."/FacturacionCodigos?wsdl";
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";
        
        $userId = Auth::id();
        
        $empresa = Empresa::where('estado','A')->first();        
        $sucursal = Agencia::where('id',$sesion['agencia_id'])->first(); 
        $puntoVenta = PuntoVenta::where('id',$sesion['punto_venta_id'])->first();                                                                    
        $clienteCodigo = Ajuste::consumoSIAT($token,$wsdlCodigos);
        $clienteSincro = Ajuste::consumoSIAT($token,$wsdlSincronizacion);
        
        //Sincronizar CUIS
        $msjCuis = Cuis::sincroCUIS($clienteCodigo);
        if ($msjCuis == "") 
        {
            //Sincronizar CUFD
            $msjCufd = Cufd::sincroCUFD($clienteCodigo);
            if ($msjCufd == "") 
            {
                $cuis = Cuis::obtenerCuis();
                
                //Sincronizar Parametros
                $parametrosSincronizacion = array(
                    'SolicitudSincronizacion' => array(
                        'codigoAmbiente' => 2, 
                        'codigoPuntoVenta' => $puntoVenta->codigo,
                        'codigoSistema' => $empresa->codigo_sistema,
                        'codigoSucursal' => $sucursal->codigo,
                        'cuis' => $cuis->codigo_cuis,
                        'nit' => $empresa->nit
                    )
                );
                
                 //PARAMETROS
                 $responseParametros = Parametro::sincronizarParametro($clienteSincro, $parametrosSincronizacion, $cuis->id);            
                                        
                 //SECTOR
                 //Sincronizar Actividades
                 $responseActividades = Codigo::soapActividad($clienteSincro, $parametrosSincronizacion, $cuis->id);
 
                 //Sincronizar Leyendas
                 $responseLeyendas = Leyenda::soapLeyenda($clienteSincro, $parametrosSincronizacion, $cuis->id);            
                 
                 //Sincronizar Catalogos (Productos/Servicios)
                 $responseCatalogos = Catalogo::soapCatalogo($clienteSincro, $parametrosSincronizacion, $cuis->id);
                 
                 //Sincronizar Actividad Documento Sector
                 $responseActividadDocumento = ActividadDocumento::soapActividadDocumento($clienteSincro, $parametrosSincronizacion, $cuis->id);
 
                // $responseFechaHora = $clienteSincro->sincronizarFechaHora($parametrosSincronizacion);
                // $fechaHora = $responseFechaHora->RespuestaFechaHora->fechaHora; 

                $caja = new Caja($request->all());
                $caja->fecha = $request->fecha;
                
                $caja->hora_inicio = $request->hora_inicio;
                $caja->monto_apertura = $request->monto_apertura;

                $ultimaApertura = Caja::all()->last();
                
                if ($ultimaApertura==null || $ultimaApertura->fecha != $caja->fecha) {
                    Alert::warning('Warning', '¿Desea Continuar? Una vez realizada la apertura no se podrá modificar');
                    $caja->save();
                    // Registrar en Bitacora
                    BitacoraHelper::registrar('Apertura de Caja', 'Apertura de Caja realizada por el usuario: ' . Auth::user()->name, 'Caja');
                    return redirect('/caja')->with('toast_success','Apertura de Caja realizado exitosamente');
                } else {
                    return redirect('/caja')->with('errors','Ya se realizó la Apertura de Caja');
                }
            } else {
                return redirect('/caja')->with('errors',$msjCufd);
            }
        } else {
            return redirect('/caja')->with('toast_error',$msjCuis);
        }
        
         
           
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function show(Caja $caja)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {                
        $caja=Caja::all()->last();
        $fecha = Carbon::now('America/La_Paz')->toDateString();       
        $horai=date('00:00:00');
        $horaf=date('23:59:59');  

        $gastos=DB::table('compras')->whereBetween('fecha_compra',[$fecha.' '.$horai, $fecha.' '.$horaf])->get();
        $total_gasto=0;
        
        if (!is_null($gastos)) {
            foreach ($gastos as $gasto) {
                $total_gasto=$total_gasto+($gasto->pago_compra - $gasto->cambio_compra);
            }
        }

        $ganancias=DB::table('ventas')->whereBetween('fecha_venta',[$fecha.' '.$horai, $fecha.' '.$horaf])->get();
        $total_ganancia=0;
        if (!is_null($ganancias)) {
            foreach ($ganancias as $ganancia) {
                $total_ganancia=$total_ganancia+($ganancia->pago_venta - $ganancia->cambio_venta);
            }
        }
        
        return view('caja.edit',['caja'=>$caja, 'total_gasto'=>$total_gasto, 'total_ganancia'=>$total_ganancia]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Caja $caja)
    {
        $caja=Caja::all()->last();
        $hora_fin = Carbon::now('America/La_Paz')->format('H:i');
        
        $caja->b200=$request->b200;
        $caja->b100=$request->b100;
        $caja->b50=$request->b50;
        $caja->b20=$request->b20;
        $caja->b10=$request->b10;
        $caja->m5=$request->m5;
        $caja->m2=$request->m2;
        $caja->m1=$request->m1;
        $caja->m05=$request->m05;
        $caja->m02=$request->m02;
        $caja->m01=$request->m01;
        $caja->hora_fin=$hora_fin;
        $caja->monto_apertura=$request->monto_apertura;
        $caja->gastos=$request->gastos;
        $caja->ganancias=$request->ganancias;
        $caja->total=$request->total;

        $ultimoArqueo = Caja::all()->last();
        if ($ultimoArqueo -> fecha == $caja->fecha && $ultimoArqueo -> hora_fin <> null) {
            return redirect('/caja')->with('errors','Ya se realizó el Arqueo de Caja');    
        } elseif ($caja -> save()) {
            Alert::warning('Warning','Una vez completado el cierre no se podrá modificar');
            // Registrar en Bitacora
            BitacoraHelper::registrar('Arqueo de Caja', 'Arqueo de Caja realizado por el usuario: ' . Auth::user()->name, 'Caja');
            return redirect('/caja')->with('toast_success','Arqueo de Caja realizado exitosamente');
        } else {
            return view('caja.edit',['caja'=>$caja])->with('toast_error','Error al realizar el arqueo de caja');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Caja  $caja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caja $caja)
    {
        //
    }
}
