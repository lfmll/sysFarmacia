<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $fillable=[
        'nombre_comercial',
        'nombre_generico',
        'composicion',
        'indicacion',
        'contraindicacion',
        'stock',
        'stock_minimo',
        'formato_id',
        'laboratorio_id',
        'via_id'
    ];

    public function formato(){
        return $this->hasMany(Formato::class);
    }

    public function laboratorio(){
        return $this->hasMany(Laboratorio::class);
    }
    
    public function via(){
        return $this->hasMany(Via::class);
    }
}
