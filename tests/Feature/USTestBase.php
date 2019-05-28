<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\User;

class USTestBase extends TestCase
{
    //use RefreshDatabase;
    use WithFaker;

    private static $initialSeed = false;

    // KNOWN VALUES - USED FOR ASSERTIONS:
    protected $normalUser;
    protected $normalUser2;
    protected $normalUserComFoto;
    protected $pilotoUser;
    protected $direcaoUser;
    protected $semQuotaUser;
    protected $desativadoUser;
    protected $softDeletedUser;
    protected $passwordInicialUser;
    protected $emailNaoVerificadoUser;
    protected $pilotoAlunoUser;
    protected $pilotoInstrutorUser;
    protected $pilotoDesativadoUser;
    protected $pilotoDirecaoUser;
    protected $tipoLicenca;
    protected $classeCertificado;
    protected $aerodromo;
    protected $aeronave;
    protected $aeronaveDeleted;
    protected $aeronave_valores;
    protected $normalMovimento;
    protected $normalMovimento2;
    protected $instrucaoMovimento;

    // USED TO RESET DATA ()
    protected static $latestUserID;
    protected static $latestMovimentoID;
    protected static $latestContaHoras;

    // INFORMATION ABOUT DATA
    protected static $totalAtivos;
    protected static $totalDesativos;
    protected static $totalPilotos;
    protected static $totalMovimentos;
    protected static $totalAeronaves;

    // DATE FORMATS
    protected static $date_format_input;
    protected static $time_format_input;

    // FLAG TO MAKE A FRESH DB SEED
    protected static $forceSeedWithinTest = false;

