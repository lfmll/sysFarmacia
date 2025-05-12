<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Caja;

class AperturaCajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fecha=Carbon::now('America/La_Paz')->toDateString();
        $hora_inicio = Carbon::now('America/La_Paz')->format('H:i');
        Caja::firstOrCreate([
            'monto_apertura' => 0,
            'fecha' => $fecha,
            'hora_inicio' => $hora_inicio
        ]);
    }
}
