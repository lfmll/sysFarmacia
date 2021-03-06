<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable=[
        'fecha','hora_inicio','hora_fin','monto_apertura',
        'b200','b100','b100','b50','b10','m5','m2','m1','m05','m02','m01',
        'gastos','ganancias','total'
    ];
}