    protected function setUp(): void
    {
        parent::setUp();

        USTestBase::$date_format_input = env('INPUT_FORMAT_DATE', 'Y-m-d H:i:s');
        USTestBase::$time_format_input = env('INPUT_FORMAT_TIME', 'Y-m-d H:i:s');        

        $forceSeed = USTestBase::$forceSeedWithinTest ? true: env('SEED_FORCE', false);
        $totalPrevisto = env('SEED_TOTAL_SOCIOS', 40);

        if (!USTestBase::$initialSeed) {
            $trySeed = true;
            $totalTentativas = 1;
            while ($trySeed) {
                $trySeed = false;
                try {
                    $total = DB::table('users')->count();
                    $seed = ($total < $totalPrevisto) || ($total > 2*$totalPrevisto);
                    $seed = !$seed ? $forceSeed : true;
                    if ($seed) {
                        dump("Seed da Base de Dados - tentativa $totalTentativas");
                        DB::statement("SET foreign_key_checks=0");

                        $this->seed(\TiposLicencasSeeder::class);
                        $this->seed(\ClassesCertificadosSeeder::class);
                        $this->seed(\AerodromosSeeder::class);
                        $this->seed(\AeronavesSeeder::class);
                        $this->seed(\AeronavesValoresSeeder::class);

                        // Custom/known values
                        $this->seedTipoLicenca();
                        $this->seedClasseCertificado();
                        $this->seedAerodromo();

                        // Remaing Seeds:
                        $this->seed(\UsersSeeder::class);
                        $this->seed(\AeronavesPilotosSeeder::class);
                        $this->seed(\MovimentosSeeder::class);

                        // Remove excessive movimentos (devido a paginação)
                        $this->cutMovimentos(12);

                        DB::statement("SET foreign_key_checks=1");
                    }

                    USTestBase::$latestUserID = DB::table('users')->max('id');
                    USTestBase::$latestMovimentoID = DB::table('movimentos')->max('id');
                    USTestBase::$latestContaHoras = DB::table('aeronaves')->pluck('conta_horas', 'matricula');
                    USTestBase::$totalAtivos = DB::table('users')->whereNull('deleted_at')->where('ativo',1)->count();
                    USTestBase::$totalDesativos = DB::table('users')->whereNull('deleted_at')->where('ativo',0)->count();
                    USTestBase::$totalPilotos = DB::table('users')->whereNull('deleted_at')->where('tipo_socio','P')->count();
                    USTestBase::$totalMovimentos = DB::table('movimentos')->count();
                    USTestBase::$totalAeronaves = DB::table('aeronaves')->whereNull('deleted_at')->count();

                    USTestBase::$initialSeed = true;

                } catch (\Exception $e) {
                    dump("Seed da Base de Dados de Teste Falhou. Vai ser feita uma nova tentativa!");
                    $totalTentativas++;
                    $trySeed = true;
                    if ($totalTentativas > 10) {
                        $trySeed = false;                        
                        throw $e;
                    }

                }
            }
        }
        $this->resetData();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    protected function tearDown(): void
    {
        $this->resetData();
        parent::tearDown();
    }

    protected function resetData()
    {
        if ($this->normalUserComFoto) {
            $this->deletePhotoByName($this->normalUserComFoto->foto_url);
        }

        DB::table('movimentos')->where('id', '>', USTestBase::$latestMovimentoID)->delete();
        DB::table('aeronaves_pilotos')->where('piloto_id', '>', USTestBase::$latestUserID)->delete();
        DB::table('users')->where('id', '>', USTestBase::$latestUserID)->delete();
        DB::table('aeronaves')->whereNotIn('matricula', ['CS-AQN', 'CS-AYV', 'CS-DCX', 'D-EAYV', 'G-CKIP'])->delete();
        DB::table('aeronaves')->whereIn('matricula', ['CS-AQN', 'D-EAYV', 'G-CKIP'])->update(['deleted_at' => null]);
//      DB::table('aeronaves')->where('matricula', 'CS-XXX')->orWhere('matricula', 'CS-NEW')->delete();  
        DB::table('aeronaves_valores')->whereNotIn('matricula', ['CS-AQN', 'CS-AYV', 'CS-DCX', 'D-EAYV', 'G-CKIP'])->delete();
        
        foreach (USTestBase::$latestContaHoras as $matricula => $conta_horas) {
            DB::table('aeronaves')->where('matricula', $matricula)->update(['conta_horas' => $conta_horas]);
        }

    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    private function cutMovimentos($totalMovimentos) 
    {
        $allIDs = DB::table('movimentos')->pluck('id')->toArray();
        if (count($allIDs) > $totalMovimentos) {
            $idsToKeep = array_slice($allIDs, -$totalMovimentos);
            DB::table('movimentos')->whereNotIn('id', $idsToKeep)->delete();
        }
    }

    protected function format_date_input($dateWithDbFormat) 
    {
        if (is_null($dateWithDbFormat)) {
            return null;
        }
        if (trim($dateWithDbFormat) == "") {
            return "";
        }
        return \Carbon\Carbon::createFromFormat('Y-m-d', $dateWithDbFormat)->format(USTestBase::$date_format_input);
    }

    protected function format_date_db($dateWithInputFormat) 
    {
        if (is_null($dateWithInputFormat)) {
            return null;
        }
        if (trim($dateWithInputFormat) == "") {
            return "";
        }
        return \Carbon\Carbon::createFromFormat(USTestBase::$date_format_input, $dateWithInputFormat)->format("Y-m-d");
    }

    protected function format_time_input($timeWithDbFormat) 
    {
        if (is_null($timeWithDbFormat)) {
            return null;
        }
        if (trim($timeWithDbFormat) == "") {
            return "";
        }
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $timeWithDbFormat)->format(USTestBase::$time_format_input);
    }

    protected function format_time_db($timeWithInputFormat) 
    {
        if (is_null($timeWithInputFormat)) {
            return null;
        }
        if (trim($timeWithInputFormat) == "") {
            return "";
        }
        return \Carbon\Carbon::createFromFormat(USTestBase::$date_format_input, $timeWithInputFormat)->format("Y-m-d H:i:s");
    }

    protected function getPilotInfo($idPiloto)
    {
        $user = collect(DB::table('users')->where('id', $idPiloto)->first())->toArray();
        return array_intersect_key($user, [
            // Chave dos elementos do array $user que serão copiados para o novo array
            // Valores não interessam 
            // 
            "id" => null,
            "num_licenca" => null,
            "tipo_licenca" => null,
            "validade_licenca" => null,
            "num_certificado" => null,
            "classe_certificado" => null, 
            "validade_certificado" => null
        ]);
    }

    private $pilotos_ids = [];
    protected function standardMovimento($idPiloto = null)
    {
        $createdAt = $this->faker->dateTimeBetween('-7 days', '-1 days');
        $updatedAt = $this->faker->dateTimeBetween($createdAt);
        if (!$this->pilotos_ids) {
            $this->pilotos_ids = DB::table('users')->whereNull('deleted_at')->where('tipo_socio','P')->pluck('id')->toArray();
        }

        if (!$idPiloto) {
            $idPiloto = $this->pilotos_ids[array_rand($this->pilotos_ids)];
        }

        $pilotoInfo = $this->getPilotInfo($idPiloto);
        $data = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d'). ' 00:00:00');
        $unidades_conta_horas = mt_rand(5,25);
//        $conta_inicio = DB::table('aeronaves')->where('matricula', 'D-EAYV')->first()->conta_horas;
        $conta_inicio = DB::table('movimentos')->where('aeronave', 'D-EAYV')->max('conta_horas_fim');
        $conta_fim = $conta_inicio + $unidades_conta_horas; 
        $preco_hora = DB::table('aeronaves_valores')
                    ->where('matricula', 'D-EAYV')
                    ->where('unidade_conta_horas', 10)
                    ->first()->preco;
        $duracao_minutos = 60 * intdiv($unidades_conta_horas, 10);
        $preco_voo = ($preco_hora * intdiv($unidades_conta_horas, 10));
        if ($unidades_conta_horas % 10 > 0) {
            $valores_unidade = DB::table('aeronaves_valores')
                        ->where('matricula', 'D-EAYV')
                        ->where('unidade_conta_horas', $unidades_conta_horas % 10)
                        ->first();
            $duracao_minutos = (60 * intdiv($unidades_conta_horas, 10)) +  $valores_unidade->minutos;
            $preco_voo = ($preco_hora * intdiv($unidades_conta_horas, 10)) + $valores_unidade->preco;
        }
        $hora = mt_rand(9,15);
        $minuto = mt_rand(0,11) * 5;
        $dataDescolagem = $data->copy()->addMinutes(60*$hora+$minuto);
        $dataAterragem = $dataDescolagem->copy()->addMinutes($duracao_minutos);
        $num_aterragens = mt_rand(13,100);
        return  [
            "data" => $data,
            "hora_descolagem" => $dataDescolagem,
            "hora_aterragem" => $dataAterragem,
            "aeronave" => "D-EAYV",
            "num_diario" => DB::table('movimentos')->where('aeronave', 'D-EAYV')->max('num_diario'),
            "num_servico" => (DB::table('movimentos')->where('aeronave', 'D-EAYV')->max('num_servico'))+1,
            "piloto_id" => $idPiloto,
            "num_licenca_piloto" => $pilotoInfo['num_licenca'],
            "validade_licenca_piloto" => $pilotoInfo['validade_licenca'],
            "tipo_licenca_piloto" => $pilotoInfo['tipo_licenca'],
            "num_certificado_piloto" => $pilotoInfo['num_certificado'],
            "validade_certificado_piloto" => $pilotoInfo['validade_certificado'],
            "classe_certificado_piloto" => $pilotoInfo['classe_certificado'],
            "natureza" => "T",
            "aerodromo_partida" => "U-POMBAL",
            "aerodromo_chegada" => "U-LAGOS",
            "num_aterragens" => $num_aterragens,
            "num_descolagens" => $num_aterragens,
            "num_pessoas" => mt_rand(9,25),
            "conta_horas_inicio" => $conta_inicio,
            "conta_horas_fim" => $conta_fim,
            "tempo_voo" => $preco_voo,
            "preco_voo" => $duracao_minutos,
            "modo_pagamento" => "N", 
            "num_recibo" => mt_rand(777777,999999),
            "observacoes" => "obsercavoes de teste",
            "confirmado" => 1,
            "tipo_instrucao" => null,
            "instrutor_id" => null,
            "num_licenca_instrutor" => null,
            "validade_licenca_instrutor" => null,
            "tipo_licenca_instrutor" => null, 
            "num_certificado_instrutor" => null,
            "validade_certificado_instrutor" => null,
            "classe_certificado_instrutor" => null,
            "created_at" => $createdAt,
            "updated_at" => $updatedAt,
            "tipo_conflito" => null,
            "justificacao_conflito" => null
        ];
    }

    protected function standardMovimentoComFormatoInput($idPiloto = null)
    {
        $mov = $this->standardMovimento($idPiloto);
        // Usa formato de data correto
        $mov["data"] = $this->format_date_input($mov["data"]->format('Y-m-d'));
        $mov["hora_descolagem"] = $this->format_time_input($mov["hora_descolagem"]->format('Y-m-d H:i:s'));
        $mov["hora_aterragem"] = $this->format_time_input($mov["hora_aterragem"]->format('Y-m-d H:i:s'));
        if ($mov["validade_licenca_piloto"]) {
            $mov["validade_licenca_piloto"] = $this->format_date_input($mov["validade_licenca_piloto"]);     
        }
        if ($mov["validade_certificado_piloto"]) {
            $mov["validade_certificado_piloto"] = $this->format_date_input($mov["validade_certificado_piloto"]);     
        }        
        if ($mov["validade_licenca_instrutor"]) {
            $mov["validade_licenca_instrutor"] = $this->format_date_input($mov["validade_licenca_instrutor"]);     
        }
        if ($mov["validade_certificado_instrutor"]) {
            $mov["validade_certificado_instrutor"] = $this->format_date_input($mov["validade_certificado_instrutor"]);     
        }        

        unset($mov["created_at"]);
        unset($mov["updated_at"]);

        return $mov;
    }

    private $instrutores_ids = [];
    protected function instrucaoMovimento($idPiloto = null, $idInstrutor = null)
    {
        $mov = $this->standardMovimento($idPiloto);

        if (!$idInstrutor) {
            $this->instrutores_ids = DB::table('users')->whereNull('deleted_at')->where('tipo_socio','P')->where('instrutor',1)->pluck('id')->toArray();
            if ($this->instrutores_ids) {
                $idInstrutor = $this->instrutores_ids[array_rand($this->instrutores_ids)];
            }
        }

        if (!$idInstrutor) {
            throw new Exception("No Instructor Available");
        }

        $instrutorInfo = $this->getPilotInfo($idInstrutor);

        $mov = array_merge($mov, [
            "natureza" => "I",
            "tipo_instrucao" => "D",
            "instrutor_id" => $idInstrutor,
            "num_licenca_instrutor" => $instrutorInfo['num_licenca'],
            "validade_licenca_instrutor" => $instrutorInfo['validade_licenca'],
            "tipo_licenca_instrutor" => $instrutorInfo['tipo_licenca'],
            "num_certificado_instrutor" => $instrutorInfo['num_certificado'],
            "validade_certificado_instrutor" => $instrutorInfo['validade_certificado'],
            "classe_certificado_instrutor" => $instrutorInfo['classe_certificado']
        ]);

        return $mov;
    }

    protected function instrucaoMovimentoComFormatoInput($idPiloto = null, $idInstrutor = null)
    {
        $mov = $this->instrucaoMovimento($idPiloto, $idInstrutor);

        // Usa formato de data correto
        $mov["data"] = $this->format_date_input($mov["data"]->format('Y-m-d'));
        $mov["hora_descolagem"] = $this->format_time_input($mov["hora_descolagem"]->format('Y-m-d H:i:s'));
        $mov["hora_aterragem"] = $this->format_time_input($mov["hora_aterragem"]->format('Y-m-d H:i:s'));
        if ($mov["validade_licenca_piloto"]) {
            $mov["validade_licenca_piloto"] = $this->format_date_input($mov["validade_licenca_piloto"]);     
        }
        if ($mov["validade_certificado_piloto"]) {
            $mov["validade_certificado_piloto"] = $this->format_date_input($mov["validade_certificado_piloto"]);     
        }        
        if ($mov["validade_licenca_instrutor"]) {
            $mov["validade_licenca_instrutor"] = $this->format_date_input($mov["validade_licenca_instrutor"]);     
        }
        if ($mov["validade_certificado_instrutor"]) {
            $mov["validade_certificado_instrutor"] = $this->format_date_input($mov["validade_certificado_instrutor"]);     
        }        

        unset($mov["created_at"]);
        unset($mov["updated_at"]);

        return $mov;
    }

    protected function standardUser($softDeleted = false)
    {
        $createdAt = $this->faker->dateTimeBetween('-10 years', '-3 months');
        $email_verified_at = $this->faker->dateTimeBetween($createdAt, '-2 months');
        $updatedAt = $this->faker->dateTimeBetween($email_verified_at, '-1 months');
        $deletedAt = $this->faker->dateTimeBetween($updatedAt);

        return  [
            "direcao" => "0",
            "tipo_socio" => "NP",
            "ativo" => "1",
            "password_inicial" => "0",
            "quota_paga" => "1",
            "aluno" => "0",
            "instrutor" => "0",
            "num_socio" => "1001",
            "name" => "User Complete Name",
            "nome_informal"=> "User Name",
            "data_nascimento" =>  "1994-12-16",
            "sexo" => "M",
            "email" => "user_standard@mail.pt",
            "password" => bcrypt("123123123"),
            "telefone" => "223753442",
            "nif" => "773084318",
            "endereco" => "Rua dos testes 123",
            "num_licenca" => null,
            "tipo_licenca" => null,
            "validade_licenca" => null,
            "licenca_confirmada" => null,
            "num_certificado" => null,
            "classe_certificado" => null,
            "certificado_confirmado" => null,
            "created_at" => $createdAt,
            "updated_at" => $updatedAt,
            "email_verified_at" => $email_verified_at,
            "deleted_at" => $softDeleted ? $deletedAt : null
        ];
    }

    private function addUserToDB($user)
    {
        $id =  DB::table('users')->insertGetId($user);
        return User::withTrashed()->findOrFail($id);
    }

    private function addMovimentoToDB($movimento)
    {
        //return factory(User::class)->create($user);
        $id =  DB::table('movimentos')->insertGetId($movimento);
        $newMovimento = DB::table('movimentos')->where('id', $id)->first();
        DB::table('aeronaves')->where('matricula', 'D-EAYV')->update(['conta_horas' => $newMovimento->conta_horas_fim]);
        return $newMovimento;
    }

    protected function addPilotoToAeronaves($userid)
    {
        if (DB::table('aeronaves_pilotos')->where("piloto_id", $userid)->count() == 0) {
            $aeronaves = ['D-EAYV', 'G-CKIP', 'CS-AQN'];
            foreach ($aeronaves as $aeronave) {
                DB::table('aeronaves_pilotos')->insert([
                    "piloto_id" => $userid,
                    "matricula" => $aeronave
                ]);
            }
        }
    }

    private function addAeronaveToDB($aeronave)
    {
        DB::table('aeronaves')->insert($aeronave);
        return DB::table('aeronaves')->where('matricula', $aeronave['matricula'])->first();
    }


    protected function seedNormalMovimentos($idPiloto = null)
    {        
        $mov = $this->standardMovimento($idPiloto);
        $this->normalMovimento = $this->addMovimentoToDB($mov);

        $mov = $this->standardMovimento($idPiloto);
        $this->normalMovimento2 = $this->addMovimentoToDB($mov);
    }

    protected function seedInstrucaoMovimento($idPiloto = null, $idInstrutor = null)
    {        
        $mov = $this->instrucaoMovimento($idPiloto, $idInstrutor);
        $this->instrucaoMovimento = $this->addMovimentoToDB($mov);
    }

    protected function updateMovimento_data($id, $data)
    {        
        $mov = DB::table('movimentos')->where('id',$id)->first();
        $mov->data = $data;
        $mov->hora_descolagem = $data . ' ' . substr($mov->hora_descolagem, -8);
        $mov->hora_aterragem = $data . ' ' . substr($mov->hora_aterragem, -8);
        DB::table('movimentos')->where('id',$id)->update(get_object_vars($mov));
    }

    protected function updateMovimento_natureza($id, $natureza)
    {        
        $mov = DB::table('movimentos')->where('id',$id)->first();
        $mov->natureza = $natureza;
        DB::table('movimentos')->where('id',$id)->update(get_object_vars($mov));
    }

    protected function updateMovimento_confirmado($id, $confirmado)
    {        
        $mov = DB::table('movimentos')->where('id',$id)->first();
        $mov->confirmado = $confirmado;
        DB::table('movimentos')->where('id',$id)->update(get_object_vars($mov));
    }

    protected function seedNormalUsers()
    {
        $normalUser = array_merge($this->standardUser(),
            [
            "num_socio" => "1001",
            "name" => "Normal User Complete Name",
            "nome_informal"=> "Informal Name 1",
            "email" => "normal@usermail.pt",
            ]);
        $this->normalUser = $this->addUserToDB($normalUser);
        $normalUser2 = array_merge($this->standardUser(),
            [
            "num_socio" => "1013",
            "name" => "Second Normal User Complete Name",
            "nome_informal"=> "Second Informal Name 2",
            "email" => "normal2@usermail.pt",
            ]);
        $this->normalUser2 = $this->addUserToDB($normalUser2);
    }

    protected function seedPilotoUser()
    {
        $pilotoUser = array_merge($this->standardUser(),
            [
            "tipo_socio" => "P",
            "num_socio" => "1002",
            "name" => "Piloto Complete Name",
            "nome_informal"=> "Piloto Name",
            "email" => "piloto@usermail.pt",
            "num_licenca" => "991234",
            "tipo_licenca" => "PPL(A)",
            "validade_licenca" => "2020-02-23",
            "licenca_confirmada" => "1",
            "num_certificado" => "99-0089",
            "classe_certificado" => "Class 2",
            "validade_certificado" => "2020-02-13",
            "certificado_confirmado" => "1",
            ]);
        $this->pilotoUser = $this->addUserToDB($pilotoUser);
        $this->addPilotoToAeronaves($this->pilotoUser->id);
    }

    protected function seedDirecaoUser()
    {
        $direcaoUser = array_merge($this->standardUser(),
            [
            "num_socio" => "1003",
            "name" => "Direcao User Complete Name",
            "nome_informal"=> "Direcao User",
            "email" => "direcao@usermail.pt",
            "direcao" => "1",
            ]);
        $this->direcaoUser = $this->addUserToDB($direcaoUser);
    }

    protected function seedQuotaNaoPagaUser()
    {
        $semQuotaUser = array_merge($this->standardUser(),
            [
            "num_socio" => "1004",
            "name" => "User Sem Quotas Pagas Complete Name",
            "nome_informal"=> "Sem Quota User",
            "email" => "semquota@usermail.pt",
            "quota_paga" => "0",
            ]);
        $this->semQuotaUser = $this->addUserToDB($semQuotaUser);
    }

    protected function seedDesativadoUser()
    {
        $desativadoUser = array_merge($this->standardUser(),
            [
            "num_socio" => "1005",
            "name" => "User Desativado Complete Name",
            "nome_informal"=> "Desativado User",
            "email" => "desativado@usermail.pt",
            "ativo" => "0",
            ]);
        $this->desativadoUser = $this->addUserToDB($desativadoUser);
    }

    protected function seedSoftDeletedUser()
    {
        $softDeletedUser = array_merge($this->standardUser(true),
            [
            "num_socio" => "1006",
            "name" => "User Soft Deleted Complete Name",
            "nome_informal"=> "Soft Deleted User",
            "email" => "softdeleted@usermail.pt",
            ]);
        $this->softDeletedUser = $this->addUserToDB($softDeletedUser);
    }

    protected function seedPasswordInicialUser()
    {
        $passwordInicialUser = array_merge($this->standardUser(),
            [
            "num_socio" => "1007",
            "name" => "User Desativado Complete Name",
            "nome_informal"=> "Desativado User",
            "email" => "passwordinicial@usermail.pt",
            "password_inicial" => "1",
            ]);
        $this->passwordInicialUser = $this->addUserToDB($passwordInicialUser);
    }

    protected function seedPilotoAlunoUser()
    {
        $pilotoAlunoUser = array_merge($this->standardUser(),
            [
            "tipo_socio" => "P",
            "num_socio" => "1008",
            "aluno" => "1",
            "name" => "Piloto Aluno Complete Name",
            "nome_informal"=> "Piloto Aluno",
            "email" => "pilotoaluno@usermail.pt",
            "num_licenca" => "881234",
            "tipo_licenca" => "ALUNO-PPL(A)",
            "validade_licenca" => "2019-02-23",
            "licenca_confirmada" => "1",
            "num_certificado" => "88-0089",
            "classe_certificado" => "Class 2",
            "validade_certificado" => "2021-04-13",
            "certificado_confirmado" => "1",
            ]);
        $this->pilotoAlunoUser = $this->addUserToDB($pilotoAlunoUser);
        $this->addPilotoToAeronaves($this->pilotoAlunoUser->id);        
    }

    protected function seedPilotoInstrutorUser()
    {
        $pilotoInstrutorUser = array_merge($this->standardUser(),
            [
            "tipo_socio" => "P",
            "num_socio" => "1009",
            "instrutor" => "1",
            "name" => "Piloto Instrutor Complete Name",
            "nome_informal"=> "Piloto Instrutor",
            "email" => "pilotoinstrutor@usermail.pt",
            "num_licenca" => "771234",
            "tipo_licenca" => "CPL(A)",
            "validade_licenca" => "2020-12-01",
            "licenca_confirmada" => "1",
            "num_certificado" => "77-0089",
            "classe_certificado" => "Class 1",
            "validade_certificado" => "2019-12-13",
            "certificado_confirmado" => "1",
            ]);
        $this->pilotoInstrutorUser = $this->addUserToDB($pilotoInstrutorUser);
        $this->addPilotoToAeronaves($this->pilotoInstrutorUser->id);
    }

    protected function seedPilotoDesativadoUser()
    {
        $pilotoDesativadoUser = array_merge($this->standardUser(),
            [
            "tipo_socio" => "P",
            "num_socio" => "1010",
            "ativo" => "0",
            "name" => "Pilot Desativado Complete Name",
            "nome_informal"=> "Piloto Desativado Name",
            "email" => "pilotodesativado@usermail.pt",
            "num_licenca" => "661234",
            "tipo_licenca" => "PPL(A)",
            "validade_licenca" => "2019-05-01",
            "licenca_confirmada" => "1",
            "num_certificado" => "66-0089",
            "classe_certificado" => "Class 2",
            "validade_certificado" => "2019-08-13",
            "certificado_confirmado" => "1",
            ]);
        $this->pilotoDesativadoUser = $this->addUserToDB($pilotoDesativadoUser);
        $this->addPilotoToAeronaves($this->pilotoDesativadoUser->id);
    }

    protected function seedPilotoDirecaoUser()
    {
        $pilotoDirecaoUser = array_merge($this->standardUser(),
            [
            "tipo_socio" => "P",
            "num_socio" => "1011",
            "direcao" => "1",
            "name" => "Piloto Instrutor Complete Name",
            "nome_informal"=> "Piloto Instrutor",
            "email" => "pilotodirecao@usermail.pt",
            "num_licenca" => "551234",
            "tipo_licenca" => "CPL(A)",
            "validade_licenca" => "2021-03-09",
            "licenca_confirmada" => "1",
            "num_certificado" => "55-0089",
            "classe_certificado" => "Class 1",
            "validade_certificado" => "2020-01-13",
            "certificado_confirmado" => "1",
            ]);
        $this->pilotoDirecaoUser = $this->addUserToDB($pilotoDirecaoUser);
        $this->addPilotoToAeronaves($this->pilotoDirecaoUser->id);
    }

    protected function seedEmailNaoVerificadoUser()
    {
        $emailNaoVerificadoUser = array_merge($this->standardUser(),
            [
            "num_socio" => "1012",
            "name" => "User sem e-Mail Verificado Complete Name",
            "nome_informal"=> "Email Nao Verificado User",
            "email" => "emailnaoverificado@usermail.pt",
            "email_verified_at" => null,
            ]);
        $this->emailNaoVerificadoUser = $this->addUserToDB($emailNaoVerificadoUser);
    }

    protected function seedNormalUserComFoto()
    {
        $normalUserComFoto = array_merge($this->standardUser(),
            [
            "num_socio" => "1013",
            "name" => "Normal User Com Foto Complete Name",
            "nome_informal"=> "Normal User Com Foto",
            "email" => "normal_com_foto@usermail.pt",
            ]);
        $this->normalUserComFoto = $this->addUserToDB($normalUserComFoto);
        $this->normalUserComFoto->foto_url = $this->createPhoto($this->normalUserComFoto->id);
    }



    // Extra Seeds Aeronave, Aeronave_pilotos, Aerodromo, tipos_licencas, classes_certificao
    protected function tipo_licenca()
    {
        return  [
            "code" => "NEWTYPE",
            "nome" => "Novo Tipo"
        ];
    }

    private function seedTipoLicenca()
    {
        $tipoLicenca = array_merge($this->tipo_licenca());
        $this->tipoLicenca = DB::table('tipos_licencas')->insert($tipoLicenca);
    }

    protected function classe_certificado()
    {
        return  [
            "code" => "NEWCLS",
            "nome" => "Nova Classe De Certificado"
        ];
    }

    private function seedClasseCertificado()
    {
        $classeCertificado = array_merge($this->classe_certificado());
        $this->classeCertificado = DB::table('classes_certificados')->insert($classeCertificado);
    }

    protected function aerodromo_()
    {
        return  [
            "code" => "SPMD",
            "nome" => "Madrid",
            "militar" => "0",
            "ultraleve" => "0",
        ];
    }

    private function seedAerodromo()
    {
        $aerodromo = array_merge($this->aerodromo_());
        $this->aerodromo = DB::table('aerodromos')->insert($aerodromo);
    }

    protected function aeronave_($softDeleted = false)
    {
        return  [
            "matricula" => $softDeleted ? "CS-XXX": "CS-NEW",
            "marca" => $softDeleted ? "MarcaX": "MarcaNew",
            "modelo" => $softDeleted ? "X1": "N1",
            "num_lugares" => $softDeleted ? 5: 3,
            "conta_horas" => $softDeleted ? 5000: 1000,
            "preco_hora" => $softDeleted ? 250: 80,
            'created_at'  => '2014-12-18 15:12:56',
            'updated_at'  => '2015-03-23 13:54:01',
            'deleted_at'  => $softDeleted ? '2018-12-05 23:33:01': null
        ];
    }

    protected function seedAeronaves()
    {
        $aeronave = array_merge($this->aeronave_());
        $aeronave_deleted = array_merge($this->aeronave_(true));
        $this->aeronave = $this->addAeronaveToDB($aeronave);
        $this->aeronaveDeleted = $this->addAeronaveToDB($aeronave_deleted);        
        $this->aeronave_valores = $this->aeronaves_valores();
        DB::table('aeronaves_valores')->insert($this->aeronave_valores);
    }

    private function aeronaves_valores()
    {
        return [
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 1,
                'minutos' => 5,
                'preco' => 10
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 2,
                'minutos' => 10,
                'preco' => 20
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 3,
                'minutos' => 20,
                'preco' => 30
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 4,
                'minutos' => 25,
                'preco' => 40
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 5,
                'minutos' => 30,
                'preco' => 50
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 6,
                'minutos' => 35,
                'preco' => 60
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 7,
                'minutos' => 40,
                'preco' => 65
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 8,
                'minutos' => 50,
                'preco' => 70
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 9,
                'minutos' => 55,
                'preco' => 75
            ],
            [
                'matricula'  => 'CS-NEW',
                'unidade_conta_horas'  => 10,
                'minutos' => 60,
                'preco' => 80
            ]
        ];
    }

