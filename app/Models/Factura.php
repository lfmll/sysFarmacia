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
    
}
