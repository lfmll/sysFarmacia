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
        return $this->hasOne(Factura::class);
    }
    
    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