    private function seedAeronavesValores()
    {
        $aeronaves_valores = array_merge($this->aeronaves_valores());
        DB::table('aeronaves_valores')->insert($aeronaves_valores);
    }

    protected function getRequestArrayFromUser($user) {
        if ($user) {
            return [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "num_socio" => $user->num_socio,
                "direcao" => $user->direcao,
                "tipo_socio" => $user->tipo_socio,
                "password_inicial" => $user->password_inicial,
                "ativo" => $user->ativo,
                "quota_paga" => $user->quota_paga,
                "nome_informal" => $user->nome_informal,
                "data_nascimento" => $this->format_date_input($user->data_nascimento),
                "sexo" => $user->sexo,
                "telefone" => $user->telefone,
                "nif" => $user->nif,
                "endereco" => $user->endereco,
                "aluno" => $user->aluno,
                "instrutor" => $user->instrutor,
                "num_licenca" => $user->num_licenca,
                "tipo_licenca" => $user->tipo_licenca,
                "validade_licenca" => $this->format_date_input($user->validade_licenca),
                "licenca_confirmada" => $user->licenca_confirmada,
                "num_certificado" => $user->num_certificado,
                "classe_certificado" => $user->classe_certificado,
                "validade_certificado" => $this->format_date_input($user->validade_certificado),
                "certificado_confirmado" => $user->certificado_confirmado
            ];
        }
        return [
                "id" => "",
                "num_socio" => "",
                "direcao" => "",
                "tipo_socio" => "",
                "password_inicial" => "",
                "ativo" => "",
                "quota_paga" => "",
                "name" => "",
                "nome_informal" => "",
                "data_nascimento" => "",
                "sexo" => "",
                "email" => "",
                "telefone" => "",
                "nif" => "",
                "endereco" => "",
                "aluno" => "",
                "instrutor" => "",
                "num_licenca" => "",
                "tipo_licenca" => "",
                "validade_licenca" => "",
                "licenca_confirmada" => "",
                "num_certificado" =>  "",
                "classe_certificado" =>  "",
                "validade_certificado" =>  "",
                "certificado_confirmado" =>  ""
            ];
    }

