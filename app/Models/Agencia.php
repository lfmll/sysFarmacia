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
    
    public function cuis(){
        return $this->hasMany(Cuis::class);
    }
    
    public function cufd(){
        return $this->hasMany(Cufd::class);
    }
    
    public function puntosVenta(){
        return $this->hasMany(PuntoVenta::class);
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    
    public function sincronizaciones(){
        return $this->hasMany(Sincronizacion::class);
    }

}
