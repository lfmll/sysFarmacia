<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    protected $fillable=[
        'codigo_caeb',
        'descripcion',
        'tipo_actividad',
        'cuis_id'
    ];

    public function cuis(){
        return $this->belongsTo(Cuis::class);
    }

    public static function soapActividad($clienteSoap, $parametrosActividad, $cuisId)
    {
        Codigo::where('cuis_id',$cuisId)->delete();
        $responseActividad = $clienteSoap->sincronizarActividades($parametrosActividad);
        if ($responseActividad->RespuestaListaActividades->transaccion == true) {
            $listaActividad = $responseActividad->RespuestaListaActividades->listaActividades;        
            $actividad = new Codigo;
            $actividad->fill([
                'codigo_caeb' => $listaActividad->codigoCaeb,
                'descripcion' => $listaActividad->descripcion,
                'tipo_actividad' => $listaActividad->tipoActividad,
                'cuis_id' => $cuisId
            ]);
            $actividad->save();
            
            return true;
        } else {
            return false;
        }
        
    }
}
