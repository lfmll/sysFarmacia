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

    public static function soapRececpcionPaqueteFactura($clienteSoap, $idEvento)
    {
        $msjError = "";
        $fecha = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fecha = $fecha.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);
        $fechaEmision = Factura::deFechaNumero($fecha);
        $evento = Evento::findOrFail($idEvento);
        $facturas = Factura::where('evento_id', $idEvento)->get();
        $empresa = Empresa::first();
        $cuis = Cuis::obtenerCuis();
        $cufd = Cufd::obtenerCufd();
                      
        if (!is_null($cufd)) {
            $dir = public_path('/siat/facturas/evento'.$evento->id);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $xmlFiles = [];
            foreach ($facturas as $factura) {                
                $factura->cufd = $cufd->codigo_cufd;
                $cuf = Factura::generarCUF(
                                    $factura->nitEmisor,
                                    $factura->codigoSucursal,
                                    $fechaEmision,
                                    $empresa->modalidad,
                                    1, // Tipo de Emisión: 1 Online
                                    1, // Tipo de Factura: 1 Documento Credito Fiscal
                                    $factura->codigoDocumentoSector,
                                    $factura->numeroFactura,
                                    0 //POS                
                                );            
                $factura->cuf = $cuf.$cufd->codigo_control;     
                $factura->fechaEmision = $fecha;
                $factura->save();
                $xmlFileName = $factura->id.'.xml';
                $xmlFilePath = $dir.'/'.$xmlFileName;
                file_put_contents($xmlFilePath, Factura::generarXML($factura->id));
                $xmlFiles[] = $xmlFilePath; // Guardar la ruta del archivo XML
            }
            // Crear un archivo TAR
            $tarFileName = 'paquete_evento_'.$evento->id.'.tar';
            $tarFilePath = $dir.'/'.$tarFileName;
            $tar = new \PharData($tarFilePath);
            foreach ($xmlFiles as $xmlFile) {
                $tar->addFile($xmlFile, basename($xmlFile)); // Añadir el XML al archivo TAR
            }
            // Comprimir el TAR a GZIP
            $tar->compress(\Phar::GZ); // Comprimir el TAR a GZIP
            
            $gzPath = $tarFilePath.'.gz';
            $byteArray = file_get_contents($gzPath);
            $hashArchivo = hash('sha256', $byteArray);
            $parametrosPaquetes = array(
                                    'SolicitudServicioRecepcionPaquete' => array(
                                        'codigoAmbiente' => $evento->codigoAmbiente,
                                        'codigoDocumentoSector' => $evento->codigoDocumentoSector,
                                        'codigoEmision' => 2, // Tipo de Emisión: 1 Online 2 Offline  
                                        'codigoModalidad' => $empresa->modalidad,
                                        'codigoPuntoVenta' => $evento->codigoPuntoVenta,
                                        'codigoSistema' => $empresa->codigo_sistema,
                                        'codigoSucursal' => $evento->codigoSucursal,
                                        'cufd' => $cufd->codigo_cufd,
                                        'cuis' => $cuis->codigo_cuis,
                                        'nit' => $empresa->nit,
                                        'tipoFacturaDocumento' => 1, // Tipo de Documento: 1 Factura
                                        'archivo' => $byteArray,
                                        'fechaEnvio' => $fecha,
                                        'hashArchivo' => $hashArchivo,
                                        'cafc' => $evento->cafc,
                                        'cantidadFacturas' => $evento->cantidadFacturas,
                                        'codigoEvento' => $evento->codigoRecepcion
                                    )
                                );
            try { 
                $responsePaqueteFacturas = $clienteSoap->recepcionPaqueteFactura($parametrosPaquetes);
                if ($responsePaqueteFacturas->RespuestaServicioFacturacion->transaccion == true) {
                    $msjValidacion = self::soapValidacionRecepcionPaqueteFactura($clienteSoap, $responsePaqueteFacturas->RespuestaServicioFacturacion->codigoRecepcion, $idEvento);
                    if ($msjValidacion == "") {
                        //Actualizar Facturas
                        foreach ($facturas as $factura) {
                            
                            $factura->estado = 'Validado';
                            $factura->codigoRecepcion = $responsePaqueteFacturas->RespuestaServicioFacturacion->codigoRecepcion;
                            $factura->save();
                        }
                        return $msjError;
                    } else {
                        return $msjError = "Error en la validacion de recepcion de paquete de facturas: " . $msjValidacion;
                    }
                    
                } else {
                    return $msjError = "Error recepcionPaqueteFactura(), transaccion=falso ".$responsePaqueteFacturas->RespuestaServicioFacturacion->mensajesList->descripcion;
                }  
            } catch (\Exception $e) {
                return $msjError = "Error al recibir el paquete de factura: " . $e->getMessage();
            }              
        } else {
            return $msjError = "No se encontró un CUFD activo.";
        }    
    }

    public static function soapValidacionRecepcionPaqueteFactura($clienteSoap, $codigoRecepcionFacturas,$idEvento)
    {
        $empresa = Empresa::first();
        $cuis = Cuis::obtenerCuis();
        $cufd = Cufd::obtenerCufd();
        $evento = Evento::findOrFail($idEvento);
        $msjError = "";  
        if (!is_null($cufd)) {
            $parametrosValidacion = array(
                'SolicitudServicioValidacionRecepcionPaquete' => array(
                    'codigoAmbiente' => $evento->codigoAmbiente, 
                    'codigoDocumentoSector' => $evento->codigoDocumentoSector,
                    'codigoEmision' => 2, // Tipo de Emisión: 1 Online 2 Offline
                    'codigoModalidad' => 2,
                    'codigoPuntoVenta' => $evento->codigoPuntoVenta,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $evento->codigoSucursal,
                    'cufd' => $cufd->codigo_cufd,
                    'cuis' => $cuis->codigo_cuis,
                    'nit' => $empresa->nit,
                    'tipoFacturaDocumento' => 1, // Tipo de Documento: 1 Factura
                    'codigoRecepcion' => $codigoRecepcionFacturas
                )
            );    
            try {                
                $responseValidacion = $clienteSoap->validacionRecepcionPaqueteFactura($parametrosValidacion);            
                if ($responseValidacion->RespuestaServicioFacturacion->transaccion == true) {                  
                    return $msjError;
                } else {
                    return $msjError = "Error validacionRecepcionPaqueteFactura(), Transaccion=falso: ".$responseValidacion->RespuestaServicioFacturacion->mensajesList->descripcion;
                }                
            } catch (\Exception $e) {            
                return $msjError = "Error validacionRecepcionPaqueteFactura(): " . $e->getMessage();
            }  
        } else {
            return $msjError = "No se encontró un CUFD activo, durante la validacion de recepcion de paquetes de factura.";
        }  
    }
}
