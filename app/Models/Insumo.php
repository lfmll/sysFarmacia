<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable=[
        'codigo','nombre','descripcion','stock','stock_minimo'
    ];

    public function lotes(){
        return $this->hasMany(Lote::class);        
    }
}
