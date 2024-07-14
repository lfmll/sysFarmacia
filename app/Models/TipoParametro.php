<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoParametro extends Model
{
    protected $fillable=[
        'nombre'
    ];

    public function parametros(){
        return $this->hasMany(Parametro::class);
    }

    public function medicamentos(){
        return $this->hasManyThrough(Medicamento::class, Parametro::class,'tipo_parametro_id','codigo_clasificador','id','codigo_clasificador');
    }
    
}
