<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoParametro;

class TipoParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo_parametros = [
            ['nombre' => 'TIPO EMISION'],
            ['nombre' => 'TIPO DOCUMENTO FACTURA'],
            ['nombre' => 'TIPO DOCUMENTO IDENTIDAD'],
            ['nombre' => 'TIPO DOCUMENTO SECTOR'],
            ['nombre' => 'TIPO METODO PAGO'],
            ['nombre' => 'PAIS ORIGEN'],
            ['nombre' => 'EVENTOS SIGNIFICATIVOS'],
            ['nombre' => 'TIPO MONEDA'],
            ['nombre' => 'MOTIVO ANULACION'],
            ['nombre' => 'MENSAJE SERVICIOS'],
            ['nombre' => 'UNIDAD MEDIDA'],
            ['nombre' => 'TIPO PUNTO VENTA'],            
            ['nombre' => 'TIPO HABITACION'],            
        ];

        foreach ($tipo_parametros as $tipo_parametro) {
            TipoParametro::create($tipo_parametro);
        }
    }
}
