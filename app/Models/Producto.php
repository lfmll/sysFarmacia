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

    public function lotes(){
        return $this->hasMany(Lote::class);        
    }
    
}