    // protected function getRequestArrayFromAeronave($aeronave) {
    //     // $precos = [];
    //     // $tempos = [];
    //     // if ($aeronave && $this->aeronave) {
    //     //     if ($aeronave->matricula == $this->aeronave->marticula) {
    //     //         foreach ( $this->aeronave_valores as $valor) {
    //     //             $precos[$valor->unidade_conta_horas] = $valor->preco;
    //     //             $tempos[$valor->unidade_conta_horas] = $valor->minutos;
    //     //         }
    //     //     }
    //     // }


    //     $valores = [];
    //     if ($aeronave && $this->aeronave) {
    //         if ($aeronave->matricula == $this->aeronave->matricula) {
    //             foreach ($this->aeronave_valores as $valor) {
    //                 $valores["precos[{$valor['unidade_conta_horas']}]"] = $valor["preco"];
    //                 $valores["tempos[{$valor['unidade_conta_horas']}]"] = $valor["minutos"];
    //             }
    //         }
    //     }
    //     if ($aeronave) {
    //         return array_merge([
    //                 "matricula" => $aeronave->matricula,
    //                 "marca" => $aeronave->marca,
    //                 "modelo" => $aeronave->modelo,
    //                 "num_lugares" => $aeronave->num_lugares,
    //                 "conta_horas" => $aeronave->conta_horas,
    //                 "preco_hora" => $aeronave->preco_hora
    //             ], $valores);
    //     }
    //     return  array_merge([
    //                 "matricula" => "",
    //                 "marca" => "",
    //                 "modelo" => "",
    //                 "num_lugares" => "",
    //                 "conta_horas" => "",
    //                 "preco_hora" => ""
    //         ], $valores);
    // }

