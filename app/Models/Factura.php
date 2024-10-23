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
        // return $cadena;

    }

    public static function calculaDigitoMod11(string $cadena, int $numDig, int $limMult, bool $sw_10){
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
     * Generar XML
     **************************************/
    public static function generarXML($id)
    {
        $factura=Factura::find($id);
        $detallefactura=DetalleFactura::where('factura_id','=',$id)->get();
        
        try {            
            $xml = new \XMLWriter();
            $xml->openMemory();
            // $xml->openURI('factura.xml');
            // $xml->setIndent(true);
            // $xml->startDocument('1.0','UTF-8');
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
            // $content = $xml->outputMemory();
            // ob_end_clean();
            // ob_start();
            $xml->flush();                        
            return $xml;

        } catch (\Exception $e) {
            // return redirect('/factura')->with('toast_error',$e);            
            return $e;
        }
    }
}
