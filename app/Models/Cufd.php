<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cufd extends Model
{
    protected $fillable=[
        'codigo_cufd',
        'fecha_vigencia',
        'estado',
        'cuis_id'
    ];

    public function cuis(){
        return $this->belongsTo(Cuis::class);
    }

    public static function soapCufd($clienteSoap, $parametrosCUFD, $cuis_id)
    {
        $responseCufd = $clienteSoap->cufd($parametrosCUFD);
        if ($responseCufd->RespuestaCufd->transaccion==true) {
            $lastCufd = Cufd::orderBy('created_at','desc')->first();
            if (!is_null($lastCufd)) {
                $lastCufd->estado = "N";
                $lastCufd->save();
            }
            $cufd = new Cufd;
            $fechaUTC = strtotime($responseCufd->RespuestaCufd->fechaVigencia);
            $fecha = date("Y-m-d H:i:s", $fechaUTC);
            $cufd->fill([
                'codigo_cufd' => $responseCufd->RespuestaCufd->codigo,
                'fecha_vigencia' => $fecha,
                'estado' => 'A',
                'cuis_id' => $cuis_id
            ]);
            $cufd->save();
        }                
        return $responseCufd;
    }
}