    protected function getRequestArrayFromAeronave($aeronave) {
        // $precos = [];
        // $tempos = [];
        // if ($aeronave && $this->aeronave) {
        //     if ($aeronave->matricula == $this->aeronave->marticula) {
        //         foreach ( $this->aeronave_valores as $valor) {
        //             $precos[$valor->unidade_conta_horas] = $valor->preco;
        //             $tempos[$valor->unidade_conta_horas] = $valor->minutos;
        //         }
        //     }
        // }


        $tempos = [];
        $precos = [];
        if ($aeronave && $this->aeronave) {
            if ($aeronave->matricula == $this->aeronave->matricula) {
                foreach ($this->aeronave_valores as $valor) {
                    $precos[$valor['unidade_conta_horas']] = $valor["preco"];
                    $tempos[$valor['unidade_conta_horas']] = $valor["minutos"];
                }
            }
        }
        if ($aeronave) {
            return [
                    "matricula" => $aeronave->matricula,
                    "marca" => $aeronave->marca,
                    "modelo" => $aeronave->modelo,
                    "num_lugares" => $aeronave->num_lugares,
                    "conta_horas" => $aeronave->conta_horas,
                    "preco_hora" => $aeronave->preco_hora,
                    "tempos" => $tempos,
                    "precos" => $precos
                ];
        }
        return  [
                    "matricula" => "",
                    "marca" => "",
                    "modelo" => "",
                    "num_lugares" => "",
                    "conta_horas" => "",
                    "preco_hora" => "",
                    "tempos" => $tempos,
                    "precos" => $precos

            ];
    }

