<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leyenda extends Model
{
    protected $fillable=[
        'codigo_actividad',
        'descripcion_leyenda',
        'cuis_id',
    ];

    public function cuis(){
        return $this->belongsTo(Cuis::class);
    }

    public static function soapLeyenda($clienteSincronizacion, $parametrosSincronizacion, $cuisId)
    {
        Leyenda::where('cuis_id',$cuisId)->delete();
        $responseLeyenda = $clienteSincronizacion->sincronizarListaLeyendasFactura($parametrosSincronizacion);
        if ($responseLeyenda->RespuestaListaParametricasLeyendas->transaccion == true) {
            $listaLeyendas = $responseLeyenda->RespuestaListaParametricasLeyendas->listaLeyendas;
            
            foreach ($listaLeyendas as $ley) {
                $leyenda = new Leyenda;
                $leyenda->fill([
                    'codigo_actividad' => $ley->codigoActividad,
                    'descripcion_leyenda' => $ley->descripcionLeyenda,
                    'cuis_id' => $cuisId
                ]);
                $leyenda->save();
            }
            return true;
        } else {
            return false;
        }
        
    }
}
