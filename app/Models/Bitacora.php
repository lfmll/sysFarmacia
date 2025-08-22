<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = [
        'accion',
        'descripcion',
        'modulo',
        'fecha_hora',
        'ip',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