    protected function getRequestArrayFromMovimento($movimento) {        
        if ($movimento) {
            return [
                "id" => $movimento->id,
                "data" => $this->format_date_input($movimento->data), 
                "hora_descolagem" => $this->format_time_input($movimento->hora_descolagem),
                "hora_aterragem" => $this->format_time_input($movimento->hora_aterragem),
                "aeronave" => $movimento->aeronave,
                "num_diario" => $movimento->num_diario,
                "num_servico" => $movimento->num_servico,
                "piloto_id" => $movimento->piloto_id,
                "num_licenca_piloto" => $movimento->num_licenca_piloto,
                "validade_licenca_piloto" => $this->format_date_input($movimento->validade_licenca_piloto),
                "tipo_licenca_piloto" => $movimento->tipo_licenca_piloto,
                "num_certificado_piloto" => $movimento->num_certificado_piloto,
                "validade_certificado_piloto" => $this->format_date_input($movimento->validade_certificado_piloto),
                "classe_certificado_piloto" => $movimento->classe_certificado_piloto,
                "natureza" => $movimento->natureza,
                "aerodromo_partida" => $movimento->aerodromo_partida,
                "aerodromo_chegada" => $movimento->aerodromo_chegada,
                "num_aterragens" => $movimento->num_aterragens,
                "num_descolagens" => $movimento->num_descolagens,
                "num_pessoas" => $movimento->num_pessoas, 
                "conta_horas_inicio" => $movimento->conta_horas_inicio,
                "conta_horas_fim" => $movimento->conta_horas_fim,
                "tempo_voo" => $movimento->tempo_voo,
                "preco_voo" => $movimento->preco_voo,
                "modo_pagamento" => $movimento->modo_pagamento,
                "num_recibo" => $movimento->num_recibo,
                "observacoes" => $movimento->observacoes,
                "confirmado" => $movimento->confirmado,
                "tipo_instrucao" => $movimento->tipo_instrucao,
                "instrutor_id" => $movimento->instrutor_id,
                "num_licenca_instrutor" => $movimento->num_licenca_instrutor,
                "validade_licenca_instrutor" => $this->format_date_input($movimento->validade_licenca_instrutor),
                "tipo_licenca_instrutor" =>$movimento->tipo_licenca_instrutor,
                "num_certificado_instrutor" => $movimento->num_certificado_instrutor,
                "validade_certificado_instrutor" => $this->format_date_input($movimento->validade_certificado_instrutor),
                "classe_certificado_instrutor" => $movimento->classe_certificado_instrutor,
                "tipo_conflito" => $movimento->tipo_conflito,
                "justificacao_conflito" => $movimento->justificacao_conflito
                ];                
        }
        return [
                "id" => "",
                "data" => "",
                "hora_descolagem" => "",
                "hora_aterragem" => "",
                "aeronave" => "",
                "num_diario" => "",
                "num_servico" => "",
                "piloto_id" => "",
                "num_licenca_piloto" => "",
                "validade_licenca_piloto" => "",
                "tipo_licenca_piloto" => "",
                "num_certificado_piloto" => "",
                "validade_certificado_piloto" => "",
                "classe_certificado_piloto" => "",
                "natureza" => "",
                "aerodromo_partida" => "",
                "aerodromo_chegada" => "",
                "num_aterragens" => "",
                "num_descolagens" => "",
                "num_pessoas" => "", 
                "conta_horas_inicio" => "",
                "conta_horas_fim" => "",
                "tempo_voo" => "",
                "preco_voo" => "",
                "modo_pagamento" => "",
                "num_recibo" => "",
                "observacoes" => "",
                "confirmado" => "",
                "tipo_instrucao" => "",
                "instrutor_id" => "",
                "num_licenca_instrutor" => "",
                "validade_licenca_instrutor" => "",
                "tipo_licenca_instrutor" => "",
                "num_certificado_instrutor" => "",
                "validade_certificado_instrutor" => "",
                "classe_certificado_instrutor" => "",
                "created_at" => "",  
                "updated_at" => "",
                "tipo_conflito" => "",
                "justificacao_conflito" => "",                
            ];
    }

