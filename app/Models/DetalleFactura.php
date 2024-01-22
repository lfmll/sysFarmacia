<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $fillable=[
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
        'numeroImei'
    ];
    
    public function factura(){
        return $this->belongsTo(Factura::class);
    }
}


