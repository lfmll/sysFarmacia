<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable=[
        'nombre',
        'stock',
        'stock_minimo',
        'fecha_vencimiento',
        'precio_compra',
        'precio_venta',
        'ganancia',        
        'estado'
    ];

    public function detalle_compras(){
        return $this->hasMany(DetalleCompra::class);
    }
    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class);
    } 
}