    protected function createPhoto($user_id)
    {
//        Storage::fake('local');

        $file = UploadedFile::fake()->image('foto.jpg', 50, 50)->size(100);
        $newFilePath = basename(Storage::putFile('public/fotos', $file, 'public'));

        DB::table('users')
            ->where('id', $user_id)
            ->update(['foto_url' => $newFilePath]);

        return $newFilePath;
    }

    protected function deletePhoto($user_id)
    {
        //Storage::fake('local');
        $photoUrl = DB::table('users')
            ->where('id', $user_id)
            ->first()->foto_url;
        Storage::delete('public/fotos/' . $photoUrl);
        return $photoUrl;
    }

    protected function deletePhotoByName($name)
    {
        //Storage::fake('local');
        Storage::delete('public/fotos/' . $name);
        return $name;
    }

    private function createPDF($name)
    {
        //Storage::fake('local');

        // $file = UploadedFile::fake()->create('document.pdf', 4);          
        // Storage::putFileAs('docs_piloto', $file, $name);

        copy(__DIR__."/pdf_file_for_tests.pdf", storage_path() . "/app/docs_piloto/$name");
        return $name;
    }

    protected function createLicencaPDF($user_id)
    {
        return $this->createPDF('licenca_' . $user_id . '.pdf');
    }

    protected function deleteLicencaPDF($user_id)
    {
        //Storage::fake('local');
        $filename = 'licenca_' . $user_id . '.pdf';
        Storage::delete('docs_piloto/' . $filename);
        return $filename;
    }

    protected function createCertificadoPDF($user_id)
    {
        return $this->createPDF('certificado_' . $user_id . '.pdf');
    }

    protected function deleteCertificadoPDF($user_id)
    {
        //Storage::fake('local');
        $filename = 'certificado_' . $user_id . '.pdf';
        Storage::delete('docs_piloto/' . $filename);
        return $filename;
    }


}
