<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cufd extends Model
{
    protected $fillable=[
        'codigo_cufd',
        'codigo_control',
        'direccion',
        'fecha_vigencia',
        'estado',
        'cuis_id'
    ];

    public function cuis(){
        return $this->belongsTo(Cuis::class);
    }

    public static function sincroCUFD($clienteCufd, $puntoVenta)
    {
        $agencia = Agencia::where('id',$puntoVenta->agencia_id)->first();
        $empresa = Empresa::where('id',$agencia->empresa_id)->first();
        $cuis = Cuis::where('estado', 'A')
                    ->where('punto_venta_id',$puntoVenta->id)
                    ->first();
        $msjError = "";
        if ($clienteCufd->verificarComunicacion()->RespuestaComunicacion->mensajesList->codigo == "926") 
        {
            $parametrosCUFD = array(
                'SolicitudCufd' => array(
                    'codigoAmbiente' => 2, 
                    'codigoModalidad' => $empresa->modalidad,
                    'codigoPuntoVenta' => $puntoVenta->codigo,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia->codigo,
                    'cuis' => $cuis->codigo_cuis,
                    'nit' => $empresa->nit
                )
            );
            $responseCufd = $clienteCufd->cufd($parametrosCUFD);
            if ($responseCufd->RespuestaCufd->transaccion==true) {
                $ultimoCufd = Cufd::orderBy('created_at','desc')->first();
                if (!is_null($ultimoCufd)) {
                    $ultimoCufd->estado = "N";
                    $ultimoCufd->save();
                }
                $cufd = new Cufd;
                $fechaUTC = strtotime($responseCufd->RespuestaCufd->fechaVigencia);
                $fecha = date("Y-m-d H:i:s", $fechaUTC);
                $cufd->fill([
                    'codigo_cufd' => $responseCufd->RespuestaCufd->codigo,
                    'codigo_control' => $responseCufd->RespuestaCufd->codigoControl,
                    'direccion' => $responseCufd->RespuestaCufd->direccion,
                    'fecha_vigencia' => $fecha,
                    'estado' => 'A',
                    'cuis_id' => $cuis->id
                ]);
                $cufd->save();
                return $msjError;
            } else {
                return $msjError = $responseCufd->$RespuestaCufd->$mensajesList->$descripcion;
            }
        } else {
            return $msjError = "Error en la comunicación con el Servicio SIAT";
        }
    }

    public static function obtenerCufd()
    {
        $fechaActual = Carbon::now('America/La_Paz')->toDatetimeString();
        $cufd =  Cufd::where('estado','A')
                    ->where('fecha_vigencia','>',$fechaActual)
                    ->first();
        return $cufd;
    }
}
