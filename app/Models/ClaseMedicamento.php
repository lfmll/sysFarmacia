<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClaseMedicamento extends Pivot
{
    protected $table='clases_medicamentos';
    
    protected $fillable=[
        'clase_id','medicamento_id'
    ];
}
