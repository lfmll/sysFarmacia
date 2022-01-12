<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable=[
        'fecha_venta','pago_venta','cambio_venta'
    ];
    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class);
    }
}
