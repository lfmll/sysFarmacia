<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaseMedicamento extends Model
{
    public function clase(){
        return $this->belongsTo(Clase::class);
    }
    public function medicamento(){
        return $this->belongsTo(Medicamento::class);
    }
    protected $fillable=[
        'clase_id','medicamento_id'
    ];
}
