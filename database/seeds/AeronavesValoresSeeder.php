<?php

use Illuminate\Database\Seeder;

class AeronavesValoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aeronaves_valores')->truncate();
        // Tabela para D-EAYV
        DB::table('aeronaves_valores')->insert([
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 1,
                'minutos' => 5,
                'preco' => 10
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 2,
                'minutos' => 10,
                'preco' => 20
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 3,
                'minutos' => 20,
                'preco' => 30
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 4,
                'minutos' => 25,
                'preco' => 40
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 5,
                'minutos' => 30,
                'preco' => 50
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 6,
                'minutos' => 35,
                'preco' => 60
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 7,
                'minutos' => 40,
                'preco' => 70
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 8,
                'minutos' => 50,
                'preco' => 80
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 9,
                'minutos' => 55,
                'preco' => 90
            ],
            [
                'matricula'  => 'D-EAYV',
                'unidade_conta_horas'  => 10,
                'minutos' => 60,
                'preco' => 100
            ],



            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 1,
                'minutos' => 5,
                'preco' => 15
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 2,
                'minutos' => 10,
                'preco' => 30
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 3,
                'minutos' => 20,
                'preco' => 45
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 4,
                'minutos' => 25,
                'preco' => 60
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 5,
                'minutos' => 30,
                'preco' => 75
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 6,
                'minutos' => 35,
                'preco' => 90
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 7,
                'minutos' => 40,
                'preco' => 105
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 8,
                'minutos' => 50,
                'preco' => 120
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 9,
                'minutos' => 55,
                'preco' => 135
            ],
            [
                'matricula'  => 'G-CKIP',
                'unidade_conta_horas'  => 10,
                'minutos' => 60,
                'preco' => 150
            ],



            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 1,
                'minutos' => 5,
                'preco' => 15
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 2,
                'minutos' => 10,
                'preco' => 30
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 3,
                'minutos' => 20,
                'preco' => 40
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 4,
                'minutos' => 25,
                'preco' => 55
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 5,
                'minutos' => 30,
                'preco' => 70
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 6,
                'minutos' => 35,
                'preco' => 85
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 7,
                'minutos' => 40,
                'preco' => 100
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 8,
                'minutos' => 50,
                'preco' => 110
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 9,
                'minutos' => 55,
                'preco' => 125
            ],
            [
                'matricula'  => 'CS-AQN',
                'unidade_conta_horas'  => 10,
                'minutos' => 60,
                'preco' => 140
            ],
        ]);
    }
}
