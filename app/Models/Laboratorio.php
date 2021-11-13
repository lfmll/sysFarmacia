<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    protected $fillable=[
        'nombre','direccion','telefono','procedencia'
    ];

    public function medicamento()
    {
        return $this->hasMany(Medicamento::class);
    }
}
