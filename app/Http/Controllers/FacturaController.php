<?php

namespace App\Http\Controllers;

use App\Helpers\BitacoraHelper;
use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Cuis;
use App\Models\Cufd;
use App\Models\TipoDocumento;
use App\Models\Ajuste;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \Milon\Barcode\DNS2D;
use Response;
use ZipArchive;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
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
        $facturas = Factura::all(); 
        foreach ($facturas as $fact) {
            $fact->enPlazoNota = Carbon::parse($fact->fechaEmision)
                                    ->addMonths(18)
                                    ->gte(Carbon::now('America/La_Paz')) ? true : false;

        }
        // dd($facturas);
        return view('factura.index',['factura'=>$facturas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy($idFactura, Request $request)
    {
        if ($request->ajax()) {
            $motivo = $request->motivo;
            $ajuste = Ajuste::first();
            $token = $ajuste->token;
            $wslSincronizacion = $ajuste->wsdl."/ServicioFacturacionCompraVenta?wsdl";
            $clienteFacturacion = Ajuste::consumoSIAT($token,$wslSincronizacion);
            $factura = Factura::find($idFactura);
            $empresa = Empresa::where('estado','A')->first();
            $cuis = Cuis::obtenerCuis();
            $cufd = Cufd::obtenerCufd(); 
            if ($clienteFacturacion->verificarComunicacion()->return->transaccion == "true") {
                $parametrosFactura = array(
                    'SolicitudServicioAnulacionFactura' => array(
                        'codigoAmbiente' => $empresa->ambiente,
                        'codigoDocumentoSector' => $factura->codigoDocumentoSector,
                        'codigoEmision' => 1,
                        'codigoModalidad' => $empresa->modalidad,
                        'codigoPuntoVenta' => $factura->codigoPuntoVenta,
                        'codigoSistema' => $empresa->codigo_sistema,
                        'codigoSucursal' => $factura->codigoSucursal,
                        'cufd' => $cufd->codigo_cufd,
                        'cuis' => $cuis->codigo_cuis,
                        'nit' => $empresa->nit,
                        'tipoFacturaDocumento' => 1, //ojo
                        'codigoMotivo' => $motivo,
                        'cuf' => $factura->cuf
                    )
                );
                $msjAnularFactura = Factura::soapAnularFactura($clienteFacturacion,$parametrosFactura,$factura->id);
                if ($msjAnularFactura == "") {
                    BitacoraHelper::registrar('Anulacion de Factura', 'Factura anulada por '.Auth::user()->usuario.' con motivo: '.$motivo, 'Factura');
                    return response()->json(['message'=>'Factura anulada'],200);
                } else {
                    return response()->json(['message'=>$msjAnularFactura],404);
                }
            } else {
                return response()->json(['message'=>'Error en la comunicación del Servicio'],404);
            }
        }
    }

    /**************************************
     * Soap Factura
     **************************************/
    public function emitirFactura($idFactura)
    {
        $factura = Factura::find($idFactura);
        $empresa = Empresa::first();
        
        $cuis = Cuis::obtenerCuis();
        $cufd = Cufd::obtenerCufd();

        $fecha_envio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fecha_envio = $fecha_envio.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);        
        $fecha_emision = Factura::deFechaNumero($fecha_envio);
        $factura->fechaEmision = $fecha_envio;
        $cuf =  Factura::generarCUF($factura->nitEmisor,      //$nit
                                    $factura->codigoSucursal,   //$sucursal [0=Casa Matriz; 1=Sucursal 1,..etc.]
                                    $fecha_emision,     //$fecha
                                    2,   //$modalidad [1=Electronica Linea; 2=Computarizada Linea; 3=Portal Web]
                                    1,  //$tipo_emision [1=Online; 2=Offline; 3=Masiva]
                                    1, //$tipo_factura [1=Doc Cred Fiscal; 2=Doc Sin Cred Fiscal; 3=Doc Ajuste]
                                    1,    //$tipo_documento_sector [1=Fac Compra Venta,...,24=Nota Credito Debito]
                                    $factura->numeroFactura,     //$nro_factura
                                    0                  //$pos
                                    );
        $factura->cuf = $cuf.$cufd->codigo_control;
        $factura->cufd = $cufd->codigo_cufd;
        $factura->save();
        //TODO: 1 - GENERAR XML
        $xml = Factura::generarXML($factura->id);
        
        //TODO: 2 - VALIDAR XML
        $msjError = Factura::validarXML($xml, 'facturaComputarizadaCompraVenta.xsd');                        
        if (empty($msjError)) 
        {
            //TODO: 3 - COMPRIMIR XML
            $zip = new ZipArchive();
            $gzipFileName = $factura->numeroFactura.'.zip';
            $path = public_path('/siat/facturas');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $path = public_path('/siat/facturas/'.$gzipFileName);

            $gzdata = gzencode($xml);
            
            file_put_contents($path,$gzdata);
            
            $zp = gzopen($path, "w9");
            gzwrite($zp, $xml);
            gzclose($zp);

            $hashArchivo = hash('sha256', $gzdata);
            
            //TODO: 4 Enviar Factura SIAT                               
            $ajuste = Ajuste::first();
            $token = $ajuste->token;
            $wsdlSincronizacion = $ajuste->wsdl."/ServicioFacturacionCompraVenta?wsdl";
            $clienteFacturacion = Ajuste::consumoSIAT($token,$wsdlSincronizacion);
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
                $responseRecepcionFactura = Factura::soapRecepcionFactura($clienteFacturacion, $parametrosFactura, $idFactura);
                if ($responseRecepcionFactura == "VALIDADA") 
                {
                    BitacoraHelper::registrar('Emision de Factura', 'Factura emitida por '.Auth::user()->usuario.' con CUF: '.$factura->cuf, 'Factura');
                    return redirect('/factura')->with('toast_success','Factura Recepcionada');
                } else {
                    return redirect('/factura')->with('toast_error', $responseRecepcionFactura);
                }
            }            
        } else {
            return redirect("/factura")->with('toast_error','Error en Formato XSD: '.implode(" ", $msjError));
        }
        
    }    
    
    /**************************************
     * Imprimir Factura XML
     **************************************/
    public function generarXML($idfactura)
    {  
        $factura=Factura::find($idfactura);        
        $detallefactura=DetalleFactura::where('factura_id','=',$idfactura)->get();
        $fecha=Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');        
        
        $gzPath = public_path('siat/facturas/'.$factura->numeroFactura.'.gz');
        //Si existe el archivo comrimido, descomprimir y mostrar
        if (file_exists($gzPath)) {
            $xmlContent = gzdecode(file_get_contents($gzPath));
            return response($xmlContent)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Disposition', 'attachment; filename="'.$factura->numeroFactura.'.xml"');
        }
        //PASO 1: Generar Cadena XML
        $xml = Factura::generarXML($idfactura);
        
        //PASO 2: Validar XML con XSD
        $error = Factura::validarXML($xml,'facturaComputarizadaCompraVenta.xsd');
        
        if (!empty($error)) {
            return redirect("/factura")->with('toast_error','Error en Formato XSD: '.implode(" ", $error));
        }
        //PASO 3: Guardar XML y comprimir
        $gzipFileName = $factura->numeroFactura.'.gz';
        $path = public_path('/siat/facturas');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $path = public_path('/siat/facturas/'.$gzipFileName);
        file_put_contents($path, gzencode($xml, 9));
        return response($xml,200)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Disposition', 'attachment; filename="'.$factura->numeroFactura.'.xml"');        
    }

    /**************************************
     * Ver Factura en Portal SIAT
     **************************************/
    public function verSIAT($idfactura)
    {
        $factura = Factura::find($idfactura);
        return redirect("https://siat.impuestos.gob.bo/consulta/QR?".'nit='.$factura->nitEmisor.'&cuf='.$factura->cuf.'&numero='.$factura->numeroFactura.'&t=2');
    }
    
    public function generadorQR($texto)
    {
        $d=new DNS2D();
        $cadena = $d->getBarcodePNGPath($texto, "QRCODE");
        return $cadena;
    }

    /**************************************
     * Revertir Anulacion de Factura
     **************************************/
    public function revertirAnulacionFactura($idFactura)
    {
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wslSincronizacion = $ajuste->wsdl."/ServicioFacturacionCompraVenta?wsdl";
        $clienteFacturacion = Ajuste::consumoSIAT($token,$wslSincronizacion);
        $factura = Factura::find($idFactura);
        $empresa = Empresa::where('estado','A')->first();
        $cuis = Cuis::obtenerCuis();
        $cufd = Cufd::obtenerCufd();
        if ($clienteFacturacion->verificarComunicacion()->return->transaccion == "true") {
            $parametrosFactura = array(
                'SolicitudServicioReversionAnulacionFactura' => array(
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
                    'cuf' => $factura->cuf
                )
            );
            $msjRevertirAnulacion = Factura::soapRevertirAnulacionFactura($clienteFacturacion, $parametrosFactura, $factura->id);
            if ($msjRevertirAnulacion == "") {
                BitacoraHelper::registrar('Revertir Anulacion de Factura', 'Anulacion revertida por '.Auth::user()->usuario.' de la factura con CUF: '.$factura->cuf, 'Factura');
                return response()->json(['message'=>'Anulacion Revertir'],200);
            } else {
                return redirect('/factura')->with('toast_error',$msjRevertirAnulacion);
            }            
        } else {
            return response()->json(['message'=>'Error en la comunicación del Servicio'],404);
        }
        
    }
}
