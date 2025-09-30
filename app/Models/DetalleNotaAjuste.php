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
        'descripcion',
        'cantidad',
        'unidadMedida',
        'precioUnitario',
        'montoDescuento',
        'subTotal',
        'numeroSerie',
        'numeroImei',
        'nota_ajuste_id'
    ];
    
    public function notaAjuste()
    {
        return $this->belongsTo(NotaAjuste::class);
    }

    
}
