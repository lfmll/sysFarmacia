<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable=[
        'comprobante','fecha_venta','pago_venta','cambio_venta','glosa','forma_pago'
    ];
    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class);
    }
}
