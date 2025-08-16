<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Medicamento extends Model
{
    protected $fillable=[
        'codigo_actividad',
        'codigo_producto',
        'codigo_producto_sin',
        'nombre_comercial',
        'nombre_generico',
        'composicion',
        'indicacion',
        'contraindicacion',
        'observacion',
        'stock',
        'stock_minimo',
        'codigo_clasificador',
        'via_id'
    ];

    // public function formato(){
    //     return $this->belongsTo(Formato::class);
    // }
    public function parametro(){
        return $this->belongsTo(Parametro::class, 'codigo_clasificador');
    }

    public function tipo_parametro(): HasOneThrough
    {
        return $this->hasOneThrough(TipoParametro::class, 
                                    Parametro::class, 
                                    'codigo_clasificador',
                                    'id',
                                    'codigo_clasificador',
                                    'tipo_parametro_id' );
    }

    public function codigo(){
        return $this->belongsTo(Codigo::class);
    }

    public function via(){
        return $this->belongsTo(Via::class);
    }

    public function clases(){
        return $this->belongsTo(ClaseMedicamento::class);
    }

    public function medidas(){
        return $this->belongsTo(MedidaMedicamento::class);
    }
    
    public function lotes(){
        return $this->hasMany(Lote::class);
    }

    public function catalogo(){
        return $this->belongsTo(Catalogo::class);
    }

    public static function generarCodigoMedicamento($i)
    {
        // $atc = Clase::where('id',$claseId[0])->first();
        // $prefijo = substr($atc->nombre,1,3);
        // $c = ClaseMedicamento::where('clase_id',$claseId[0])->count();
        // $codigoMedicamento = $prefijo.str_pad(++$c, 6, '0', STR_PAD_LEFT);
// dd($i);
        $codigoMedicamento = str_pad($i, 5, '0', STR_PAD_LEFT);
        return 'P'.$codigoMedicamento;
    }
}
