<?php

use Illuminate\Database\Seeder;

class AeronavesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aeronaves')->truncate();
        DB::table('aeronaves')->insert([
            [   
                'matricula'  => 'CS-AYV',
                'marca'  => 'Cessna',
                'modelo'  => 'C-172',
                'num_lugares'  => 4,
                'conta_horas'  => 34345,
                'preco_hora'  => 0,
                'created_at'  => '2003-04-12 10:23:45',
                'updated_at'  => '2007-02-01 18:56:22',
                'deleted_at'  => '2007-02-01 19:01:46',
            ],            
            [   
                'matricula'  => 'CS-DCX',
                'marca'  => 'Cessna',
                'modelo'  => 'C-150',
                'num_lugares'  => 2,
                'conta_horas'  => 83955,
                'preco_hora'  => 0,
                'created_at'  => '2002-10-24 11:45:01',
                'updated_at'  => '2009-09-30 15:32:45',
                'deleted_at'  => '2009-09-30 15:51:31',
            ],
            [   
                'matricula'  => 'D-EAYV',
                'marca'  => 'Cessna',
                'modelo'  => 'C-150',
                'num_lugares'  => 2,
                'conta_horas'  => 11200,
                'preco_hora'  => 100,
                'created_at'  => '2014-07-30 09:34:48',
                'updated_at'  => '2018-12-24 20:09:12',
                'deleted_at'  => null,
            ],
            [   
                'matricula'  => 'CS-AQN',
                'marca'  => 'Piper',
                'modelo'  => 'PA18',
                'num_lugares'  => 2,
                'conta_horas'  => 102345,
                'preco_hora'  => 140,
                'created_at'  => '2001-09-21 17:24:42',
                'updated_at'  => '2019-02-12 21:43:21',
                'deleted_at'  => null,
            ],
            [   
                'matricula'  => 'G-CKIP',
                'marca'  => 'Cessna',
                'modelo'  => 'C-172',
                'num_lugares'  => 4,
                'conta_horas'  => 73523,
                'preco_hora'  => 150,
                'created_at'  => '2011-11-03 14:34:44',
                'updated_at'  => '2019-01-30 18:40:23',
                'deleted_at'  => null,
            ],            
        ]);

    }
}
