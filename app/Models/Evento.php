<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
        $msjError = "";
        try { 
            $evento = Evento::findOrFail($idEvento);
            $fechaFin = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
            $fechaFin = $fechaFin.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);
            $cufd = Cufd::obtenerCufd();
            
            if (!is_null($cufd)) {
                $parametrosEvento = array(
                    'SolicitudEventoSignificativo' => array(
                        'codigoAmbiente' => $evento->codigoAmbiente,
                        'codigoMotivoEvento' => $evento->codigoEvento,
                        'codigoPuntoVenta' => $evento->codigoPuntoVenta,
                        'codigoSistema' => $evento->codigoSistema,
                        'codigoSucursal' => $evento->codigoSucursal,
                        'cufd' => $cufd->codigo_cufd,
                        'cufdEvento' => $evento->cufdEvento,
                        'cuis' => $evento->cuis,
                        'descripcion' => $evento->descripcion,
                        'fechaHoraFinEvento' => $fechaFin,
                        'fechaHoraInicioEvento' => $evento->fechaInicioEvento,
                        'nit' => $evento->nit
                    )
                );      
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
            return $msjError = "Error al registrar el evento significativo: " . $e->getMessage();
        }        
    }

    public static function soapRececpcionPaqueteFactura($clienteSoap, $idEvento)
    {
        $msjError = "";
        $fechaEnvio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fechaEnvio = $fechaEnvio.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);        
        $dir = public_path('/siat/facturas/evento'.$idEvento);
        $tarGzPath = null;
        foreach (glob($dir.'/paquete_*.tar.gz') as $archivo) {
            $tarGzPath = $archivo;
            break; // Solo tomamos el primer archivo encontrado
        }
        if (!$tarGzPath || !file_exists($tarGzPath)) {
            return $msjError = "No se encontró el archivo comprimido del paquete de facturas.";
        }
        $phar = new \PharData(str_replace('.gz', '', $tarGzPath)); // Accede al .tar antes de compresión
        $xmlCount = 0;
        foreach (new RecursiveIteratorIterator($phar) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'xml') {
                $xmlCount++;
            }
        }
        if ($xmlCount === 0) {
            return $msjError = "El paquete no contiene archivos XML válidos.";
        }

        $hashArchivo = hash_file('sha256', $tarGzPath);
        
        try {
            $evento = Evento::findOrFail($idEvento);
            $cufd = Cufd::obtenerCufd();
            if (!is_null($cufd)) {
                $parametrosPaquetes = array(
                    'SolicitudServicioRecepcionPaquete' => array(
                        'codigoAmbiente' => $evento->codigoAmbiente,
                        'codigoDocumentoSector' => $evento->codigoDocumentoSector,
                        'codigoEmision' => 2, // Tipo de Emisión: 1 Online 2 Offline  
                        'codigoModalidad' => 2,
                        'codigoPuntoVenta' => $evento->codigoPuntoVenta,
                        'codigoSistema' => $evento->codigoSistema,
                        'codigoSucursal' => $evento->codigoSucursal,
                        'cufd' => $cufd->codigo_cufd,
                        'cuis' => $evento->cuis,
                        'nit' => $evento->nit,
                        'tipoFacturaDocumento' => 1, // Tipo de Documento: 1 Factura
                        'archivo' => base64_encode(file_get_contents($tarGzPath)),
                        'fechaEnvio' => $fechaEnvio,
                        'hashArchivo' => $hashArchivo,
                        'cafc' => $evento->cafc,
                        'cantidadFacturas' => $evento->cantidadFacturas,
                        'codigoEvento' => $evento->codigoRecepcion
                    )
                );
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
