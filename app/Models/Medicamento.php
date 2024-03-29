<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $fillable=[
        'codigo',
        'nombre_comercial',
        'nombre_generico',
        'composicion',
        'indicacion',
        'contraindicacion',
        'observacion',
        'stock',
        'stock_minimo',
        'formato_id',
        'via_id',
        'catalogo_id'
    ];

    public function formato(){
        return $this->belongsTo(Formato::class);
    }

    public function via(){
        return $this->belongsTo(Via::class);
    }

    public function clases(){
        return $this->belongsTo(ClaseMedicamento::class);
    }

    public function medidas(){
        return $this->belongsTo(MedidaMedicamento::class);
    }
    
    public function lotes(){
        return $this->hasMany(Lote::class);
    }

    public function catalogos(){
        return $this->belongsTo(Catalogo::class);
    }
}
