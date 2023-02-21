<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Caja extends Model
{
    protected $fillable=[
        'monto_apertura','fecha','hora_inicio','hora_fin',
        'b200','b100','b100','b50','b10','m5','m2','m1','m05','m02','m01',
        'gastos','ganancias','total'
    ];

    public function scopeApertura(){
        return $apertura=DB::table('cajas')->whereDate('fecha', DB::raw('CURDATE()'))->count();     
    }    
}   
