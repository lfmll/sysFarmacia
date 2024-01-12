<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre'
    ];
    
    public function medicamento()
    {
        return $this->hasMany(Medicamento::class);
    }

}
