<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable=[
        'tipo_documento',
        'numero_documento',
        'complemento',
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'estado'
    ];

    public function ventas(){
        return $this->hasMany(Venta::class);
    }
}
