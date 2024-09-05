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
}
