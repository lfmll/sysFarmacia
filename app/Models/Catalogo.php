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
    
    public function producto()
    {
        return $this->hasMany(Producto::class);
    }
}
