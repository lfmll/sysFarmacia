<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Via extends Model
{
    protected $fillable=[
        'descripcion'
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }
}
