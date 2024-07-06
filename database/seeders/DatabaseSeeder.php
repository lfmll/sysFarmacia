<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caja;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (empty(Caja::count())) {
            $this->call(AperturaCajaSeeder::class);
        }
        $this->call(FormatoSeeder::class);
        $this->call(ViasSeeder::class);
        $this->call(DosisSeeder::class);
        $this->call(AccionSeeder::class);
        $this->call(TipoParametroSeeder::class);
    }
}
