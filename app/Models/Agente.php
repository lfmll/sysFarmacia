<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agente extends Model
{
    protected $fillable=[
        'nombre','telefonos','anotacion','laboratorio_id'
    ];

    public function laboratorio(){
        return $this->belongsTo(Laboratorio::class);
    }
    /* public function compras(){
        return $this->hasMany(Compra::class);
    } */
}
