<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoVenta extends Model
{
    protected $fillable=[
        'descripcion',
        'agencia_id',
        'user_id',
        'estado'
    ];

    public function agencia(){
        return $this->belongsTo(Agencia::class);
    }

}
