<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // \App\Models\User::factory(10)->create();
        $this->call(FormatoSeeder::class);
        $this->call(ViasSeeder::class);
        $this->call(DosisSeeder::class);
        $this->call(AccionSeeder::class);
    }
}
