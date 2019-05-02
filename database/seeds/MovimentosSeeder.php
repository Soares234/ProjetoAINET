<?php

use Illuminate\Database\Seeder;

class MovimentosSeeder extends Seeder
{
    private $numvoos_diasuteis = [
        'D-EAYV' => [0,0,0,0,0,0,0,0,0,0,1,1,1,2],
        'G-CKIP' => [0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,2],
        'CS-AQN' => [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,2],
    ];
    private $numvoos_fimsemana = [
        'D-EAYV' => [0,0,0,1,1,1,1,1,2,2,2,2,3,3,4],
        'G-CKIP' => [0,0,0,0,0,1,1,1,1,2,2,2,2,3,3,4],
        'CS-AQN' => [0,0,0,0,0,0,0,0,1,1,1,1,2,2,2,3,3,4],
    ];

    private $duracao_voos = [1,1,1,1,1,1,1,1,1,1,2,2,2,2,2,3,3,4];
    private $num_aterragens = [1,1,1,1,1,1,1,1,1,1,2,2,2,2,2,3,3,3,4,4,5,6,7];

    private $pilotos = [];
    private $instrutores = [];
    private $alunos = [];
    private $aeronaves = [];
    private $aerodromos = [];

    private $start_date=null;
    private $first_id = 1;

    private function initValuesFromConfig()
    {
        $d = Carbon\Carbon::today();
        $totalDias = env('SEED_START_DAYS', 2000);
        $d->subDays($totalDias);
        $this->start_date = $d->copy();
        $this->first_id = env('SEED_ID_PRIMEIRO_MOVIMENTO', 1);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->initValuesFromConfig();
        $faker = \Faker\Factory::create('pt_PT');
        DB::table('movimentos')->truncate();

        DB::statement("ALTER TABLE movimentos AUTO_INCREMENT=" . $this->first_id);

        $this->instrutores= DB::table('users')->where('ativo' , 1)->where('tipo_socio', 'P')->where('tipo_licenca', '!=', 'PU')->where('instrutor', 1)->where('aluno', 0)->pluck('id')->all();
        $this->alunos= DB::table('users')->where('ativo' , 1)->where('tipo_socio', 'P')->where('tipo_licenca', '!=', 'PU')->where('instrutor', 0)->where('aluno', 1)->pluck('id')->all();
        $this->pilotos = [
            'D-EAYV' => array_diff(DB::table('aeronaves_pilotos')->where('matricula' , 'D-EAYV')->pluck('piloto_id')->all(), $this->alunos),
            'G-CKIP' => DB::table('aeronaves_pilotos')->where('matricula' , 'G-CKIP')->pluck('piloto_id')->all(),
            'CS-AQN' => DB::table('aeronaves_pilotos')->where('matricula' , 'CS-AQN')->pluck('piloto_id')->all(),
            ];
        $this->aeronaves = DB::table('aeronaves')->whereNull('deleted_at')->pluck('conta_horas', 'matricula')->all();
        $this->aeronaves_minutos = [
            'D-EAYV' => DB::table('aeronaves_valores')->where('matricula' , 'D-EAYV')->pluck('minutos', 'unidade_conta_horas')->all(),
            'G-CKIP' => DB::table('aeronaves_valores')->where('matricula' , 'G-CKIP')->pluck('minutos', 'unidade_conta_horas')->all(),
            'CS-AQN' => DB::table('aeronaves_valores')->where('matricula' , 'CS-AQN')->pluck('minutos', 'unidade_conta_horas')->all(),
            ];
        $this->aeronaves_minutos['D-EAYV'][0] = 0;
        $this->aeronaves_minutos['G-CKIP'][0] = 0;
        $this->aeronaves_minutos['CS-AQN'][0] = 0;
        $this->aeronaves_preco = [
            'D-EAYV' => DB::table('aeronaves_valores')->where('matricula' , 'D-EAYV')->pluck('preco', 'unidade_conta_horas')->all(),
            'G-CKIP' => DB::table('aeronaves_valores')->where('matricula' , 'G-CKIP')->pluck('preco', 'unidade_conta_horas')->all(),
            'CS-AQN' => DB::table('aeronaves_valores')->where('matricula' , 'CS-AQN')->pluck('preco', 'unidade_conta_horas')->all(),
            ];
        $this->aeronaves_preco['D-EAYV'][0] = 0;
        $this->aeronaves_preco['G-CKIP'][0] = 0;
        $this->aeronaves_preco['CS-AQN'][0] = 0;

        $this->aeronaves_diarios = [
            'D-EAYV' => ['diario' => 1, 'servico' => 435],
            'G-CKIP' => ['diario' => 3, 'servico' => 121],
            'CS-AQN' => ['diario' => 4, 'servico' => 890],
        ];

        $this->aerodromos= DB::table('aerodromos')->whereNull('deleted_at')->pluck('code')->all();

        $today = Carbon\Carbon::today()->toDateTimeString();
        $d = $this->start_date->copy();
        $x = 0;
        while ($d <= $today)
        {
            if ($x == 10) {
                $this->command->info("A criar Movimentos para o dia " . $d->format('Y-m-d'));
                $x= 0;
            }
            $this->criarVoosParaUmDia($faker, $d, 'D-EAYV');
            $this->criarVoosParaUmDia($faker, $d, 'G-CKIP');
            $this->criarVoosParaUmDia($faker, $d, 'CS-AQN');
            $d->addDays(1);
            $x++;
        }

        foreach ($this->aeronaves as $matricula => $conta_horas) {
            DB::table('aeronaves')->where('matricula', $matricula)->update(['conta_horas' => $conta_horas]);
        }
        // Ultimos voos (ultimos 5 dias) ainda não estão confirmados
        $ultimos5dias = Carbon\Carbon::today()->subDays(5)->format('Y-m-d');
        DB::table('movimentos')->where('data', '>=', $ultimos5dias)->update(['confirmado' => 0]);
    }

