<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre',
        'direccion',
        'telefono',
        'ciudad',
        'municipio',
        'estado',
        'empresa_id'
    ];

    public function puntoventas(){
        return $this->hasMany(PuntoVenta::class);
    }
}
