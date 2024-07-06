<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    use HasFactory;

    protected $fillable=[        
        'codigo',
        'nombre',
        'departamento',
        'municipio',
        'direccion',
        'telefono',
        'estado',
        'empresa_id'
    ];

    public function puntoventas(){
        return $this->hasMany(PuntoVenta::class);
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
}
