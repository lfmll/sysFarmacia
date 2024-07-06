<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoVenta extends Model
{
    protected $fillable=[
        'codigo',
        'nombre',
        'agencia_id',
        'user_id',
        'estado'
    ];

    public function agencia(){
        return $this->belongsTo(Agencia::class);
    }

    public function ajustes(){
        return $this->hasMany(Ajuste::class);
    }

    public function cuis(){
        return $this->hasMany(Cuis::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }    

}
