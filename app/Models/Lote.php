<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable=[
        'numero',
        'cantidad',
        'fecha_vencimiento',
        'laboratorio_id',
        'medicamento_id',        
        'precio_compra',
        'precio_venta',
        'ganancia',
        'estado'
    ];

    public function laboratorio(){
        return $this->belongsTo(Laboratorio::class);
    }

    public function medicamento(){
        return $this->belongsTo(Medicamento::class);
    }

    public function detalle_compras(){
        return $this->hasMany(DetalleCompra::class);
    }
    
    public function detalle_ventas(){
        return $this->hasMany(DetalleVenta::class);
    }    
}
