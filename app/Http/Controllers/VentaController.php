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
            $venta->literal = $literal->toMoney($venta->eTotalIVA,2);
            
            $venta->estado='A';
            $venta->metodo_pago_id = $request->forma_pago;
            $venta->monto_pagar = $request->ppago_efectivo;
            $venta->cambio_venta = $request->cambio;
            $venta->monto_giftcard = $request->ppago_giftcard;
            
            // $venta->save();

            $dcantidad = $request->get('dcantidad');
            $dprecio = $request->get('dprecio');
            $dlote = $request->get('dcodigo');
            
            $cont = 0;
            $n = count($dcantidad);
            
            while ($cont < $n) {
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->cantidad = $dcantidad[$cont];
                $detalle->precio_venta = $dprecio[$cont];                
                $detalle->lote_id = $dlote[$cont];                                                       
                // $detalle->save();

                $lote = Lote::find($dlote[$cont]);
                $cantlote = $lote->cantidad;
                $cantlote = $cantlote - $dcantidad[$cont];                
                $lote->cantidad = $cantlote;
                $lote->precio_venta = $dprecio[$cont];
                // $lote->save();
                
                if (!is_null($lote->medicamento_id)) {
                    $medicamento = Medicamento::find($lote->medicamento_id);
                    $medicamento->stock = $medicamento->stock - $dcantidad[$cont];
                    // $medicamento->save();
                }               
                $cont = $cont + 1;
            }

            //Factura
            
            // $client = new \SoapClient('https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl');
            // $client = new SoapClient('https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl'); //SERVICIO DE OBTENCIÓN DE CÓDIGOS
            // $client = new \SoapClient('https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl');
            // $client = new \SoapClient('https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl');
            
            // $respuesta = $client->request('GET','https://pilotosiatservicios.impuestos.gob.bo/v2/verificarComunicacion?wsdl=');

            // $parametros = [
            //     'codigoAmbiente' => 2,
            //     'codigoModalidad'   => 2,
            //     'codigoPuntoVenta'  => 0,
            //     'codigoSistema' => '7C49BFA4983BC9FAE824BA6',
            //     'codigoSucursal'    => 0,                
            //     'nit'   => '8928903012'                                              
            // ];
            $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJMbWVkaW5hMzAxMiIsImNvZGlnb1Npc3RlbWEiOiI3QzQ5QkZBNDk4M0JDOUZBRTgyNEJBNiIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFMT3dOTEt3TkRBMk1EUUNBQWhwY3d3S0FBQUEiLCJpZCI6MzA0MTU3MSwiZXhwIjoxNzI1NjQwODgyLCJpYXQiOjE3MTUwMjgwNTIsIm5pdERlbGVnYWRvIjo4OTI4OTAzMDEyLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.CafF0rusf1JiihcRHUWeZKpUc6_R46sfgh8c-SYINcYKyOvX4a3QmOQEAC8aK0rTw-bvMGD-nPt8-IPwde30tA';
            

            // $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v1/FacturacionCodigos?wsdl";
            $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl";

            $opts = array(
                'http'=> array(
                    'header' => "apikey: TokenApi $token",
                )
            );
            
            $context = stream_context_create($opts);
            
            $client = new SoapClient($wsdl, [ 
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,                  
            ]);
            $respons = $client->verificarComunicacion();

            
            dd($respons);
    
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
            
            $factura->cuf =  $this->generarCUF(123456789, //$empresa->nit,    //$nit
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

    /**************************************
     * Generar CUF
     **************************************/
    public function generarCUF($nit, $sucursal, $fecha, $modalidad, $tipo_emision, $tipo_factura, $tipo_documento_sector, $nro_factura, $pos){
        /**
         * PASO 1 y PASO 2 Completa con ceros cada campo y concatena todo en una
         * sola cadena
         */
        $cadena = "";
        $cadena .= str_pad($nit, 13, '0', STR_PAD_LEFT);
        $cadena .= $fecha;
        $cadena .= str_pad($sucursal, 4, '0', STR_PAD_LEFT);
        $cadena .= $modalidad;
        $cadena .= $tipo_emision;
        $cadena .= $tipo_factura;
        $cadena .= str_pad($tipo_documento_sector, 2, '0', STR_PAD_LEFT);
        $cadena .= str_pad($nro_factura, 10, '0', STR_PAD_LEFT);
        $cadena .= str_pad($pos, 4, '0', STR_PAD_LEFT);
        /**
         * PASO 3 Obtiene modulo 11 y adjunta resultado a la cadena
         */
        $cadena .= $this->calculaDigitoMod11($cadena, 1, 9, false);
        
        /**
         * PASO 4 Aplica base16
         */
        $baseH = $this->base16($cadena);
        
        return $baseH;
        // return $cadena;

    }

    public function calculaDigitoMod11(string $cadena, int $numDig, int $limMult, bool $sw_10){
        if (!$sw_10) $numDig = 1;
        
        for ($n=1; $n <= $numDig; $n++) { 
            $suma = 0;
            $mult = 2;
            for ($i=strlen($cadena)-1; $i >= 0 ; $i--) { 
                $suma += ($mult * substr($cadena, $i, $i+1));
                if (++$mult > $limMult) {
                    $mult = 2;
                }
            }
            if ($sw_10) {
                $dig = (($suma * 10) % 11) % 10;
            } else {
                $dig = $suma % 11;
            }
            if ($dig == 10) {
                $cadena .= "1";
            }
            if ($dig == 11) {
                $cadena .= "0";
            }
            if ($dig < 10) {
                $cadena .= $dig;
            }
        }
        return substr($cadena, strlen($cadena) - $numDig, strlen($cadena));
    }

    public function base16($nro, $touppercase = true) {
        $hexvalues = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $hexval = '';
        while ($nro != '0') {
            $hexval = $hexvalues[bcmod($nro, '16')].$hexval;
            $nro = bcdiv($nro, '16', 0);
        }
        return ($touppercase) ? strtoupper($hexval):$hexval;
    }

}
