<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleNotaAjuste extends Model
{
    protected $fillable = [
        'actividadEconomica',
        'codigoProductoSin',
        'codigoProducto',        
        'cantidad',
        'descripcion',
        'unidadMedida',
        'precioUnitario',
        'montoDescuento',
        'subTotal',
        'numeroSerie',
        'numeroImei',
        'codigoDetalleTransaccion', //1: Transaccion Original, 2: Devuelto
        'nota_ajuste_id'
    ];
    
    public function notaAjuste()
    {
        return $this->belongsTo(NotaAjuste::class);
    }

    
}
