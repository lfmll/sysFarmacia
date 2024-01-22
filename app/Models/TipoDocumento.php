<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable=[
        'nombre'
    ];

    public function factura(){
        return $this->hasMany(Factura::class);
    }
}
