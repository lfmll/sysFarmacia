<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PuntoVenta extends Model
{
    protected $fillable=[
        'codigo',
        'nombre',
        'descripcion',
        'agencia_id',
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

    public function cufd(){
        return $this->hasMany(Cufd::class);
    }

    public function sincronizaciones(){
        return $this->hasMany(Sincronizacion::class);
    }

    public function usuarios():BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_punto_ventas')
                    ->using(UserPuntoVenta::class)
                    ->withPivot('estado', 'fecha_asignacion')
                    ->withTimestamps();
    }   
    
    public static function consultarPuntoVentas($clienteSoap, $puntoVenta)
    {
        $agencia = Agencia::where('id', session('agencia_id'))->first();
        $empresa = Empresa::where('id', $agencia->empresa_id)->first();
        $cuis = Cuis::where('estado', 'A')
                    ->where('punto_venta_id',session('puntoVenta->id'))
                    ->first();
        $msj = "";
        if ($clienteSoap->verificarComunicacion()->return->mensajesList->codigo == "926") 
        {
            $parametrosPVenta = array(
                'SolicitudConsultaPuntoVenta' => array(
                    'codigoAmbiente' => $empresa->ambiente,
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
                    'codigoAmbiente' => $empresa->ambiente,
                    'codigoModalidad' => $empresa->modalidad,
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
                    'estado' => 'A'
                ]);
                $puntoVenta->save();
                $userPuntoVenta = new UserPuntoVenta();
                $userPuntoVenta->fill([
                    'user_id' => Auth::id(),
                    'punto_venta_id' => $puntoVenta->id,
                    'estado' => 'A',
                    'fecha_asignacion' => Carbon::now('America/La_Paz')
                ]);
                $userPuntoVenta->save();
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
                    'codigoAmbiente' => $empresa->ambiente,
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
