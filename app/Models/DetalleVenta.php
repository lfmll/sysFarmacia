<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $fillable=[
        'cantidad',
        'precio_venta',
        'lote_id',
        'venta_id'
    ];

    public function lote(){
        return $this->belongsTo(Lote::class);
    }
    public function venta(){
        return $this->belongsTo(Venta::class);
    }
}
