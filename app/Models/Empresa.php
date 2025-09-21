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
        'extension',
        'sistema',
        'codigo_sistema',
        'version',
        'modalidad',    
        'ambiente',
        'estado'        
    ];

    public function agencias(){
        return $this->hasMany(Agencia::class);
    }
}
