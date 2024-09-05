<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Medicamento;
use App\Models\Insumo;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\Cuis;
use App\Models\Codigo;
use App\Models\Catalogo;
use App\Models\Parametro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Luecano\NumeroALetras\NumeroALetras;
use GuzzleHttp\Client;
use SoapClient;

class VentaController extends Controller
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
        $ventas=Venta::all();
        return view('venta.index',['ventas'=>$ventas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $venta=new Venta();        
        $fecha_venta = Carbon::now('America/La_Paz')->toDateString();
        $horai = date('00:00:00');
        $horaf = date('23:59:59');

        $cuis = Cuis::obtenerCuis();

        $codigoInicial = Codigo::where('cuis_id',$cuis->id)->orderBy('descripcion','ASC')->first();
        $catalogoInicial = Catalogo::join('codigos','catalogos.codigo_actividad','=','codigos.codigo_caeb')
                                    ->where('codigos.codigo_caeb','=',$codigoInicial->codigo_caeb)                                    
                                    ->orderBy('catalogos.descripcion_producto','ASC')                                    
                                    ->pluck('catalogos.descripcion_producto','catalogos.codigo_producto');
                                
        if ($catalogoInicial->isEMpty()) {
            $catalogoInicial = [];
            $lotesm = [];
        } else {
            $productoInicial = Catalogo::where('codigo_actividad',$codigoInicial->codigo_caeb)
                                    ->orderBy('descripcion_producto','ASC')
                                    ->first();
            $lotesm = Lote::join('medicamentos','lotes.medicamento_id','=','medicamentos.id')
                        ->join('laboratorios','lotes.laboratorio_id','=','laboratorios.id')
                        ->where('medicamentos.codigo_actividad','=',$codigoInicial->codigo_caeb)
                        ->where('medicamentos.codigo_producto_sin','=',$productoInicial->codigo_producto)
                        ->where('lotes.estado','A')                  
                        ->get();
        }
                        
        $clientes = Cliente::where('estado','A')
                    ->get();        
        
        $actividades = Codigo::where('cuis_id',$cuis->id)
                            ->orderBy('descripcion','ASC')
                            ->pluck('descripcion','codigo_caeb');        
                                   
        $tipo_documento_identidad = Parametro::join('tipo_parametros','parametros.tipo_parametro_id','=','tipo_parametros.id')
                                            ->where('tipo_parametros.nombre','=','TIPO DOCUMENTO IDENTIDAD')
                                            ->orderBy('parametros.codigo_clasificador','ASC')
                                            ->get();
        
        return view('venta.create',['venta'=>$venta])
                ->with('lotesm',$lotesm)
                ->with('clientes',$clientes)
                ->with('tipo_documento_identidad',$tipo_documento_identidad)
                ->with('actividades',$actividades)
                ->with('catalogos',$catalogoInicial);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // dd($request);
        // try {            
        //     DB::beginTransaction();            
            $venta = new Venta($request->all());            
            $venta->fecha_venta = Carbon::now('America/La_Paz')->toDateTimeString();
            $venta->subtotal = $request->eSubTotal;
            $venta->descuento = $request->eDescuento;
            $venta->total = $request->eTotal;
            $venta->monto_giftcard = $request->ppago_giftcard;
            
            $venta->importe_iva = $request->eTotalIVA;
            
            $literal = new NumeroALetras();
            $venta->literal = $literal->toMoney($request->eTotalIVA,2);
            
            $venta->estado='A';
            $venta->metodo_pago_id = $request->forma_pago;
            $venta->monto_pagar = $request->ppago_efectivo;
            $venta->cambio_venta = $request->cambio;
            $venta->monto_giftcard = $request->ppago_giftcard;
            
            // $venta->save();

            $dcantidad = $request->get('dcantidad');
            $dprecio = $request->get('dprecio');
            $dlote = $request->get('dcodigo');
            
            $n = count($dcantidad);
            
            for ($i=0; $i < $n; $i++) { 
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->cantidad = $dcantidad[$i];
                $detalle->precio_venta = $dprecio[$i];                
                $detalle->lote_id = $dlote[$i];                                                       
                // $detalle->save();

                $lote = Lote::find($dlote[$i]);
                $cantlote = $lote->cantidad;
                $cantlote = $cantlote - $dcantidad[$i];                
                $lote->cantidad = $cantlote;
                $lote->precio_venta = $dprecio[$i];
                // $lote->save();
                
                if (!is_null($lote->medicamento_id)) {
                    $medicamento = Medicamento::find($lote->medicamento_id);
                    $medicamento->stock = $medicamento->stock - $dcantidad[$i];
                    // $medicamento->save();
                }
            }
            
    
            $tdcodigoDocSec = $request->get('tdcodigoDocSec');
            $fecha_emision=Carbon::now('America/La_Paz')->format('YmdHisv');
            $empresa = Empresa::first();
            $agencia = Agencia::where('empresa_id',$empresa->id)->first();
            
            $factura = new Factura($request->all());
            $cantFactura = Factura::count();
            $factura->nitEmisor = $empresa->nit;
            $factura->razonSocialEmisor = $empresa->actividad;
            $factura->municipio = $agencia->municipio;
            $factura->telefono = $agencia->telefono;
            $factura->numeroFactura = $cantFactura++; 
            
            $factura->cuf =  Factura::generarCUF(123456789, //$empresa->nit,    //$nit
                                                0, //$agencia->id,       //$sucursal [0=Casa Matriz; 1=Sucursal 1,..etc.]
                                                20190113163721231, //$fecha_emision,     //$fecha
                                                1,//$modalidad [1=Electronica Linea; 2=Computarizada Linea; 3=Portal Web]
                                                1,  //$tipo_emision [1=Online; 2=Offline; 3=Masiva]
                                                1,//$tipo_factura [1=Doc Cred Fiscal; 2=Doc Sin Cred Fiscal; 3=Doc Ajuste]
                                                1,    //$tipo_documento_sector [1=Fac Compra Venta,...,24=Nota Credito Debito]
                                                1,               //$nro_factura
                                                0                   //$pos
                                            );// llenar
                                            dd($factura->cuf);
                                            // dd($this->base16("000012345678920190113163721231000011101000000000100001"));
            if ($request->Factura=="factura") {
                $nit=$request->Nit;
                $razon=$request->Razon;
                $autorizacion=$request->Autorizacion;
            }
            
            DB::commit();

        // } catch (\Exception $e) {
        //     DB::rollback();
        // }
        if ($venta->save()) {
            return redirect("/venta")->with('toast_success','Cobro realizado exitosamente');
        }else {
            return view("venta.create",["venta"=>$venta])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show($venta)
    {
        $detalleventas=DetalleVenta::where('venta_id',$venta)->get();        
        return view("venta.detalle",["detalleventas"=>$detalleventas]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        //
    }

    public function entrada(){
        return view("venta.entrada");
    }


}
