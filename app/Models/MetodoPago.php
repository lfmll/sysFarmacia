<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $fillable=[
        'nombre'
    ];

    public function facturas(){
        return $this->hasMany(Factura::class);
    }

    public function ventas(){
        return $this->hasMany(Venta::class);
    }

}
