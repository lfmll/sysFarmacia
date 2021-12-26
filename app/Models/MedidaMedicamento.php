<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


class MedidaMedicamento extends Pivot
{
    protected $table='medidas_medicamentos';
    protected $fillable=[
        'descripcion','dosis_estandar','medida_id','medicamento_id','estado'
    ];

    public function medicamentos(){
        return $this->belongsToMany(Medicamento::class)->using(MedidaMedicamento::class);
    }
}