    private function criarVoosParaUmDia($faker, $data, $matricula){
        $fimSemana = $data->isWeekend();
        $totalVoos = $faker->randomElement($fimSemana ? $this->numvoos_fimsemana[$matricula] : $this->numvoos_diasuteis[$matricula]);
        if ($totalVoos > 0) {
            $duracoes = [];
            for($i=1; $i <= $totalVoos; $i++) {
                $duracoes[$i] = $faker->randomElement($this->duracao_voos);
            }
            $horas[1] = 7;
            $minutos[1] = mt_rand(0,11) * 5;
            if ($totalVoos < 3) {
                $horas[1] = mt_rand(7,14);
            }
            for($i=2; $i <= $totalVoos; $i++) {
                $horas[$i] = $horas[$i-1] + $duracoes[$i-1] + 1;
                $minutos[$i] = mt_rand(0,11) * 5;
            }
            // $duracoes em horas - falta converter para conta-horas ( $duracao * 10 + rand(-5,+5))
            for($i=1; $i <= $totalVoos; $i++) {
                $this->criaUmVoo($faker, $data, $matricula, $horas[$i], $minutos[$i], $duracoes[$i] * 10 + rand(-5,+5) );
            }
        }
    }

    //Nota: $duracao em unidade de conta-horas (não em minutos)
    private function criaUmVoo($faker, $data, $matricula, $hora, $minuto, $duracao)
    {
        $dataDescolagem = Carbon\Carbon::create($data->year, $data->month, $data->day, $hora, $minuto, 0);
        $conta_horas_inicio = $this->aeronaves[$matricula];
        $conta_horas_fim = $conta_horas_inicio + $duracao;
        $this->aeronaves[$matricula] = $conta_horas_fim;
        $duracao_minutos = (60 * intdiv($duracao, 10)) + $this->aeronaves_minutos[$matricula][$duracao % 10];
        $preco_voo = ($this->aeronaves_preco[$matricula][10] * intdiv($duracao, 10)) + $this->aeronaves_preco[$matricula][$duracao % 10];
        $dataAterragem = $dataDescolagem->copy()->addMinutes($duracao_minutos);
        $randNumber = mt_rand(1,20);
        $naturezaVoo = $randNumber == 20 ? 'E' : 'T';
        if ($matricula == 'D-EAYV') {
            $naturezaVoo = $randNumber == 20 ? 'E' : ($randNumber <= 6 ? 'I' : 'T');
        }
        $infoPiloto = [];
        $infoInstrutor = [];
        if ($naturezaVoo == 'I') {
            $infoPiloto = $this->getRandomPilot($faker, $matricula, true, false);
            $infoInstrutor = $this->getRandomPilot($faker, $matricula, false, true);
        } else {
            $infoPiloto = $this->getRandomPilot($faker, $matricula, false, false);
        }
        if ($this->aeronaves_diarios[$matricula]['servico'] == 999) {
            $this->aeronaves_diarios[$matricula]['servico'] = 1;
            $this->aeronaves_diarios[$matricula]['diario'] = $this->aeronaves_diarios[$matricula]['diario'] + 1;
        } else {
            $this->aeronaves_diarios[$matricula]['servico'] = $this->aeronaves_diarios[$matricula]['servico'] + 1;
        }
        $num_aterragens = $faker->randomElement($this->num_aterragens);
        $data_created = $dataAterragem->copy()->addMinutes(mt_rand(20,2500));
        $aerodromo_partida = 'LPJF';
        $aerodromo_chegada = 'LPJF';
        $randNumber = mt_rand(1,10);
        if ($randNumber == 1) {
            $aerodromo_partida = 'LPJF';
            $aerodromo_chegada = $faker->randomElement($this->aerodromos);
        } elseif ($randNumber == 2) {
            $aerodromo_partida = $faker->randomElement($this->aerodromos);
            $aerodromo_chegada = 'LPJF';
        }

        $movimento = [
            'data'                          => $data->format('Y-m-d'),
            'hora_descolagem'               => $dataDescolagem->format('Y-m-d H:i:s'),
            'hora_aterragem'                => $dataAterragem->format('Y-m-d H:i:s'),
            'aeronave'                      => $matricula,
            'num_diario'                    => $this->aeronaves_diarios[$matricula]['diario'],
            'num_servico'                   => $this->aeronaves_diarios[$matricula]['servico'],
            'piloto_id'                     => $infoPiloto['id'],
            'num_licenca_piloto'            => $infoPiloto['num_licenca'],
            'validade_licenca_piloto'       => $infoPiloto['validade_licenca'],
            'tipo_licenca_piloto'           => $infoPiloto['tipo_licenca'],
            'num_certificado_piloto'        => $infoPiloto['num_certificado'],
            'validade_certificado_piloto'   => $infoPiloto['validade_certificado'],
            'classe_certificado_piloto'     => $infoPiloto['classe_certificado'],
            'natureza'                      => $naturezaVoo,
            'aerodromo_partida'             => $aerodromo_partida,
            'aerodromo_chegada'             => $aerodromo_chegada,
            'num_aterragens'                => $num_aterragens,
            'num_descolagens'               => $num_aterragens,
            'num_pessoas'                   => $matricula == 'G-CKIP' ? mt_rand(1,4) : mt_rand(1,2),
            'conta_horas_inicio'            => $conta_horas_inicio,
            'conta_horas_fim'               => $conta_horas_fim,
            'tempo_voo'                     => $duracao_minutos,
            'preco_voo'                     => $preco_voo,
            'modo_pagamento'                => $faker->randomElement(['N','M','T','P']),
            'num_recibo'                    => $faker->randomNumber($nbDigits = 4),
            'confirmado'                    => true,
            'observacoes'                   => mt_rand(1,3) == 2 ? $faker->realText() : null,
            'tipo_instrucao'                => $naturezaVoo == 'I' ? $faker->randomElement(['D','D','D','S']) : null,
            'instrutor_id'                  => $infoInstrutor ? $infoInstrutor['id'] : null,
            'num_licenca_instrutor'         => $infoInstrutor ? $infoInstrutor['num_licenca'] : null,
            'validade_licenca_instrutor'    => $infoInstrutor ? $infoInstrutor['validade_licenca'] : null,
            'tipo_licenca_instrutor'        => $infoInstrutor ? $infoInstrutor['tipo_licenca'] : null,
            'num_certificado_instrutor'     => $infoInstrutor ? $infoInstrutor['num_certificado'] : null,
            'validade_certificado_instrutor'=> $infoInstrutor ? $infoInstrutor['validade_certificado'] : null,
            'classe_certificado_instrutor'  => $infoInstrutor ? $infoInstrutor['classe_certificado'] : null,
            'created_at'                    => $data_created->format('Y-m-d H:i:s'),
            'updated_at'                    => $data_created->format('Y-m-d H:i:s'),
        ];
        DB::table('movimentos')->insert($movimento);
    }

    private function getRandomPilot($faker, $matricula, $aluno, $instrutor)
    {
        $pilots = $aluno ? $this->alunos : ($instrutor ? $this->instrutores : $this->pilotos[$matricula]);
        $id = $faker->randomElement($pilots);
        $userRow = DB::table('users')->where('id' , $id)->first(); //->all();
        return [
            'id'                    => $id,
            'num_licenca'           => $userRow->num_licenca,
            'validade_licenca'      => $userRow->validade_licenca,
            'tipo_licenca'          => $userRow->tipo_licenca,
            'num_certificado'       => $userRow->num_certificado,
            'validade_certificado'  => $userRow->validade_certificado,
            'classe_certificado'    => $userRow->classe_certificado
        ];
    }


}
