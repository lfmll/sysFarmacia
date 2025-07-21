<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public static function obtenerCuis()
    {
        $fechaActual = Carbon::now('America/La_Paz')->toDatetimeString();
        $cuis =  Cuis::where('estado','A')
                    ->where('fecha_vigencia','>',$fechaActual)
                    ->first();
        return $cuis;
    }
    
    public static function sincroCUIS($clienteCuis, $puntoVenta)
    {
        $agencia = Agencia::where('id', $puntoVenta->agencia_id)->first();
        $empresa = Empresa::where('id', $agencia->empresa_id)->first();
        $msjError = "";
        
        if ($clienteCuis->verificarComunicacion()->RespuestaComunicacion->mensajesList->codigo == "926") {
            $parametrosCUIS = array(
                'SolicitudCuis' => array(
                    'codigoAmbiente' => 2, 
                    'codigoModalidad' => $empresa->modalidad,
                    'codigoPuntoVenta' => $puntoVenta->codigo,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia->codigo,
                    'nit' => $empresa->nit
                )            
            ); 
            $responseCuis = $clienteCuis->cuis($parametrosCUIS);
            $ultimoCuis = Cuis::orderby('created_at','desc')->first();            
            if ($responseCuis->RespuestaCuis->transaccion == true) {
                if (!is_null($ultimoCuis)) {
                    $ultimoCuis->estado = "N";
                    $ultimoCuis->save();
                }
                $cuis = new Cuis;
                $fechaUTC = strtotime($responseCuis->RespuestaCuis->fechaVigencia);
                $fecha = date("Y-m-d H:i:s", $fechaUTC);
                $cuis->fill([
                    'codigo_cuis' => $responseCuis->RespuestaCuis->codigo,
                    'fecha_vigencia' => $fecha,
                    'estado' => 'A',
                    'punto_venta_id' => $puntoVenta->id
                ]);
                $cuis->save();
                return $msjError;
            } else {  
                if ($responseCuis->RespuestaCuis->mensajesList->codigo == "980") {
                    if (is_null($ultimoCuis)) {
                        $cuis = new Cuis;
                        $fechaUTC = strtotime($responseCuis->RespuestaCuis->fechaVigencia);
                        $fecha = date("Y-m-d H:i:s", $fechaUTC);
                        $cuis->fill([
                            'codigo_cuis' => $responseCuis->RespuestaCuis->codigo,
                            'fecha_vigencia' => $fecha,
                            'estado' => 'A',
                            'punto_venta_id' => $puntoVenta->id
                        ]);
                        $cuis->save();
                        return $msjError;
                    } elseif ($responseCuis->RespuestaCuis->codigo != $ultimoCuis->codigo_cuis) {
                        $ultimoCuis->estado = "N";
                        $ultimoCuis->save();
                        $cuis = new Cuis;
                        $fechaUTC = strtotime($responseCuis->RespuestaCuis->fechaVigencia);
                        $fecha = date("Y-m-d H:i:s", $fechaUTC);
                        $cuis->fill([
                            'codigo_cuis' => $responseCuis->RespuestaCuis->codigo,
                            'fecha_vigencia' => $fecha,
                            'estado' => 'A',
                            'punto_venta_id' => $puntoVenta->id
                        ]);
                        $cuis->save();
                        return $msjError;
                    }
                } else {
                    $msjError = $responseCuis->RespuestaCuis->mensajesList->descripcion;
                    return $msjError;
                }                                             
            }            
        } else {
            return $msjError = "Error en la comunicación con el Servicio SIAT";
        }
        
    }
}
