<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    use HasFactory;

    protected $fillable=[
        'codigo_actividad',
        'codigo_producto',
        'descripcion_producto',
        'cuis_id'
    ];
    
    public function medicamentos(){
        return $this->hasMany(Medicamento::class);
    }
    
    public function cuis()
    {
        return $this->belongsTo(Cuis::class);
    }

    public static function soapCatalogo($clienteSincronizacion, $parametrosSincronizacion, $cuisId)
    {        
        $responseCatalogo = $clienteSincronizacion->sincronizarListaProductosServicios($parametrosSincronizacion);
        if ($responseCatalogo->RespuestaListaProductos->transaccion == true) {
            Catalogo::truncate();
            $listaCatalogos = $responseCatalogo->RespuestaListaProductos->listaCodigos;
            foreach ($listaCatalogos as $lcat) {
                $catalogo = new Catalogo;
                $catalogo->fill([
                    'codigo_actividad' => $lcat->codigoActividad,
                    'codigo_producto' => $lcat->codigoProducto,
                    'descripcion_producto' => $lcat->descripcionProducto,
                    'cuis_id' => $cuisId
                ]);
                $catalogo->save();
            }
            return true;
        } else {
            return false;
        }

    }

}
