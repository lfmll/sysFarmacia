<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPuntoVenta extends Pivot
{
    protected $table = 'user_punto_ventas';
    protected $fillable = [
        'user_id',
        'punto_venta_id',
        'estado',
        'fecha_asignacion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function puntoVenta()
    {
        return $this->belongsTo(PuntoVenta::class);
    }
}
