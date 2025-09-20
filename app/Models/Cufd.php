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
        'cuis_id',
        'agencia_id',
        'punto_venta_id'
    ];

    public function cuis(){
        return $this->belongsTo(Cuis::class);
    }

    public function agencia(){
        return $this->belongsTo(Agencia::class);
    }

    public function punto_venta(){
        return $this->belongsTo(PuntoVenta::class);
    }

    public static function obtenerCufd()
    {
        $fechaActual = Carbon::now('America/La_Paz')->toDatetimeString();
        $cuis = Cuis::obtenerCuis();
        $cufd = null;
        if (!is_null($cuis)) {
            $cufd =  Cufd::where('estado', 'A')
                    ->where('cuis_id', $cuis->id)
                    ->where('agencia_id', session('agencia_id'))
                    ->where('punto_venta_id', session('punto_venta_id')) 
                    ->where('fecha_vigencia', '>' ,$fechaActual)
                    ->first();
        }
        
        return $cufd;
    }

    public static function sincroCUFD($clienteCufd)
    {
        $empresa = Empresa::first();
        $agencia = Agencia::where('id',session('agencia_id'))->first();
        $puntoVenta = PuntoVenta::where('id',session('punto_venta_id'))->first();
        $cuis = Cuis::obtenerCuis();
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
                    'agencia_id' => $agencia->id,
                    'punto_venta_id' => $puntoVenta->id,
                    'cuis_id' => $cuis->id
                ]);
                $cufd->save();
                return $msjError;
            } else {
                return $msjError = $responseCufd->RespuestaCufd->mensajesList->descripcion;
            }
        } else {
            return $msjError = "Error en la comunicación con el Servicio SIAT";
        }
    }    
}
