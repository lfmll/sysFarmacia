<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable=[
        'fecha_venta',
        'subtotal',
        'descuento',
        'total',
        'monto_giftcard',
        'monto_pagar',
        'importe_iva',
        'cambio_venta',
        'literal',
        'estado'
    ];
    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class);
    }
    
    public function factura(){
        return $this->belongsTo(Factura::class);
    }

    public function metodo_pago(){
        return $this->belongsTo(MetodoPago::class);
    }
}
