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
    public static function registrarPuntoVenta($clienteSoap, $puntoVenta)
    {
        $agencia = Agencia::where('id', $puntoVenta->agencia_id)->first();
        $empresa = Empresa::where('id', $agencia->empresa_id)->first();
        $cuis = Cuis::where('estado', 'A')
                    ->where('punto_venta_id',$puntoVenta->id)
                    ->first();
        if ($clienteSoap->verificarComunicacion()->return->mensajesList->codigo == "926") {
            $parametrosPVenta = array(
                'SolicitudRegistroPuntoVenta' => array(
                    'codigoAmbiente' => 2,
                    'codigoModalidad' => 2,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia->codigo,
                    'codigoTipoPuntoVenta' => "1",
                    'cuis' => $cuis->codigo_cuis,
                    'descripcion' => "Punto de Venta",
                    'nit' => $empresa->nit,
                    'nombrePuntoVenta' => $puntoVenta->nombre
                )
            );
            $responsePuntoVenta = $clienteSoap->registroPuntoVenta($parametrosPVenta);
            if ($responsePuntoVenta->RespuestaRegistroPuntoVenta->transaccion == true) {
                return $msj = $responsePuntoVenta->RespuestaRegistroPuntoVenta->mensajesList->descripcion;    
            } else {
                return $msj = $responsePuntoVenta->RespuestaRegistroPuntoVenta->mensajesList->descripcion;
            }
            
        } else {
            return $msj = "Error en la comunicación con el Servicio SIAT";
        }
    }

}
