<?php

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
        DB::statement("SET foreign_key_checks=0");

        $this->call(TiposLicencasSeeder::class);
        $this->call(ClassesCertificadosSeeder::class);
        $this->call(AerodromosSeeder::class);
        $this->call(AeronavesSeeder::class);
        $this->call(AeronavesValoresSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(AeronavesPilotosSeeder::class);
        $this->call(MovimentosSeeder::class);

        DB::statement("SET foreign_key_checks=1");
    }
}
