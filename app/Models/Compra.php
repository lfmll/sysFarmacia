<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable=[
        'comprobante','fecha_compra','agente_id', 'pago_compra', 'cambio_compra','glosa','forma_pago'
    ];
    public function agente(){
        return $this->belongsTo(Agente::class);
    }

    public function detalle_compras(){
        return $this->hasMany(DetalleCompra::class);
    }

    /* public function factura(){
        return $this->hasOne(Factura::class);        
    } */
}
