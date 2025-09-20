<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sincronizacion extends Model
{
    protected $table = 'sincronizaciones';
         
    protected $fillable = [
        'fecha_sincronizada',
        'fecha_local',
        'diferencia_horaria',
        'nit',
        'agencia_id',
        'punto_venta_id',
        'cuis_id'
    ];

    public function agencia(){
        return $this->belongsTo(Agencia::class, 'agencia_id');
    }

    public function puntoVenta(){
        return $this->belongsTo(PuntoVenta::class, 'punto_venta_id');
    }

    public function cuis(){
        return $this->belongsTo(Cuis::class, 'cuis_id');
    }

    public function obtenerUltimaSincronizacion($agencia_id, $punto_venta_id)
    {
        return Sincronizacion::where('agencia_id', $agencia_id)
                            ->where('punto_venta_id', $punto_venta_id)
                            ->orderBy('created_at', 'desc')
                            ->first();
    }

    public static function sincronizacionFechaHora($clienteSoap, $parametrosSincronizacion, $idSincronizacion)
    {
        $response = $clienteSoap->sincronizarFechaHora($parametrosSincronizacion);
                
        if ($response->RespuestaFechaHora->transaccion == true) {
            $sincronizacion = Sincronizacion::find($idSincronizacion);
            $fecha_sincronizada = $response->RespuestaFechaHora->fechaHora;
            $fecha_local = Carbon::now('America/La_Paz');
            
            $sincronizacion->fill([
                'fecha_sincronizada' => $fecha_sincronizada,
                'fecha_local' => $fecha_local,
                'diferencia_horaria' => $fecha_local->diffInSeconds(Carbon::parse($fecha_sincronizada))
            ]);
            $sincronizacion->save();
            return true;
        } else {
            return false;
        }
    }
}
