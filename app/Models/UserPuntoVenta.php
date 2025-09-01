<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPuntoVenta extends Model
{
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
