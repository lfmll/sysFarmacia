<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClaseMedicamento extends Pivot
{
    protected $table='clases_medicamentos';
    
    protected $fillable=[
        'medicamento_id', 'clase_id','estado'
    ];

    public function medicamentos(){
        return $this->hasMany(Medicamento::class);
    } 
    
    public function clases(){
        return $this->hasMany(Medicamento::class);
    } 
    
}
