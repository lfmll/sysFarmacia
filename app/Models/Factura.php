<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable=[        
        'nroFactura',
        'nroAutorizacion',
        'fecha',
        'NIT',
        'razonSocial',
        'fechaLimite',
        'codigoControl',
        'total',
        'totalLiteral',
        'eliminado',
        'venta_id'
    ];
}
