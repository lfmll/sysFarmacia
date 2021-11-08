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
        'id_formato',
        'id_laboratorio',
        'id_via'
    ];
}
