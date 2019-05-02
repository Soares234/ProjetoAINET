<?php

use Illuminate\Database\Seeder;

class TiposLicencasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_licencas')->truncate();
        DB::table('tipos_licencas')->insert([
            [
                'code'  => 'PU',
                'nome'  => 'Piloto de Ultraleve'
            ],
            [
                'code'  => 'PPL(A)',
                'nome'  => 'Private Pilot License Airplane'
            ],
            [
                'code'  => 'CPL(A)',
                'nome'  => 'Comercial Pilot License Airplane'
            ],
            [
                'code'  => 'ATPL',
                'nome'  => 'Airline Transport Pilot License'
            ],
            [
                'code'  => 'ALUNO-PU',
                'nome'  => 'Aluno - Piloto de Ultraleve'
            ],
            [
                'code'  => 'ALUNO-PPL(A)',
                'nome'  => 'Aluno - Private Pilot License Airplane'
            ],
        ]);
    }


}
