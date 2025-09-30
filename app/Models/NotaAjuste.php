<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaAjuste extends Model
{
    protected $fillable = [
        'tipo',
        'estado',
        'monto',
        'motivo',
        'codigoPuntoVenta',
        'codigoDocumentoSector',
        'usuario',
        'factura_id'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleNotaAjuste::class);
    }
    
    public function cufd()
    {
        return $this->belongsTo(Cufd::class);
    }
}
