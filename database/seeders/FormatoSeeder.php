<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Formato;

class FormatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formatos = [
            ['descripcion'=>'Polvos'],
            ['descripcion'=>'Granuladodas'],
            ['descripcion'=>'Cápsulas Duras'],
            ['descripcion'=>'Cápsulas Blandas'],
            ['descripcion'=>'Tabletas'],
            ['descripcion'=>'Comprimidos'],
            ['descripcion'=>'Supositorios'],
            ['descripcion'=>'Óvulos'],
            ['descripcion'=>'Pomadas'],
            ['descripcion'=>'Pastas'],
            ['descripcion'=>'Cremas'],
            ['descripcion'=>'Jaleas'],
            ['descripcion'=>'Emplastos'],
            ['descripcion'=>'Inyectables'],
            ['descripcion'=>'Jarabes'],
            ['descripcion'=>'Emulsiones'],
            ['descripcion'=>'Suspensiones'],
            ['descripcion'=>'Colirios'],
            ['descripcion'=>'Inhaladores'],
            ['descripcion'=>'Aerosoles']
        ];

        foreach ($formatos as $formato) {
            Formato::firstOrCreate($formato);
        }                        
    }
}
