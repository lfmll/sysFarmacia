<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formato extends Model
{
    protected $fillable=[
        'descripcion'
    ];

    public function medicamentos()
    {
        return $this->hasMany(Medicamento::class);
    }
}
