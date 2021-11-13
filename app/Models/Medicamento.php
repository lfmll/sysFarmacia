<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $fillable=[
        'nombre_comercial',
        'nombre_generico',
        'composicion',
        'indicacion',
        'contraindicacion',
        'stock',
        'stock_minimo',
        'formato_id',
        'laboratorio_id',
        'via_id'
    ];

    public function formato(){
        return $this->belongsTo(Formato::class);
    }

    public function laboratorio(){
        return $this->belongsTo(Laboratorio::class);
    }

    public function via(){
        return $this->belongsTo(Via::class);
    }

    public function clases(){
        return $this->belongsToMany(Clase::class)->unsing(ClaseMedicamento::class);
    }

    public function medidas(){
        return $this->belongsToMany(Medida::class)->using(MedidaMedicamento::class);
    }
}
