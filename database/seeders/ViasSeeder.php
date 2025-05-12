<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Via;

class ViasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vias = [
            ['descripcion'=>'Oral'],
            ['descripcion'=>'Sublingual'],
            ['descripcion'=>'Tópica'],
            ['descripcion'=>'Ótica'],
            ['descripcion'=>'Transdérmica'],
            ['descripcion'=>'Rectal'],
            ['descripcion'=>'Parental Intravenosa'],
            ['descripcion'=>'Parental Intramuscular'],
            ['descripcion'=>'Parental Subcutánea'],
            ['descripcion'=>'Vaginal'],
            ['descripcion'=>'Inhalatoria'],
            ['descripcion'=>'Oftalmológica']
        ];
        foreach ($vias as $via) {
            Via::firstOrCreate($via);
        }        
    }
}
