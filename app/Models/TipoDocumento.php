<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable=[
        'nombre',
        'descripcion',
        'tipo_documento'
    ];

    public function factura(){
        return $this->hasMany(Factura::class);
    }
}
