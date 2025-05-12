<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadDocumento extends Model
{
    protected $fillable=[
        'codigo_actividad',
        'codigo_documento_sector',
        'tipo_documento_sector',
        'cuis_id'
    ];

    public function cuis(){
        return $this->belongsTo(Cuis::class);
    }

    public static function soapActividadDocumento($clienteSincronizacion, $parametrosSincronizacion, $cuisId)
    {        
        $responseActividadDocumento = $clienteSincronizacion->sincronizarListaActividadesDocumentoSector($parametrosSincronizacion);
        if ($responseActividadDocumento->RespuestaListaActividadesDocumentoSector->transaccion == true) {
            ActividadDocumento::truncate();
            $listaActividadesDocumentos = $responseActividadDocumento->RespuestaListaActividadesDocumentoSector->listaActividadesDocumentoSector;
            foreach ($listaActividadesDocumentos as $lad) {
                $lista_actividad_documento = new ActividadDocumento;
                $lista_actividad_documento->fill([
                    'codigo_actividad' => $lad->codigoActividad,
                    'codigo_documento_sector' => $lad->codigoDocumentoSector,
                    'tipo_documento_sector' => $lad->tipoDocumentoSector,
                    'cuis_id' => $cuisId
                ]);
                $lista_actividad_documento->save();
            }
            return true;
        } else {
            return false;
        }
    }
}
