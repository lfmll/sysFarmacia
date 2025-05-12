<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $fillable=[
        'codigo_clasificador',
        'descripcion',
        'tipo_parametro_id',
        'cuis_id'
    ];

    public function cuis(){
        return $this->belongsTo(Cuis::class);
    }

    public function medicamentos(){
        return $this->hasMany(Medicamento::class, 'codigo_clasificador');
    }

    public function tipo_parametro(){
        return $this->belongsTo(TipoParametro::class);
    }
    
    public static function sincronizarParametro($clienteSincronizacion, $parametrosSincronizacion, $cuisId)
    {
        Parametro::truncate();
        //Sincronizar Eventos Significativos
        $responseEventos = $clienteSincronizacion->sincronizarParametricaEventosSignificativos($parametrosSincronizacion);
        $listaEventos = $responseEventos->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','EVENTOS SIGNIFICATIVOS')->first();
        foreach ($listaEventos as $ev) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $ev->codigoClasificador,
                'descripcion' => $ev->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        //Sincronizar Motivo Anulacion
        $responseMotivo = $clienteSincronizacion->sincronizarParametricaMotivoAnulacion($parametrosSincronizacion);
        $listaMotivos = $responseMotivo->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','MOTIVO ANULACION')->first();
        foreach ($listaMotivos as $mot) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $mot->codigoClasificador,
                'descripcion' => $mot->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        //Sincronizar Tipo Documento
        $responseDocumento = $clienteSincronizacion->sincronizarParametricaTipoDocumentoIdentidad($parametrosSincronizacion);
        $listaDocumento = $responseDocumento->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','TIPO DOCUMENTO IDENTIDAD')->first();
        foreach ($listaDocumento as $doc) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $doc->codigoClasificador,
                'descripcion' => $doc->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        //Sincronizar Sectores (Tipo Documento Sector)
        $responseSector = $clienteSincronizacion->sincronizarParametricaTipoDocumentoSector($parametrosSincronizacion);
        $listaSector = $responseSector->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','TIPO DOCUMENTO SECTOR')->first();
        foreach ($listaSector as $tsec) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $tsec->codigoClasificador,
                'descripcion' => $tsec->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        //Sincronizar Tipo Emision
        $responseTipoEmision = $clienteSincronizacion->sincronizarParametricaTipoEmision($parametrosSincronizacion);
        $listatipoEmision = $responseTipoEmision->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','TIPO EMISION')->first();
        foreach ($listatipoEmision as $te) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $te->codigoClasificador,
                'descripcion' => $te->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        //Sincronizar Tipo Factura
        $responseTipoFactura = $clienteSincronizacion->sincronizarParametricaTiposFactura($parametrosSincronizacion);
        $listaTipoFactura = $responseTipoFactura->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','TIPO DOCUMENTO FACTURA')->first();
        foreach ($listaTipoFactura as $tfac) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $tfac->codigoClasificador,
                'descripcion' => $tfac->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        //Sincronizar Lista Mensaje de Servicios
        $responseMensajeServicio = $clienteSincronizacion->sincronizarListaMensajesServicios($parametrosSincronizacion);
        $listaMensajeServicio = $responseMensajeServicio->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','MENSAJE SERVICIOS')->first();
        foreach ($listaMensajeServicio as $lmsj) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $lmsj->codigoClasificador,
                'descripcion' => $lmsj->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }

        //Sincronizar Unidad Medida
        $responseUnidadMedida = $clienteSincronizacion->sincronizarParametricaUnidadMedida($parametrosSincronizacion);
        $listaUnidadMedida = $responseUnidadMedida->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','UNIDAD MEDIDA')->first();
        foreach ($listaUnidadMedida as $tum) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $tum->codigoClasificador,
                'descripcion' => $tum->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        
        //Sincronizar Metodo de Pago
        $responseMetodoPago = $clienteSincronizacion->sincronizarParametricaTipoMetodoPago($parametrosSincronizacion);
        $listaMetodoPago = $responseMetodoPago->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','TIPO METODO PAGO')->first();
        foreach ($listaMetodoPago as $tmp) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $tmp->codigoClasificador,
                'descripcion' => $tmp->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }

        //Sincronizar Pais Origen
        $responsePais = $clienteSincronizacion->sincronizarParametricaPaisOrigen($parametrosSincronizacion);
        $listaPais = $responsePais->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','PAIS ORIGEN')->first();
        foreach ($listaPais as $pais) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $pais->codigoClasificador,
                'descripcion' => $pais->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }

        //Sincronizar Moneda
        $responseMoneda = $clienteSincronizacion->sincronizarParametricaTipoMoneda($parametrosSincronizacion);
        $listaMoneda = $responseMoneda->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','TIPO MONEDA')->first();
        foreach ($listaMoneda as $moneda) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $moneda->codigoClasificador,
                'descripcion' => $moneda->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }

        //Sincronizar Punto Venta
        $responseTipoPuntoVenta = $clienteSincronizacion->sincronizarParametricaTipoPuntoVenta($parametrosSincronizacion);
        $listaTipoPuntoVenta = $responseTipoPuntoVenta->RespuestaListaParametricas->listaCodigos;
        $tipoParametro = TipoParametro::where('nombre','TIPO PUNTO VENTA')->first();
        foreach ($listaTipoPuntoVenta as $tipopv) {
            $parametro = new Parametro;
            $parametro->fill([
                'codigo_clasificador' => $tipopv->codigoClasificador,
                'descripcion' => $tipopv->descripcion,
                'tipo_parametro_id' => $tipoParametro->id,
                'cuis_id' => $cuisId
            ]);
            $parametro->save();
        }
        return true;
    }
    
}


