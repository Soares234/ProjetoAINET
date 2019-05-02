<?php

use Illuminate\Database\Seeder;

class ClassesCertificadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classes_certificados')->truncate();
        DB::table('classes_certificados')->insert([
            [   
                'code'  => 'Class 1',
                'nome'  => 'Class 1 medical certificate'
            ],
            [   
                'code'  => 'Class 2',
                'nome'  => 'Class 2 medical certificate'
            ],
            [   
                'code'  => 'LAPL',
                'nome'  => 'Light Aircraft Pilot Licence Medical'
            ],
        ]);
    }
}
