<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Cuis;
use App\Models\Cufd;
use App\Models\TipoDocumento;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \Milon\Barcode\DNS2D;
use Response;
use ZipArchive;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $factura = Factura::all();             
        return view('factura.index',['factura'=>$factura]);
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
            $empresa = Empresa::first();
            $cuis = Cuis::obtenerCuis();
            $cufd = Cufd::obtenerCufd(); 
            if ($clienteFacturacion->verificarComunicacion()->return->transaccion == "true") {
                $parametrosFactura = array(
                    'SolicitudServicioAnulacionFactura' => array(
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
                        'codigoMotivo' => $motivo,
                        'cuf' => $factura->cuf
                    )
                );
                $msjAnularFactura = Factura::soapAnularFactura($clienteFacturacion,$parametrosFactura,$factura->id);
                if ($msjAnularFactura == "") {
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
        $agencia = Agencia::where('empresa_id',$empresa->id)->first();   //ojo
        
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
                $responseRecepcionFactura = Factura::soapRecepcionFactura($clienteFacturacion, $parametrosFactura, $idFactura);
                if ($responseRecepcionFactura == "VALIDADA") 
                {
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
     * Firmar Factura
     **************************************/
    public function firmarFactura($idfactura)
    {   
        
    }
    public function existeArchivos($filexml, $xsd): bool {
        if (!file_exists(public_path('siat/'.$filexml))|| !file_exists(public_path('siat/'.$xsd))) {
            return false;
        }        
        return true;
    }

    public function validarArchivo($filexml, $xsd)
    {
        $xmlReader = new \XMLReader();
        $xmlReader->open(public_path('siat/'.$filexml));
        $xmlReader->setParserProperty(\XMLReader::VALIDATE, true);
        $xmlReader->setSchema(public_path('siat/'.$xsd));

        \libxml_use_internal_errors(true);

        $msj = [];

        while ($xmlReader->read()) {
            if (!$xmlReader->isValid()) {
                $err = \libxml_get_last_error();
                if ($err && $err instanceof \libXMLError) {
                    $msj[] = \trim($err->message). 'on line '.$err->line;
                }
            }
        }
        return $msj;
    }
    
    /**************************************
     * Imprimir Factura XML
     **************************************/
    public function generarXML($idfactura)
    {  
        $fecha=Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');        
        
        //PASO 1: Generar Cadena XML
        $a = Factura::generarXML($idfactura);
        
        //PASO 2: Validar XML con XSD
        $m = Factura::validarXML($a,'facturaComputarizadaCompraVenta.xsd');
        
        // if (empty($m)) {
        //     $zip = new ZipArchive();
        //     $zipFileName = 'pruebaXML.zip';
        //     $path = public_path('/siat/facturas');
        //     if (!file_exists($path)) {
        //         mkdir($path, 0777, true);
        //     }
        //     $path = public_path('/siat/facturas/'.$zipFileName);
        //     $abrirZip = $zip->open($path, ZipArchive::CREATE);
        //     if ($abrirZip === TRUE) {
        //         $zip->addFromString('facturas.xml', $a, ZipArchive::FL_OVERWRITE);
        //         $zip->close();
        //     }
            
        // }
        // dd($a);

        if (empty($m)) {
            $gzipFileName = 'pruebaXML.txt.gz';
            $path = public_path('/siat/facturas');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $path = public_path('/siat/facturas/'.$gzipFileName);

            $gzdata = gzencode($a);
            file_put_contents($path,$gzdata);
            $byteArray = base64_encode($gzdata);

            $zp = gzopen($path, "w9");
            gzwrite($zp, $a);
            gzclose($zp);            
        }
        $hashArchivo = hash('sha256', $byteArray);
        // dd("archivo".$hashArchivo);
        // $b = pack("C","Tfafaefdyweyqy brown dog");
        
        // dd($b); 
        
        $factura=Factura::find($idfactura);
        // dd($factura->codigoPuntoVenta);
        $detallefactura=DetalleFactura::where('factura_id','=',$idfactura)->get();
        
        try {            
            $xml = new \XMLWriter();
            $xml->openMemory();
            // $xml->openURI('factura.xml');
            // $xml->openURI($factura->numeroFactura.'.xml');
            // $xml->setIndent(true);
            $xml->startDocument('1.0','UTF-8');
            $xml->startElement('facturaComputarizadaCompraVenta');
            $xml->writeAttribute('xsi:noNamespaceSchemaLocation','facturaComputarizadaCompraVenta.xsd');
            $xml->writeAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');            
                $xml->startElement('cabecera');            
                $xml->writeElement('nitEmisor',$factura->nitEmisor);
                $xml->writeElement('razonSocialEmisor',$factura->razonSocialEmisor);
                $xml->writeElement('municipio',$factura->municipio);
                $xml->writeElement('telefono',$factura->telefono);
                $xml->writeElement('numeroFactura',$factura->numeroFactura);
                $xml->writeElement('cuf',$factura->cuf);
                $xml->writeElement('cufd',$factura->cufd);
                $xml->writeElement('codigoSucursal',$factura->codigoSucursal);
                $xml->writeElement('direccion',$factura->direccion);
                if (is_null($factura->codigoPuntoVenta) || empty($factura->codigoPuntoVenta)) {
                    $xml->startElement('codigoPuntoVenta');
                        $xml->startAttribute('xsi:nil');
                        $xml->text('true');
                        $xml->endAttribute();
                    $xml->endElement();
                } else {
                    $xml->writeElement('codigoPuntoVenta',$factura->codigoPuntoVenta);                    
                }                
                $xml->writeElement('fechaEmision',$factura->fechaEmision);
                $xml->writeElement('nombreRazonSocial',$factura->nombreRazonSocial);
                $xml->writeElement('codigoTipoDocumentoIdentidad',$factura->codigoTipoDocumentoIdentidad);
                $xml->writeElement('numeroDocumento',$factura->numeroDocumento);
                if (is_null($factura->complemento) || empty($factura->complemento)) {
                    $xml->startElement('complemento');
                        $xml->startAttribute('xsi:nil');
                        $xml->text('true');
                        $xml->endAttribute();
                    $xml->endElement();
                } else {
                    $xml->writeElement('complemento',$factura->complemento);                    
                }                                            
                $xml->writeElement('codigoCliente',$factura->codigoCliente);
                $xml->writeElement('codigoMetodoPago',$factura->codigoMetodoPago);
                if (is_null($factura->numeroTarjeta) || empty($factura->numeroTarjeta)) {
                    $xml->startElement('numeroTarjeta');
                        $xml->startAttribute('xsi:nil');
                        $xml->text('true');
                        $xml->endAttribute();
                    $xml->endElement();
                } else {
                    $xml->writeElement('numeroTarjeta',$factura->numeroTarjeta);                    
                }                 
                $xml->writeElement('montoTotal',$factura->montoTotal);
                $xml->writeElement('montoTotalSujetoIva',$factura->montoTotalSujetoIva);
                $xml->writeElement('codigoMoneda',$factura->codigoMoneda);
                $xml->writeElement('tipoCambio',$factura->tipoCambio);
                $xml->writeElement('montoTotalMoneda',$factura->montoTotalMoneda);
                $xml->writeElement('leyenda',$factura->leyenda);
                $xml->writeElement('usuario',$factura->usuario);
                $xml->writeElement('codigoDocumentoSector',$factura->codigoDocumentoSector);
                // if (is_null($factura->montoGiftCard) || empty($factura->montoGiftCard)) {
                //     $xml->startElement('montoGiftCard');
                //         $xml->startAttribute('xsi:nil');
                //         $xml->text('true');
                //         $xml->endAttribute();
                //     $xml->endElement();
                // } else {
                //     $xml->writeElement('montoGiftCard',$factura->montoGiftCard);                    
                // }                 
                // $xml->writeElement('descuentoAdicional',$factura->descuentoAdicional);
                // if (is_null($factura->codigoExcepcion) || empty($factura->codigoExcepcion)) {
                //     $xml->startElement('codigoExcepcion');
                //         $xml->startAttribute('xsi:nil');
                //         $xml->text('true');
                //         $xml->endAttribute();
                //     $xml->endElement();
                // } else {
                //     $xml->writeElement('codigoExcepcion',$factura->codigoExcepcion);                    
                // }                
                // if (is_null($factura->cafc) || empty($factura->cafc)) {
                //     $xml->startElement('cafc');
                //         $xml->startAttribute('xsi:nil');
                //         $xml->text('true');
                //         $xml->endAttribute();
                //     $xml->endElement();
                // } else {
                //     $xml->writeElement('cafc',$factura->cafc);                    
                // }                
                
                
                $xml->endElement();

                $xml->startElement('detalle');
                foreach ($detallefactura as $detalle) {
                    $xml->writeElement('actividadEconomica', $detalle->actividadEconomica);
                    $xml->writeElement('codigoProductoSin', $detalle->codigoProductoSin);
                    $xml->writeElement('codigoProducto', $detalle->codigoProducto);
                    $xml->writeElement('descripcion', $detalle->descripcion);
                    $xml->writeElement('cantidad', $detalle->cantidad);
                    $xml->writeElement('unidadMedida', $detalle->unidadMedida);
                    $xml->writeElement('precioUnitario', $detalle->precioUnitario);
                    $xml->writeElement('montoDescuento', $detalle->montoDescuento);
                    $xml->writeElement('subTotal', $detalle->subTotal);
                    $xml->writeElement('numeroSerie', $detalle->numeroSerie);
                    $xml->writeElement('numeroImei', $detalle->numeroImei);
                    $xml->endElement();
                }                
                $xml->endElement();

            $xml->endElement();
            $xml->endDocument();
            // $xml->fullEndElement();
            // $content = $xml->outputMemory();
            $content = $xml->flush();
              
            return response()->download($factura->numeroFactura.'.xml');
            // return response()->download('factura.xml');

        } catch (\Exception $e) {
            return redirect('/factura')->with('toast_error',$e);            
        }
    }
    public function imprimirFactura($idfactura)
    {
        $perfil_empresarial=Empresa::first();
        dd($perfil_empresarial);
    }

    public function generadorQR($texto)
    {
        $d=new DNS2D();
        $cadena = $d->getBarcodePNGPath($texto, "QRCODE");
        return $cadena;
    }
    
}
