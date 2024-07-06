<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuis extends Model
{
    protected $fillable=[
        'codigo_cuis',
        'fecha_vigencia',
        'estado',
        'punto_venta_id'
    ];

    public function punto_venta(){
        return $this->belongsTo(PuntoVenta::class);
    }

    public function cufds(){
        return $this->hasMany(Cufd::class);
    }

    public function parametros(){
        return $this->hasMany(Parametro::class);
    }
    
    public function codigo(){
        return $this->hasOne(Codigo::class);
    }

    public function leyendas(){
        return $this->hasMany(Leyenda::class);
    }
    
    public function actividad_documentos(){
        return $this->hasMany(ActividadDocumento::class);
    }
    
    public static function soapCuis($clienteSoap, $parametrosCUIS, $punto_venta_id)
    {
        $responseCuis = $clienteSoap->cuis($parametrosCUIS);
        if ($responseCuis->RespuestaCuis->transaccion==true) {
            $lastCuis = Cuis::orderBy('created_at', 'desc')->first();
            if (!is_null($lastCuis)) {
                $lastCuis->estado = "N";
                $lastCuis->save();
            }
            $cuis = new Cuis;
            $fechaUTC = strtotime($responseCuis->RespuestaCuis->fechaVigencia);
            $fecha = date("Y-m-d H:i:s", $fechaUTC);
            $cuis->fill([
                'codigo_cuis' => $responseCuis->RespuestaCuis->codigo,
                'fecha_vigencia' => $fecha,
                'estado' => 'A',
                'punto_venta_id' => $punto_venta_id
            ]);
            $cuis->save();            
        }        
        return $responseCuis;
    }
}
