<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Writer;

class NotaAjuste extends Model
{
    protected $fillable = [
        'numeroNotaCreditoDebito',
        'tipo',
        'estado',
        'motivo',
        'tipoFactura',
        'codigoDocumentoSector',
        'fechaEmision',
        'montoTotal',
        'cuf',
        'montoTotalDevuelto',
        'montoDescuentoCreditoDebito',
        'montoEfectivoCreditoDebito',
        'codigoExcepcion',
        'leyenda',
        'factura_id',
        'cufd_id',
        'punto_venta_id',
        'user_id',
        'codigoRecepcion'
    ];

    public function factura(){
        return $this->belongsTo(Factura::class);
    }

    public function detalles(){
        return $this->hasMany(DetalleNotaAjuste::class);
    }
    
    public function cufd(){
        return $this->belongsTo(Cufd::class);
    }

    public function puntoVenta(){
        return $this->belongsTo(PuntoVenta::class, 'punto_venta_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function validarXML($xml, $xsd)
    {
        $reader = new \XMLReader();
        $reader->XML($xml);
        $reader->setParserProperty(\XMLReader::VALIDATE, true);
        $reader->setSchema(public_path('siat/'.$xsd));
        \libxml_use_internal_errors(true);
        $msj = [];
        while ($reader->read()) {
            if (!$reader->isValid()) {
                $err = \libxml_get_last_error();
                if ($err && $err instanceof \LibXMLError) {
                    $msj[] = \trim($err->message). 'en linea '.$err->line.' columna '.$err->column;
                }
            }
        }
        return $msj;
    }

    public static function soapRecepcionNotaAjuste($clienteSoap, $parametrosNotaAjuste, $idNotaAjuste)
    {
        $responseFacturacionSOAP = $clienteSoap->recepcionDocumentoAjuste($parametrosNotaAjuste);
        $respuesta = $responseFacturacionSOAP->RespuestaServicioFacturacion;
        if ($respuesta->codigoEstado == 908) 
        {
            //Cambiar Estado, actualizar factura
            $notaAjuste = NotaAjuste::find($idNotaAjuste);
            $notaAjuste->fill([
                'estado' => $respuesta->codigoDescripcion,
                'codigoRecepcion' => $respuesta->codigoRecepcion
            ]);            
            $notaAjuste->save();
            return "VALIDADA";
        } else {
            return $mensaje = $respuesta->mensajesList->descripcion;
        }        
    }

    public static function generarXML($idFactura, $idNotaAjuste)
    {
        $factura = Factura::find($idFactura);
        $detallefactura = DetalleFactura::where('factura_id','=',$idFactura)->get();

        $notaAjuste = NotaAjuste::find($idNotaAjuste);
        $detalleNotaAjuste = DetalleNotaAjuste::where('nota_ajuste_id','=',$idNotaAjuste)->get();

        $puntoVenta = PuntoVenta::find(session('punto_venta_id'));
        $sucursal = Agencia::find(session('agencia_id'));
        $cuis = Cuis::obtenerCuis();
        $cufd = Cufd::obtenerCufd();
        try {            
            $writer = new \XMLWriter();
            $writer->openMemory();
            $writer->setIndent(true);
            $writer->setIndentString('    ');
            $writer->startDocument('1.0','UTF-8');
                $writer->startElement('notaFiscalComputarizadaCreditoDebito');
                    $writer->writeAttribute('xsi:noNamespaceSchemaLocation','notaFiscalComputarizadaCreditoDebito.xsd');
                    $writer->writeAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');            
                    $writer->startElement('cabecera');            
                        $writer->writeElement('nitEmisor',$factura->nitEmisor);
                        $writer->writeElement('razonSocialEmisor',$factura->razonSocialEmisor);
                        $writer->writeElement('municipio',$factura->municipio);
                        $writer->writeElement('telefono',$factura->telefono);
                        $writer->writeElement('numeroNotaCreditoDebito',$notaAjuste->numeroNotaCreditoDebito);
                        $writer->writeElement('cuf',$notaAjuste->cuf);
                        $writer->writeElement('cufd',$cufd->codigo_cufd);
                        $writer->writeElement('codigoSucursal',$sucursal->codigo);
                        $writer->writeElement('direccion',$factura->direccion);
                        if (is_null($puntoVenta->codigo)) {
                            $writer->startElement('codigoPuntoVenta');
                                $writer->startAttribute('xsi:nil');
                                $writer->text('true');
                                $writer->endAttribute();
                            $writer->endElement();
                        } else {
                            $writer->writeElement('codigoPuntoVenta',$puntoVenta->codigo);                    
                        }                
                        $writer->writeElement('fechaEmision',$notaAjuste->fechaEmision);
                        $writer->writeElement('nombreRazonSocial',$factura->nombreRazonSocial);
                        $writer->writeElement('codigoTipoDocumentoIdentidad',$factura->codigoTipoDocumentoIdentidad);
                        $writer->writeElement('numeroDocumento',$factura->numeroDocumento);
                        if (is_null($factura->complemento) || empty($factura->complemento)) {
                            $writer->startElement('complemento');
                                $writer->startAttribute('xsi:nil');
                                $writer->text('true');
                                $writer->endAttribute();
                            $writer->endElement();
                        } else {
                            $writer->writeElement('complemento',$factura->complemento);                    
                        }                                            
                        $writer->writeElement('codigoCliente',$factura->codigoCliente);
                        $writer->writeElement('numeroFactura',$factura->numeroFactura);
                        $writer->writeElement('numeroAutorizacionCuf',$factura->cuf);
                        $writer->writeElement('fechaEmisionFactura',$factura->fechaEmision);
                        $writer->writeElement('montoTotalOriginal',$factura->montoTotal);

                        $writer->writeElement('montoTotalDevuelto',$notaAjuste->montoTotalDevuelto);
                        $writer->writeElement('montoDescuentoCreditoDebito',$notaAjuste->montoDescuentoCreditoDebito);
                        $writer->writeElement('montoEfectivoCreditoDebito',$notaAjuste->montoEfectivoCreditoDebito);
                        if (is_null($notaAjuste->codigoExcepcion) || empty($notaAjuste->codigoExcepcion)) {
                            $writer->startElement('codigoExcepcion');
                                $writer->startAttribute('xsi:nil');
                                $writer->text('true');
                                $writer->endAttribute();
                            $writer->endElement();
                        } else {
                            $writer->writeElement('codigoExcepcion',$notaAjuste->codigoExcepcion);
                        }
                        
                        $writer->writeElement('leyenda',$notaAjuste->leyenda);
                        $writer->writeElement('usuario',$notaAjuste->usuario->name);
                        $writer->writeElement('codigoDocumentoSector',$notaAjuste->codigoDocumentoSector);
                        
                    $writer->endElement();                        
                    foreach ($detalleNotaAjuste as $detalle) {
                        $writer->startElement('detalle');
                        $writer->writeElement('actividadEconomica', $detalle->actividadEconomica);
                        $writer->writeElement('codigoProductoSin', $detalle->codigoProductoSin);
                        $writer->writeElement('codigoProducto', $detalle->codigoProducto);
                        $writer->writeElement('descripcion', $detalle->descripcion);
                        $writer->writeElement('cantidad', $detalle->cantidad);
                        $writer->writeElement('unidadMedida', $detalle->unidadMedida);
                        $writer->writeElement('precioUnitario', $detalle->precioUnitario);
                        $writer->writeElement('montoDescuento', $detalle->montoDescuento);
                        $writer->writeElement('subTotal', $detalle->subTotal);
                        $writer->writeElement('codigoDetalleTransaccion',$detalle->codigoDetalleTransaccion); //1: Transaccion Original, 2: Devuelto
                        $writer->endElement();
                    }                                
                $writer->endElement();
            $writer->endDocument();
            $xml = $writer->flush();                        
            return $xml;

        } catch (\Exception $e) {           
            return $e;
        }
    }

    /**************************************
     * SOAP: Anular Nota Ajuste SIAT
     **************************************/
    public static function soapAnulacionNotaAjuste($clienteSoap, $parametrosAnulacionNotaAjuste, $idNotaAjuste)
    {
        $responseAnulacionSOAP = $clienteSoap->anulacionDocumentoAjuste($parametrosAnulacionNotaAjuste);
        $notaAjuste = NotaAjuste::find($idNotaAjuste);
        $msjError = "";
        if ($responseAnulacionSOAP->RespuestaServicioFacturacion->transaccion == "true") {
            $notaAjuste->fill([
                'estado' => 'ANULADA'
            ]);            
            $notaAjuste->save();
            return $msjError;
        } else {
            return $msjError = $responseAnulacionSOAP->RespuestaServicioFacturacion->mensajesList->descripcion;
        }      
    }
    /**************************************
     * SOAP: Revertir Anulacion Nota Ajuste SIAT
     **************************************/
    public static function soapRevertirAnulacionNotaAjuste($clienteSoap, $parametrosRevertirAnulacionNotaAjuste, $idNotaAjuste)
    {
        $responseRevertirAnulacionSOAP = $clienteSoap->reversionAnulacionDocumentoAjuste($parametrosRevertirAnulacionNotaAjuste);
        $notaAjuste = NotaAjuste::find($idNotaAjuste);
        $msjError = "";
        if ($responseRevertirAnulacionSOAP->RespuestaServicioFacturacion->transaccion == "true") {
            $notaAjuste->fill([
                'estado' => 'VALIDADA'
            ]);            
            $notaAjuste->save();
            return $msjError;
        } else {
            return $msjError = $responseRevertirAnulacionSOAP->RespuestaServicioFacturacion->mensajesList->descripcion;
        }      
    }
}
