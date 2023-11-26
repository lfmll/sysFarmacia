<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable=[
        'nombre',
        'nit',
        'correo',
        'actividad',
        'documento',
        'modalidad',
        'emision',
        'cuis',
        'vigencia_cuis'
    ];
}
