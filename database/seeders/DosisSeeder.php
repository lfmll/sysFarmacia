<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medida;

class DosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medidas = [
            ['descripcion'=>'Lactantes'],
            ['descripcion'=>'Infantes'],
            ['descripcion'=>'Adultos']
        ];

        foreach ($medidas as $medida) {
            Medida::firstOrCreate($medida);
        } 
    }
}
