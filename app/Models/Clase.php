<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $fillable=[
        'nombre','clase'
    ];

    public function medicamento(){
        return $this->belongsToMany(Medicamento::class)->using(ClaseMedicamento::class);
    }
}
