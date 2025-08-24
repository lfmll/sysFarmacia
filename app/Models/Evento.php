<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use RecursiveIteratorIterator;
use PharData;

class Evento extends Model
{
    protected $fillable = [
        'codigoAmbiente',
        'codigoSistema',
        'nit',
        'cuis',
        'cufd',
        'codigoSucursal',
        'codigoPuntoVenta',
        'codigoEvento',
        'descripcion',
        'estado',
        'fechaInicioEvento',
        'fechaFinEvento',
        'cufdEvento'
    ];

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public static function soapRegistrarEvento($clienteSoap, $idEvento)
    {
        $empresa = Empresa::first();
        $evento = Evento::findOrFail($idEvento);
        $msjError = "";
        try { 
            $fechaFin = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
            $fechaFin = $fechaFin.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);
            $cuis = Cuis::obtenerCuis();
            $cufd = Cufd::obtenerCufd();
            
            if (!is_null($cufd)) {
                $parametrosEvento = array(
                    'SolicitudEventoSignificativo' => array(
                        'codigoAmbiente' => $evento->codigoAmbiente,
                        'codigoMotivoEvento' => $evento->codigoEvento,
                        'codigoPuntoVenta' => $evento->codigoPuntoVenta,
                        'codigoSistema' => $empresa->codigo_sistema,
                        'codigoSucursal' => $evento->codigoSucursal,
                        'cufd' => $cufd->codigo_cufd,
                        'cufdEvento' => $evento->cufdEvento,
                        'cuis' => $cuis->codigo_cuis,
                        'descripcion' => $evento->descripcion,
                        'fechaHoraFinEvento' => $fechaFin,
                        'fechaHoraInicioEvento' => $evento->fechaInicioEvento,
                        'nit' => $empresa->nit
                    )
                );    
                // dd($parametrosEvento);  
                $responseEvento = $clienteSoap->registroEventoSignificativo($parametrosEvento);            
                if ($responseEvento->RespuestaListaEventos->transaccion == true) {                  
                    $evento->estado = 'Cerrado';
                    $evento->cufd = $cufd->codigo_cufd;
                    $evento->fechaFinEvento = $fechaFin;
                    $evento->codigoRecepcion = $responseEvento->RespuestaListaEventos->codigoRecepcionEventoSignificativo;                    
                    $evento->save();
                    return $msjError;
                } else {
                    return $msjError = $responseEvento->RespuestaListaEventos->mensajesList->descripcion;
                }                
            } else {
                return $msjError = "No se encontró un CUFD activo.";
            }            
        } catch (\Exception $e) {            
            return $msjError = "Error: " . $e->getMessage();
        }        
    }

    public static function soapRececpcionPaqueteFactura($clienteSoap, $parametrosPaquetes)
    {
        $msjError = "";
                
        try {
            $cufd = Cufd::obtenerCufd();
            if (!is_null($cufd)) {
                
                $responsePaqueteFacturas = $clienteSoap->recepcionPaqueteFactura($parametrosPaquetes);
                if ($responsePaqueteFacturas->RespuestaServicioFacturacion->transaccion == true) {
                    return $msjError;
                } else {
                    return $msjError = $responsePaqueteFacturas->RespuestaServicioFacturacion->mensajesList->descripcion;
                }                
            } else {
                return $msjError = "No se encontró un CUFD activo.";
            }

            return $msjError;
        } catch (\Exception $e) {
            return $msjError = "Error al recibir el paquete de factura: " . $e->getMessage();
        }
    }
}
