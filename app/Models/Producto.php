<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable=[
        'catalogo_id',
        'codigo',
        'descripcion',
        'precio_unitario',
        'unidad',                
        'estado'
    ];

    public function lotes(){
        return $this->hasMany(Lote::class);        
    }

    public function catalogo(){
        return $this->belongsTo(Catalogo::class);
    }
    
}
