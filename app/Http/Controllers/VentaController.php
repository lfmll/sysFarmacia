<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\Medicamento;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Cuis;
use App\Models\Cufd;
use App\Models\Codigo;
use App\Models\Catalogo;
use App\Models\Parametro;
use App\Models\Leyenda;
use App\Models\Ajuste;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Luecano\NumeroALetras\NumeroALetras;
use GuzzleHttp\Client;
use SoapClient;
use ZipArchive;

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

        $evento = Evento::where('estado', 'Abierto')->first();
        
        $cuis = Cuis::obtenerCuis();

        $codigoInicial = Codigo::where('cuis_id',$cuis->id)->orderBy('descripcion','ASC')->first();
        $catalogoInicial = Catalogo::join('codigos','catalogos.codigo_actividad','=','codigos.codigo_caeb')
                                    ->where('codigos.codigo_caeb','=',$codigoInicial->codigo_caeb)                                    
                                    ->orderBy('catalogos.descripcion_producto','ASC')                                    
                                    ->pluck('catalogos.descripcion_producto','catalogos.codigo_producto');
                                     
        if ($catalogoInicial->isEmpty()) {
            $catalogoInicial = [];
            $lotesm = [];
        } else {
            $productoInicial = Catalogo::where('codigo_actividad',$codigoInicial->codigo_caeb)
                                    ->orderBy('descripcion_producto','ASC')
                                    ->first();
                                    
            $lotesm = DB::table('lotes')
                        ->join('medicamentos','medicamentos.id','=','lotes.medicamento_id')
                        ->leftjoin('laboratorios','lotes.laboratorio_id','=','laboratorios.id')
                        ->where('medicamentos.codigo_actividad','=',$codigoInicial->codigo_caeb)
                        ->where('medicamentos.codigo_producto_sin','=',$productoInicial->codigo_producto)
                        ->where('medicamentos.stock','>',0)
                        ->where('lotes.estado','A')     
                        ->select('lotes.*','medicamentos.nombre_comercial','laboratorios.nombre')             
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
                ->with('catalogos',$catalogoInicial)
                ->with('evento',$evento);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {             
        $fecha_envio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fecha_envio = $fecha_envio.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);     
        $usuario = auth()->user();        
        $cuis = Cuis::obtenerCuis(); 
        $evento = Evento::where('estado', 'Abierto')->first();            
        $empresa = Empresa::first();
        $agencia = Agencia::where('empresa_id',$empresa->id)->first();   //ojo
        $puntoVenta = PuntoVenta::where('agencia_id', $agencia->id)
                                ->where('user_id', $usuario->id)
                                ->first();
        $leyenda = Leyenda::where('cuis_id',$cuis->id)
                            ->inRandomOrder()
                            ->first();
        $DocSector = Parametro::join('tipo_parametros','tipo_parametros.id','=','parametros.tipo_parametro_id')
                            ->where('tipo_parametros.nombre','TIPO DOCUMENTO SECTOR')
                            ->where('parametros.descripcion','FACTURA COMPRA-VENTA')
                            ->first();
            
        if (!is_null($evento))  //Con Evento Significativo
        {
            //Registrar Venta y Detalle Venta
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
            
            $venta->save();

            $dcantidad = $request->get('dcantidad');
            $dprecio = $request->get('dprecio');
            $dlote = $request->get('dcodigo');
            $dsubtotal = $request->get('dsubtotal');                    
            
            $n = count($dcantidad);
            
            for ($i=0; $i < $n; $i++) { 
                $detalle = new DetalleVenta();
                $detalle->venta_id = $venta->id;
                $detalle->cantidad = $dcantidad[$i];
                $detalle->precio_venta = $dprecio[$i];                
                $detalle->lote_id = $dlote[$i];                                                       
                $detalle->save();

                $lote = Lote::find($dlote[$i]);                
                $cantlote = $lote->cantidad;
                $cantlote = $cantlote - $dcantidad[$i];                
                $lote->cantidad = $cantlote;
                $lote->precio_venta = $dprecio[$i];
                $lote->save();
                
                if (!is_null($lote->medicamento_id)) {
                    $medicamento = Medicamento::find($lote->medicamento_id);                            
                    $medicamento->stock = $medicamento->stock - $dcantidad[$i];
                    $medicamento->save();
                }
            }
            //Registar Factura y Detalle Factura
            $factura = new Factura($request->all());
            $fecha_emision = Factura::deFechaNumero($fecha_envio);
            $cantFactura = Factura::count();

            //CABEZA FACTURA
            $factura->nitEmisor = $empresa->nit;
            $factura->razonSocialEmisor = $empresa->nombre;    //Actividad, Codigo
            $factura->municipio = $agencia->municipio;
            $factura->telefono = $agencia->telefono;
            $factura->numeroFactura = $cantFactura+1;                 
            $factura->cufd = null;
            $factura->cuf = null;
            $factura->codigoSucursal = $agencia->codigo;
            $factura->direccion = $agencia->direccion;
            if (is_null($puntoVenta)) {
                $factura->codigoPuntoVenta = 0;
            } else {
                $factura->codigoPuntoVenta = $puntoVenta->codigo;
            }                                       
            $factura->fechaEmision = $fecha_envio;
            $factura->nombreRazonSocial = $request->cliente;
            $factura->codigoTipoDocumentoIdentidad = $request->tipodoc;
            $factura->numeroDocumento = $request->nrodoc;
            $factura->complemento = $request->cmpl;
            $factura->codigoCliente = $request->cid;
            $factura->codigoMetodoPago = $request->forma_pago;
            $factura->numeroTarjeta = $request->ppago_tarjeta;
            $factura->montoTotal = $request->eTotal;
            $factura->montoTotalSujetoIva = $request->eTotalIVA;
            $factura->codigoMoneda = 1; 
            $factura->tipoCambio = 1;
            $factura->montoTotalMoneda = $request->eTotal;
            $factura->montoGiftCard = $request->ppago_giftcard;
            $factura->descuentoAdicional = $request->eDescuento;
            $factura->codigoExcepcion = null;
            $factura->cafc = null;
            $factura->leyenda = $leyenda->descripcion_leyenda;
            $factura->usuario = $usuario->name;
            $factura->codigoDocumentoSector = $DocSector->codigo_clasificador;
            $factura->estado = "RECEPCION PENDIENTE";
            $factura->venta_id = $venta->id;
            $factura->evento_id = $evento->id; // Asignar el evento a la factura
            $factura->codigoRecepcion = null; // Inicialmente nulo, se actualizara
            $factura->save();

            $evento->cantidadFacturas = $evento->cantidadFacturas + 1;
            $evento->save();
                
            //DETALLE FACTURA
            for ($j=0; $j < $n; $j++) { 
                $detalle_factura = new DetalleFactura;
                $producto = Medicamento::join('lotes','medicamentos.id','=','lotes.medicamento_id')
                                        ->where('lotes.id','=',$dlote[$j])
                                        ->first();
                $detalle_factura->actividadEconomica = $producto->codigo_actividad;
                $detalle_factura->codigoProductoSin = $producto->codigo_producto_sin;
                $detalle_factura->codigoProducto = $producto->codigo_producto;
                $detalle_factura->descripcion = $producto->nombre_comercial;
                $detalle_factura->cantidad = $dcantidad[$j];
                $detalle_factura->unidadMedida = $producto->codigo_clasificador;
                $detalle_factura->precioUnitario = $dprecio[$j];
                $detalle_factura->montoDescuento = 0;   //ojo
                $detalle_factura->subTotal = $dsubtotal;
                $detalle_factura->numeroSerie = null;
                $detalle_factura->numeroImei = null;
                $detalle_factura->factura_id = $factura->id;
                $detalle_factura->save();
            }
            return redirect('/venta')->with('toast_success', 'Venta realizada y Factura a espera de ser Validada.');
        } 
        else    //Sin Evento Significativo
        {
            if (!is_null($cuis)) 
            {
                $cufd = Cufd::obtenerCufd();            
                if (!is_null($cufd)) 
                {                                                                            
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
                    
                    $venta->save();

                    $dcantidad = $request->get('dcantidad');
                    $dprecio = $request->get('dprecio');
                    $dlote = $request->get('dcodigo');
                    $dsubtotal = $request->get('dsubtotal');             
                    $n = count($dcantidad);
                    
                    for ($i=0; $i < $n; $i++) { 
                        $detalle = new DetalleVenta();
                        $detalle->venta_id = $venta->id;
                        $detalle->cantidad = $dcantidad[$i];
                        $detalle->precio_venta = $dprecio[$i];                
                        $detalle->lote_id = $dlote[$i];                                                       
                        $detalle->save();

                        $lote = Lote::find($dlote[$i]);
                        $cantlote = $lote->cantidad;
                        $cantlote = $cantlote - $dcantidad[$i];                
                        $lote->cantidad = $cantlote;
                        $lote->precio_venta = $dprecio[$i];
                        $lote->save();
                        
                        if (!is_null($lote->medicamento_id)) {
                            $medicamento = Medicamento::find($lote->medicamento_id);                            
                            $medicamento->stock = $medicamento->stock - $dcantidad[$i];
                            $medicamento->save();
                        }
                    }
                                                                            
                    $factura = new Factura($request->all());
                    $fecha_emision = Factura::deFechaNumero($fecha_envio);
                    $cantFactura = Factura::count();

                    //CABEZA FACTURA
                    $factura->nitEmisor = $empresa->nit;
                    $factura->razonSocialEmisor = $empresa->nombre;    //Actividad, Codigo
                    $factura->municipio = $agencia->municipio;
                    $factura->telefono = $agencia->telefono;
                    $factura->numeroFactura = $cantFactura+1; 
                        
                    $cuf =  Factura::generarCUF($empresa->nit,      //$nit
                                                $agencia->codigo,   //$sucursal [0=Casa Matriz; 1=Sucursal 1,..etc.]
                                                $fecha_emision,     //$fecha
                                                $empresa->modalidad,//$modalidad [1=Electronica Linea; 2=Computarizada Linea; 3=Portal Web]
                                                1,  //$tipo_emision [1=Online; 2=Offline; 3=Masiva]
                                                1, //$tipo_factura [1=Doc Cred Fiscal; 2=Doc Sin Cred Fiscal; 3=Doc Ajuste]
                                                1,    //$tipo_documento_sector [1=Fac Compra Venta,...,24=Nota Credito Debito]
                                                $cantFactura+1,     //$nro_factura
                                                0                  //$pos
                                                );
                    $factura->cufd = $cufd->codigo_cufd;
                    $factura->cuf = $cuf.$cufd->codigo_control;
                    $factura->codigoSucursal = $agencia->codigo;
                    $factura->direccion = $agencia->direccion;
                    if (is_null($puntoVenta)) {
                        $factura->codigoPuntoVenta = 0;
                    } else {
                        $factura->codigoPuntoVenta = $puntoVenta->codigo;
                    }                                       
                    $factura->fechaEmision = $fecha_envio;
                    $factura->nombreRazonSocial = $request->cliente;
                    $factura->codigoTipoDocumentoIdentidad = $request->tipodoc;
                    $factura->numeroDocumento = $request->nrodoc;
                    $factura->complemento = $request->cmpl;
                    $factura->codigoCliente = $request->cid;
                    $factura->codigoMetodoPago = $request->forma_pago;
                    $factura->numeroTarjeta = $request->ppago_tarjeta;
                    $factura->montoTotal = $request->eTotal;
                    $factura->montoTotalSujetoIva = $request->eTotalIVA;
                    $factura->codigoMoneda = 1; 
                    $factura->tipoCambio = 1;
                    $factura->montoTotalMoneda = $request->eTotal;
                    $factura->montoGiftCard = $request->ppago_giftcard;
                    $factura->descuentoAdicional = $request->eDescuento;
                    $factura->codigoExcepcion = null;
                    $factura->cafc = null;
                    $factura->leyenda = $leyenda->descripcion_leyenda;
                    $factura->usuario = $usuario->name;
                    $factura->codigoDocumentoSector = $DocSector->codigo_clasificador;
                    $factura->estado = "RECEPCION PENDIENTE";
                    $factura->venta_id = $venta->id;
                    $factura->save();
                        
                    //DETALLE FACTURA
                    for ($j=0; $j < $n; $j++) { 
                        $detalle_factura = new DetalleFactura;
                        $producto = Medicamento::join('lotes','medicamentos.id','=','lotes.medicamento_id')
                                                ->where('lotes.id','=',$dlote[$j])
                                                ->first();
                        $detalle_factura->actividadEconomica = $producto->codigo_actividad;
                        $detalle_factura->codigoProductoSin = $producto->codigo_producto_sin;
                        $detalle_factura->codigoProducto = $producto->codigo_producto;
                        $detalle_factura->descripcion = $producto->nombre_comercial;
                        $detalle_factura->cantidad = $dcantidad[$j];
                        $detalle_factura->unidadMedida = $producto->codigo_clasificador;
                        $detalle_factura->precioUnitario = $dprecio[$j];
                        $detalle_factura->montoDescuento = 0;   //ojo
                        $detalle_factura->subTotal = $dsubtotal;                      
                        $detalle_factura->numeroSerie = null;
                        $detalle_factura->numeroImei = null;
                        $detalle_factura->factura_id = $factura->id;
                        $detalle_factura->save();
                    } 
                                                            
                    //FACTURAR
                    //TODO: 1 - GENERAR XML
                    $xml = Factura::generarXML($factura->id);
                    //TODO: 2 - VALIDAR XML
                    $msjError = Factura::validarXML($xml, 'facturaComputarizadaCompraVenta.xsd');                        
                    if (empty($msjError)) 
                    {
                        //TODO: 3 - COMPRIMIR XML
                        // $zip = new ZipArchive();
                        
                        // $zipNombreArchivo = 'factura'.$factura->numeroFactura.'.zip';
                        // $xmlNombreArchivo = $factura->numeroFactura.'.xml';                        
                        // $path = public_path('/siat/facturas');
                        // if (!file_exists($path)) {
                        //     mkdir($path, 0777, true);
                        // }
                        // $ziPath = $path.'/'.$zipNombreArchivo;

                        // if ($zip->open($ziPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                        //     $zip->addFromString($xmlNombreArchivo, $xml); //Anadir el XML al ZIP                            
                        //     $zip->close();  // Cerrar el ZIP
                        //     $hashArchivo = hash_file('sha256', $ziPath);  // Calcular el hash del archivo ZIP                            
                        //     $contenidoZip = file_get_contents($ziPath); // Leer el contenido del ZIP    
                        //     $archivoZipBase64 = base64_encode($contenidoZip); // Codificar a Base64                                                        
                        // } else {
                        //     return redirect('/venta')->with('toast_error', 'Error al crear el archivo ZIP.');
                        // }
                        /////////***************/////////
                        //1. Generar nombres y rutas de archivos
                        $gzipFileName = $factura->numeroFactura.'.gz';
                        $path = public_path('/siat/facturas/');
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $gzPath = $path . $gzipFileName;
                        //2. Comprimir el XML con gzip
                        $gzdata = gzencode($xml,9); // Comprimir el XML con gzip  
                                               
                        file_put_contents($gzPath, $gzdata); // Guardar el archivo comprimido                        
                        //3. Calcular el hash del archivo comprimido
                        $hashArchivo = hash_file('sha256', $gzPath); // Calcular el hash del archivo comprimido
                        
                        // $zp = gzopen($path, 'w9'); // Abrir el archivo comprimido
                        // gzwrite($zp, $xml); // Escribir el contenido comprimido                                                
                        // gzclose($zp); // Cerrar el archivo comprimido
                        
                        /////////***************/////////
                        //TODO: 4 Enviar Factura SIAT                    
                        $ajuste = Ajuste::first();
                        $token = $ajuste->token;
                        $wslSincronizacion = $ajuste->wsdl."/ServicioFacturacionCompraVenta?wsdl";
                        $clienteFacturacion = Ajuste::consumoSIAT($token,$wslSincronizacion);
                        if ($clienteFacturacion->verificarComunicacion()->return->transaccion == "true") 
                        {
                            $parametrosFactura = array(
                                'SolicitudServicioRecepcionFactura' => array(
                                    'codigoAmbiente' => 2,
                                    'codigoDocumentoSector' => $factura->codigoDocumentoSector,
                                    'codigoEmision' => 1,
                                    'codigoModalidad' => 2,
                                    'codigoPuntoVenta' => $factura->codigoPuntoVenta,
                                    'codigoSistema' => $empresa->codigo_sistema,
                                    'codigoSucursal' => $factura->codigoSucursal,
                                    'cufd' => $cufd->codigo_cufd,
                                    'cuis' => $cuis->codigo_cuis,
                                    'nit' => $empresa->nit,
                                    'tipoFacturaDocumento' => 1, //ojo
                                    'archivo' => $gzdata,
                                    'fechaEnvio' => $fecha_envio,
                                    'hashArchivo' => $hashArchivo
                                )
                            );
                            $responseRecepcionFactura = Factura::soapRecepcionFactura($clienteFacturacion, $parametrosFactura, $factura->id);
                            if (($responseRecepcionFactura == "VALIDADA")) {
                                return redirect('/venta')->with('toast_success','Factura Recepcionada');
                            } else {
                                return redirect('/venta')->with('toast_error', $responseRecepcionFactura);
                            }                    
                        }            
                    } else {
                        return redirect("/venta")->with('toast_error','Error en Formato XSD: '.implode(" ", $msjError));
                    }                             
                } else {
                    return redirect("/venta")->with('toast_error','CUFD desactualizado');
                }                                                 
            } else {
                return redirect("/venta")->with('toast_error','CUIS desactualizado');
            }
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
