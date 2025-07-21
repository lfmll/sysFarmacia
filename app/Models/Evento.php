<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function soapRegistrarEvento($clienteSoap, $parametroEvento)
    {
        try {
            $response = $clienteSoap->registroEventoSignificativo($parametroEvento);
            if ($response->RespuestaEventoSignificativo->transaccion == true) {
                return $response->RespuestaEventoSignificativo;
            } else {
                return $response->RespuestaEventoSignificativo->mensajesList;
            }
        } catch (\Exception $e) {
            return ['error' => 'Error al registrar el evento significativo: ' . $e->getMessage()];
        }        
    }
}
