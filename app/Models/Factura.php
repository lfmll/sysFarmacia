<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable=[                
            'nitEmisor', 
            'razonSocialEmisor',
            'municipio',
            'telefono',
            'numeroFactura',
            'cuf',
            'cufd',
            'codigoSucursal',
            'direccion',
            'codigoPuntoVenta',
            'fechaEmision',
            'nombreRazonSocial',
            'codigoTipoDocumentoIdentidad',
            'numeroDocumento',
            'complemento',
            'codigoCliente',
            'codigoMetodoPago',
            'numeroTarjeta',
            'montoTotal',
            'montoTotalSujetoIva',
            'codigoMoneda',
            'tipoCambio',
            'montoTotalMoneda',
            'montoGiftCard',
            'descuentoAdicional',
            'codigoExcepcion',
            'cafc',
            'leyenda',
            'usuario',
            'codigoDocumentoSector',
            'estado',
            'codigoRecepcion',
            'venta_id'
    ];

    public function venta(){
        return $this->hasOne(Venta::class);
    }

    public function detalles(){
        return $this->hasMany(DetalleFactura::class);
    }

    public function metodoPago(){
        return $this->belongsTo(MetodoPago::class);
    }

    public function tipoDocumento(){
        return $this->belongsTo(TipoDocumento::class);
    }
    
    /**************************************
     * Generar CUF
     **************************************/
    public static function generarCUF($nit, $sucursal, $fecha, $modalidad, $tipo_emision, $tipo_factura, $tipo_documento_sector, $nro_factura, $pos){
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
        $cadena .= self::calculaDigitoMod11($cadena, 1, 9, false);
        
        /**
         * PASO 4 Aplica base16
         */
        $baseH = self::base16($cadena);
        
        return $baseH;        
    }

    public static function calculaDigitoMod11(string $cadena, int $numDig, int $limMult, bool $sw_10){
        if (!$sw_10) $numDig = 1;
        
        for ($n=1; $n <= $numDig; $n++) { 
            $suma = 0;
            $mult = 2;
            for ($i=strlen($cadena)-1; $i >= 0; $i--) { 
                $suma += ($mult * substr($cadena, $i, 1));
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

    public static function base16($nro, $touppercase = true) {
        $hexvalues = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $hexval = '';
        while ($nro != '0') {
            $hexval = $hexvalues[bcmod($nro, '16')].$hexval;
            $nro = bcdiv($nro, '16', 0);
        }
        return ($touppercase) ? strtoupper($hexval):$hexval;
    }

    /**************************************
     * Generar Cadena XML
     **************************************/
    public static function generarXML($id)
    {
        $factura=Factura::find($id);
        $detallefactura=DetalleFactura::where('factura_id','=',$id)->get();
        
        try {            
            $writer = new \XMLWriter();
            $writer->openMemory();
            $writer->startDocument('1.0','UTF-8');
            $writer->startElement('facturaComputarizadaCompraVenta');
            $writer->writeAttribute('xsi:noNamespaceSchemaLocation','facturaComputarizadaCompraVenta.xsd');
            $writer->writeAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');            
                $writer->startElement('cabecera');            
                $writer->writeElement('nitEmisor',$factura->nitEmisor);
                $writer->writeElement('razonSocialEmisor',$factura->razonSocialEmisor);
                $writer->writeElement('municipio',$factura->municipio);
                $writer->writeElement('telefono',$factura->telefono);
                $writer->writeElement('numeroFactura',$factura->numeroFactura);
                $writer->writeElement('cuf',$factura->cuf);
                $writer->writeElement('cufd',$factura->cufd);
                $writer->writeElement('codigoSucursal',$factura->codigoSucursal);
                $writer->writeElement('direccion',$factura->direccion);
                if (is_null($factura->codigoPuntoVenta)) {
                    $writer->startElement('codigoPuntoVenta');
                        $writer->startAttribute('xsi:nil');
                        $writer->text('true');
                        $writer->endAttribute();
                    $writer->endElement();
                } else {
                    $writer->writeElement('codigoPuntoVenta',$factura->codigoPuntoVenta);                    
                }                
                $writer->writeElement('fechaEmision',$factura->fechaEmision);
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
                $writer->writeElement('codigoMetodoPago',$factura->codigoMetodoPago);
                if (is_null($factura->numeroTarjeta) || empty($factura->numeroTarjeta)) {
                    $writer->startElement('numeroTarjeta');
                        $writer->startAttribute('xsi:nil');
                        $writer->text('true');
                        $writer->endAttribute();
                    $writer->endElement();
                } else {
                    $writer->writeElement('numeroTarjeta',$factura->numeroTarjeta);                    
                }                 
                $writer->writeElement('montoTotal',$factura->montoTotal);
                $writer->writeElement('montoTotalSujetoIva',$factura->montoTotalSujetoIva);
                $writer->writeElement('codigoMoneda',$factura->codigoMoneda);
                $writer->writeElement('tipoCambio',$factura->tipoCambio);
                $writer->writeElement('montoTotalMoneda',$factura->montoTotalMoneda);
                if (is_null($factura->montoGiftCard) || empty($factura->montoGiftCard)) {
                    $writer->startElement('montoGiftCard');
                        $writer->startAttribute('xsi:nil');
                        $writer->text('true');
                        $writer->endAttribute();
                    $writer->endElement();
                } else {
                    $writer->writeElement('montoGiftCard',$factura->montoGiftCard);                    
                }
                $writer->writeElement('descuentoAdicional',$factura->descuentoAdicional);
                if (is_null($factura->codigoExcepcion) || empty($factura->codigoExcepcion)) {
                    $writer->startElement('codigoExcepcion');
                        $writer->startAttribute('xsi:nil');
                        $writer->text('true');
                        $writer->endAttribute();
                    $writer->endElement();
                } else {
                    $writer->writeElement('codigoExcepcion',$factura->codigoExcepcion);                    
                } 
                if (is_null($factura->cafc) || empty($factura->cafc)) {
                    $writer->startElement('cafc');
                        $writer->startAttribute('xsi:nil');
                        $writer->text('true');
                        $writer->endAttribute();
                    $writer->endElement();
                } else {
                    $writer->writeElement('cafc',$factura->cafc);                    
                }
                $writer->writeElement('leyenda',$factura->leyenda);
                $writer->writeElement('usuario',$factura->usuario);
                $writer->writeElement('codigoDocumentoSector',$factura->codigoDocumentoSector);
                   
                $writer->endElement();

                $writer->startElement('detalle');
                foreach ($detallefactura as $detalle) {
                    $writer->writeElement('actividadEconomica', $detalle->actividadEconomica);
                    $writer->writeElement('codigoProductoSin', $detalle->codigoProductoSin);
                    $writer->writeElement('codigoProducto', $detalle->codigoProducto);
                    $writer->writeElement('descripcion', $detalle->descripcion);
                    $writer->writeElement('cantidad', $detalle->cantidad);
                    $writer->writeElement('unidadMedida', $detalle->unidadMedida);
                    $writer->writeElement('precioUnitario', $detalle->precioUnitario);
                    $writer->writeElement('montoDescuento', $detalle->montoDescuento);
                    $writer->writeElement('subTotal', $detalle->subTotal);
                    $writer->writeElement('numeroSerie', $detalle->numeroSerie);
                    $writer->writeElement('numeroImei', $detalle->numeroImei);
                    $writer->endElement();
                }                
                $writer->endElement();

            $writer->endElement();
            $writer->endDocument();
            $xml = $writer->flush();                        
            return $xml;

        } catch (\Exception $e) {           
            return $e;
        }
    }
    /**************************************
     * Validar XML con XSD
     **************************************/
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
                if ($err && $err instanceof \libXMLError) {
                    $msj[] = \trim($err->message). 'en linea '.$err->line;
                }
            }
        }
        return $msj;
    }
    /**************************************
     * SOAP: Recepcion Factura SIAT
     **************************************/
    public static function soapRecepcionFactura($clienteSoap, $parametrosFactura, $idfactura)
    {
        $responseFacturacionSOAP = $clienteSoap->recepcionFactura($parametrosFactura);
        $respuesta = $responseFacturacionSOAP->RespuestaServicioFacturacion;        
        if ($respuesta->codigoEstado == 908) 
        {
            //Cambiar Estado, actualizar factura
            $factura = Factura::find($idfactura);
            $factura->fill([
                'estado' => $respuesta->codigoDescripcion,
                'codigoRecepcion' => $respuesta->codigoRecepcion
            ]);            
            $factura->save();
            return "VALIDADA";
        } else {
            return $mensaje = $respuesta->mensajesList->descripcion;
        }        
    }
    /**************************************
     * SOAP: Anular Factura SIAT
     **************************************/
    public static function soapAnularFactura($clienteSoap, $parametrosFactura, $idfactura)
    {
        $responseFacturacionSOAP = $clienteSoap->anulacionFactura($parametrosFactura);
        $factura = Factura::find($idfactura);
        $msjError = "";
        if ($responseFacturacionSOAP->RespuestaServicioFacturacion->transaccion == "true") {
            $factura->fill([
                'estado'=> "ANULADA"
            ]);            
            $factura->save();
            return $msjError;
        } else {
            return $msjError = $responseFacturacionSOAP->RespuestaServicioFacturacion->mensajesList->descripcion;
        }
    }
    /**************************************
     * SOAP: Revertir Anulacion Factura SIAT
     **************************************/
    public static function soapRevertirAnulacionFactura($clienteSoap, $parametrosFactura, $idfactura)
    {
        $responseFacturacionSOAP = $clienteSoap->reversionAnulacionFactura($parametrosFactura);
        $factura = Factura::find($idfactura);
        $msjError = "";
        if ($responseFacturacionSOAP->RespuestaServicioFacturacion->transaccion == "ture") {
            $factura->fill([
                'estado' => "VALIDADA"
            ]);
            $factura->save();
            return $msjError;
        } else {
            return $msjError = $responseFacturacionSOAP->RespuestaServicioFacturacion->mensajesList->descripcion;
        }
    }
    /**************************************
     * Utilitarios: De Fecha a Numeros
     **************************************/
    public static function deFechaNumero($fecha)
    {
        $nro = "";
        for ($i=0; $i < strlen($fecha); $i++) { 
            if (is_numeric($fecha[$i])) {
                $nro = $nro.$fecha[$i];
            }
        }
        return $nro;
    }    
}
