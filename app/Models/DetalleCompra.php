<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $fillable=[
        'cantidad',
        'precio_compra',
        'lote_id',
        'compra_id'
    ];

    public function lote(){
        return $this->belongsTo(Lote::class);
    }
    public function compra(){
        return $this->belongsTo(Compra::class);
    }
}
