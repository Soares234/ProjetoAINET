<?php

use Illuminate\Database\Seeder;

class AeronavesPilotosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aeronaves_pilotos')->truncate();
        $instrutores= DB::table('users')->where('ativo' , 1)->where('tipo_socio', 'P')->where('tipo_licenca', '!=', 'PU')->where('instrutor', 1)->where('aluno', 0)->pluck('id')->all();
        $normal= DB::table('users')->where('ativo' , 1)->where('tipo_socio', 'P')->where('tipo_licenca', '!=', 'PU')->where('instrutor', 0)->where('aluno', 0)->pluck('id')->all();
        $alunos= DB::table('users')->where('ativo' , 1)->where('tipo_socio', 'P')->where('tipo_licenca', '!=', 'PU')->where('instrutor', 0)->where('aluno', 1)->pluck('id')->all();

        $pilots = [];    
        foreach($instrutores as $id) {
            $pilots[] = ['matricula' => 'D-EAYV', 'piloto_id' => $id];
        }
        foreach($normal as $id) {
            $pilots[] = ['matricula' => 'D-EAYV', 'piloto_id' => $id];
        }
        foreach($alunos as $id) {
            $pilots[] = ['matricula' => 'D-EAYV', 'piloto_id' => $id];
        }
        DB::table('aeronaves_pilotos')->insert($pilots);  // todos podem voar D-EAYV

        $pilots = [];    
        foreach($instrutores as $id) {
            $pilots[] = ['matricula' => 'G-CKIP', 'piloto_id' => $id];
        }
        foreach($normal as $id) {   
            $randomNumber =  mt_rand(1,5);
            if ($randomNumber > 1) {
                $pilots[] = ['matricula' => 'G-CKIP', 'piloto_id' => $id];
            }
        }
        DB::table('aeronaves_pilotos')->insert($pilots); // 80 % dos pilotos normais podem voar o G-CGIP

        $pilots = [];    
        foreach($instrutores as $id) {
            $pilots[] = ['matricula' => 'CS-AQN', 'piloto_id' => $id];
        }
        foreach($normal as $id) {   
            $randomNumber = mt_rand(1,5);
            if ($randomNumber == 1) {
                $pilots[] = ['matricula' => 'CS-AQN', 'piloto_id' => $id];
            }
        }
        DB::table('aeronaves_pilotos')->insert($pilots); // só 20 % dos pilotos normais podem voar o G-CGIP
    }
}
