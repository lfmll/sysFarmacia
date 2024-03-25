<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{    
    protected $table = 'ajustes';
    protected $fillable=[
        'cuis',
        'fecha_cuis',
        'cuifd',
        'fecha_cuifd',
        'driver', 'host', 'port', 'encryption', 'username', 'password', 'from',
    ];

    // protected $casts = [
    //     'password' => 'encrypted',
    // ];
}
