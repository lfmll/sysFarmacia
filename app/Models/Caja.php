<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Caja extends Model
{
    protected $fillable=[
        'monto_apertura','fecha','hora_inicio','hora_fin','estado',
        'b200','b100','b100','b50','b10','m5','m2','m1','m05','m02','m01',
        'gastos','ganancias','total','agencia_id','punto_venta_id','user_id'
    ];
    public function agencia()
    {
        return $this->belongsTo(Agencia::class);
    }

    public function puntoVenta()
    {
        return $this->belongsTo(PuntoVenta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApertura(){
        return $apertura=DB::table('cajas')->whereDate('fecha', DB::raw('CURDATE()'))->count();     
    }    
}   
