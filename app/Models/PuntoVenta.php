<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoVenta extends Model
{
    protected $fillable=[
        'codigo',
        'nombre',
        'agencia_id',
        'user_id',
        'estado'
    ];

    public function agencia(){
        return $this->belongsTo(Agencia::class);
    }

    public function ajustes(){
        return $this->hasMany(Ajuste::class);
    }

    public function cuis(){
        return $this->hasMany(Cuis::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }   
    
    public static function consultarPuntoVentas($clienteSoap, $puntoVenta)
    {
        $agencia = Agencia::where('id', $puntoVenta->agencia_id)->first();
        $empresa = Empresa::where('id', $agencia->empresa_id)->first();
        $cuis = Cuis::where('estado', 'A')
                    ->where('punto_venta_id',$puntoVenta->id)
                    ->first();
        $msj = "";
        if ($clienteSoap->verificarComunicacion()->return->mensajesList->codigo == "926") 
        {
            $parametrosPVenta = array(
                'SolicitudConsultaPuntoVenta' => array(
                    'codigoAmbiente' => 2,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia->codigo,
                    'cuis' => $cuis->codigo_cuis, 
                    'nit' => $empresa->nit
                )
            );
            $responsePuntoVenta = $clienteSoap->consultaPuntoVenta($parametrosPVenta);
            return $msj = $responsePuntoVenta->RespuestaConsultaPuntoVenta->mensajesList->descripcion;
        } else {
            return $msj = "Error en la comunicación con el Servicio SIAT";
        }        
    }
    public static function registrarPuntoVenta($clienteSoap, $request)
    {
        $agencia = Agencia::where('id', $request->agencias)->first();        
        $empresa = Empresa::first();
        $cuis = Cuis::obtenerCuis();
                
        $msj = "";
        if ($clienteSoap->verificarComunicacion()->return->transaccion == true) {
            $parametrosPVenta = array(
                'SolicitudRegistroPuntoVenta' => array(
                    'codigoAmbiente' => 2,
                    'codigoModalidad' => 2,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia->codigo,
                    'codigoTipoPuntoVenta' => $request->tipoPuntoVenta,  //5: Cajero
                    'cuis' => $cuis->codigo_cuis,
                    'descripcion' => "Punto de Venta",
                    'nit' => $empresa->nit,
                    'nombrePuntoVenta' => $request->nombre
                )
            );
            $responsePuntoVenta = $clienteSoap->registroPuntoVenta($parametrosPVenta);
            if ($responsePuntoVenta->RespuestaRegistroPuntoVenta->transaccion == true) {
                $puntoVenta = new PuntoVenta();
                $puntoVenta->fill([
                    'codigo' => $responsePuntoVenta->RespuestaRegistroPuntoVenta->codigoPuntoVenta,
                    'nombre' => $request->nombre,
                    'agencia_id' => $request->agencias,
                    'user_id' => 1,
                    'estado' => 'A'
                ]);
                $puntoVenta->save();
                return $msj;
            } else {
                return $msj = $responsePuntoVenta->RespuestaRegistroPuntoVenta->mensajesList->descripcion;
            }            
        } else {
            return $msj = "Error en la comunicación con el Servicio SIAT";
        }
    }
    public static function cerrarPuntoVenta($clienteSoap, $idPuntoVenta)
    {
        $puntoVenta = PuntoVenta::find($idPuntoVenta);
        $agencia = Agencia::where('id', $puntoVenta->agencia_id)->first();
        $empresa = Empresa::first();
        $cuis = Cuis::obtenerCuis();
        $msj = "";
        if ($clienteSoap->verificarComunicacion()->return->mensajesList->codigo == "926") 
        {
            $parametrosPVenta = array(
                'SolicitudCierrePuntoVenta' => array(
                    'codigoAmbiente' => 2,
                    'codigoPuntoVenta' => $puntoVenta->codigo,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia->codigo,
                    'cuis' => $cuis->codigo_cuis, 
                    'nit' => $empresa->nit                    
                )
            );
            $responsePuntoVenta = $clienteSoap->cierrePuntoVenta($parametrosPVenta);
            if ($responsePuntoVenta->RespuestaCierrePuntoVenta->transaccion == true) {
                $puntoVenta->estado = 'N';
                $puntoVenta->save();
                return $msj;
            } else {
                return $msj = $responsePuntoVenta->RespuestaCierrePuntoVenta->mensajesList->descripcion;
            }
        } else {
            return $msj = "Error en la comunicación con el Servicio SIAT"; 
        }
    }
}
