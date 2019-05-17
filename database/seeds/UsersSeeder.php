<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    private $first_socio = 20;
    private $first_id = 1;
    private $total_socios = 400;
    private $remove_ficheiros = true;
    private $add_ficheiros = true;
    private $photoPath = 'public/fotos';
    private $docPath = 'docs_piloto';
    private $files_M = [];
    private $files_F = [];
    private $files_PDF = [];

    private $tipos_licencas = ['ATPL', 'CPL(A)', 'PU', 'PPL(A)', 'PPL(A)', 'PPL(A)'];
    private $tipos_licencas_instrutor = ['ATPL', 'CPL(A)'];
    private $classes_certificados = ['Class 1', 'Class 2', 'Class 2'];
    private $classes_certificados_UL = ['Class 1', 'Class 2', 'Class 2', 'Class 2', 'LAPL', 'LAPL'];

    private function initValuesFromConfig()
    {
        $this->first_socio = env('SEED_NUM_PRIMEIRO_SOCIO', 1);
        $this->first_id = env('SEED_ID_PRIMEIRO_SOCIO', 10000);
        $this->total_socios = env('SEED_TOTAL_SOCIOS', 400);
        $this->remove_ficheiros = env('SEED_REMOVE_FICHEIROS', '1') == '1';
        $this->add_ficheiros = env('SEED_ADD_FICHEIROS', '1') == '1';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->initValuesFromConfig();

        DB::table('users')->truncate();

        DB::statement("ALTER TABLE users AUTO_INCREMENT=" . $this->first_id);

        $this->command->line('Fotos vão ser armazenados na pasta '.storage_path('app/'.$this->photoPath));

        // if ($this->command->confirm('Deseja apagar as fotos já existentes na pasta '
        //         .storage_path('app/'.$this->photoPath).'?', true)) {
        //     Storage::deleteDirectory($this->photoPath);
        // }

        if ($this->remove_ficheiros) {
            Storage::deleteDirectory($this->photoPath);
            Storage::makeDirectory($this->photoPath);
            Storage::deleteDirectory($this->docPath);
            Storage::makeDirectory($this->docPath);
        }

        // Preencher files_M com fotos de Homens e files_F com fotos de mulheres
        $allFiles = collect(File::files(database_path('seeds/fotos')));
        foreach ($allFiles as $f) {
            if (strpos($f->getPathname(), 'M_')) {
                $this->files_M[] = $f->getPathname();
            }
            else {
                $this->files_F[] = $f->getPathname();
            }
        }

        // Preencher files_PDF com documentos PDFs
        $allPDFFiles = collect(File::files(database_path('seeds/docs')));
        foreach ($allPDFFiles as $f) {
                $this->files_PDF[] = $f->getPathname();
        }

        $faker = \Faker\Factory::create('pt_PT');

        //$this->total_socios = $this->command->ask('Total de sócios a criar ', $this->total_socios);

        $num_socio = $this->first_socio;
        for ($i=1; $i<=$this->total_socios; $i++) {
            DB::table('users')->insert($this->newFakerSocio($faker, $num_socio));
            $this->command->info("Criado User/Sócio - $i / " . $this->total_socios. " com o Nº de sócio $num_socio");
            if ($num_socio < ($this->first_socio+10)) {
                $num_socio++;
            } else {
                $num_socio+= $faker->randomElement([1,1,1,1,1,1,2,2,2,3]);
            }
        }
        $allPilots= DB::table('users')->where('tipo_socio', 'P')->pluck('id');
        foreach($allPilots as $id) {
            $randomNumber = $faker->numberBetween(0,20);
            $pilotoinfo = $this->updateSocioToPiloto($faker, $randomNumber == 20, $randomNumber < 3);
            DB::table('users')->where('id', $id)->update($pilotoinfo);
            $this->command->info("Updating Pilot Information - $id");
        }
        // Para facilitar testes:
        // Forçar user id 1 = direção e piloto normal
        // Forçar user id 2 = piloto instrutor
        // Forçar user id 3 = piloto aluno
        // Forçar user id 4 = piloto normal
        // Forçar user id 5 = Não piloto
        // Forçar user id 6 = Não piloto - Aeromodelista

        $pilotoinfo = $this->updateSocioToPiloto($faker, false, false);
        DB::table('users')->where('id', $this->first_id)->update($pilotoinfo);
        DB::table('users')->where('id', $this->first_id)->update(['direcao' => 1]);
        $this->command->info("Updated Sócio ".$this->first_id);

        $pilotoinfo = $this->updateSocioToPiloto($faker, true, false);
        DB::table('users')->where('id', $this->first_id+1)->update($pilotoinfo);
        $this->command->info("Updated Sócio " . ($this->first_id+1));

        $pilotoinfo = $this->updateSocioToPiloto($faker, false, true);
        DB::table('users')->where('id', $this->first_id+2)->update($pilotoinfo);
        $this->command->info("Updated Sócio " . ($this->first_id+2));

        $pilotoinfo = $this->updateSocioToPiloto($faker, false, false);
        DB::table('users')->where('id', $this->first_id+3)->update($pilotoinfo);
        $this->command->info("Updated Sócio " .($this->first_id+3));

        $pilotoinfo = $this->updateSocioToNaoPiloto($faker, false);
        DB::table('users')->where('id', $this->first_id+4)->update($pilotoinfo);
        $this->command->info("Updated Sócio " . ($this->first_id+4));

        $pilotoinfo = $this->updateSocioToNaoPiloto($faker, true);
        DB::table('users')->where('id', $this->first_id+5)->update($pilotoinfo);
        $this->command->info("Updated Sócio " . ($this->first_id+5));

        // Acrescentar mais 3 sócios à direção (fica com 4) - fora dos primeiros 6 - para testes
        $all= DB::table('users')->where('id', '>', $this->first_id+5)->where('ativo', 1)->pluck('id');

        for($i = 2; $i <= 4; $i++) {
            $id = $faker->unique()->randomElement($all);
            DB::table('users')->where('id', $id)->update(['direcao' => 1]);
            $this->command->info("Updated Elemento da Direcao $i => id = $id");
        }
        // Preencher fotos e documentos só se for para adicionar ficheiros
        if ($this->add_ficheiros) {
            shuffle($this->files_M);
            shuffle($this->files_F);
            // Primeiros 6 sócios (de teste) têm sempre foto
            for($i = 0; $i < 6; $i++) {
                $sexo= DB::table('users')->where('id', $this->first_id + $i)->first()->sexo;
                $file = $sexo == 'M' ? array_shift($this->files_M) : array_shift($this->files_F);
                $this->savePhotoOfUser($this->first_id + $i, $file);
            }
            $allM= DB::table('users')->where('id', '>', $this->first_id+5)->where('ativo', 1)->where('sexo', 'M')->pluck('id')->all();
            $allF= DB::table('users')->where('id', '>', $this->first_id+5)->where('ativo', 1)->where('sexo', 'F')->pluck('id')->all();
            shuffle($allM);
            shuffle($allF);
            while (!empty($allM) && !empty($this->files_M)) {
                $file = array_shift($this->files_M);
                $id = array_shift($allM);
                $this->savePhotoOfUser($id, $file);
            }
            while (!empty($allF) &&  !empty($this->files_F)) {
                $file = array_shift($this->files_F);
                $id = array_shift($allF);
                $this->savePhotoOfUser($id, $file);
            }

            // Os documentos são distribuidos pelos primeiros sócios pilotos
            foreach($allPilots as $id) {
                $piloto = DB::table('users')->where('id', $id)->first();
                $file1 = "";
                $file2 = "";
                if (!empty($this->files_PDF)) {
                    $file1 = array_shift($this->files_PDF);
                }
                if (!empty($this->files_PDF)) {
                    $file2 = array_shift($this->files_PDF);
                }
                if (empty($file1) && empty($file2)) {
                    break;
                }
                if (!empty($file1)) {
                    $this->saveDocOfUser($id, $file1, "licenca");
                }
                if (!empty($file2)) {
                    $this->saveDocOfUser($id, $file2, "certificado");
                }
            }
        }
    }

    function newFakerSocio($faker, $num_socio)
    {
        $tipo_socio = $faker->randomElement(['P','P','P', 'NP','NP','NP','NP','NP','NP','NP','NP','A']);;
        $gender = $faker->randomElement(['male','male','male','male','female']);

        $firstname = $faker->firstName($gender);
        $lastname = $faker->lastName();
        $secondname = $faker->numberBetween(1,3) == 2 ? "" : " " . $faker->firstName($gender);
        $number_middlenames = $faker->numberBetween(1,6);
        $number_middlenames = $number_middlenames == 1 ? 0 : ($number_middlenames >= 5 ? $number_middlenames-3 : 1);
        $middlenames = "";
        for($i=0; $i<$number_middlenames; $i++) {
            $middlenames.= " " . $faker->lastName();
        }
        $fullname = $firstname.$secondname.$middlenames." ".$lastname;
        $informalname = $firstname." ".$lastname;

        $createdAt = $faker->dateTimeBetween('-10 years', '-3 months');
        $email_verified_at = $faker->dateTimeBetween($createdAt, '-2 months');
        $updatedAt = $faker->dateTimeBetween($email_verified_at, '-1 months');
        $deletedAt = $faker->numberBetween(0,10) == 5 ? $faker->dateTimeBetween($updatedAt) : null;

        $activo =  !is_null($deletedAt) ? false : ($faker->numberBetween(1,6) !== 3);

        return [
            'name' => $fullname,
            'email' =>  $num_socio . "@mail.pt", // $faker->unique()->safeEmail,
            'email_verified_at' => $email_verified_at,
            'password' => bcrypt('123123123'),
            'remember_token' => str_random(10),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
            'num_socio' => $num_socio,
            'nome_informal' => $informalname,
            'sexo' => $gender == 'male' ? 'M' : 'F',
            'data_nascimento' => $faker->dateTimeBetween('-100 years', '-18 year'),
            'nif' => $faker->randomNumber($nbDigits = 9, $strict = true),
            'telefone' => $faker->phoneNumber,
            'endereco' => $faker->address,
            'tipo_socio' => $tipo_socio,
            'quota_paga' => $activo ? 1 : 0,
            'ativo' =>  $activo ? 1 : 0,
            'password_inicial' => 0,
            'direcao' => 0
        ];
    }

    function updateSocioToPiloto($faker, $instrutor, $aluno)
    {
        $tipo_socio = 'P';
        $activo =  true;
        if ($instrutor) {
            $tipoLicenca = $faker->randomElement($this->tipos_licencas_instrutor);
        } elseif ($aluno) {
            $tipoLicenca = 'ALUNO-PPL(A)';
        } else {
            $tipoLicenca = $faker->randomElement($this->tipos_licencas);
        }
        $classCertificado = $faker->randomElement($this->classes_certificados);
        if ($tipoLicenca == 'PU') {
            $classCertificado = $faker->randomElement($this->classes_certificados_UL);
        } elseif ($tipoLicenca == 'ATPL'){
            $classCertificado = 'Class 1';
        }

        return [
            'tipo_socio' => 'P',
            'quota_paga' => 1,
            'ativo' =>  1,
            'deleted_at' => null,
            'num_licenca' => $faker->randomNumber($nbDigits = 4, $strict = true), //.".".$tipoLicenca,
            'tipo_licenca' => $tipoLicenca,
            'instrutor' => $instrutor,
            'aluno' => $aluno,
            'validade_licenca' => $faker->dateTimeBetween('-1 month', '23 months'),
            'licenca_confirmada' => 1,
            'num_certificado' => 'PT.' . $faker->randomNumber($nbDigits = 5, $strict = true),
            'classe_certificado' => $classCertificado,
            'validade_certificado' => $faker->dateTimeBetween('-1 month', '23 months'),
            'certificado_confirmado' => 1,
        ];
    }

    function updateSocioToNaoPiloto($faker, $aeromodelista = false)
    {
        return [
            'tipo_socio' => $aeromodelista ? 'A' : 'NP',
            'quota_paga' => 1,
            'ativo' =>  1,
            'num_licenca' => null,
            'tipo_licenca' => null,
            'instrutor' => null,
            'aluno' => null,
            'validade_licenca' => null,
            'licenca_confirmada' => null,
            'num_certificado' => null,
            'classe_certificado' => null,
            'validade_certificado' => null,
            'certificado_confirmado' => null,
        ];
    }

    function savePhotoOfUser($id, $file)
    {
        $targetDir = storage_path('app/'.$this->photoPath);
        $newfilename = $id . "_" . uniqid(). '.jpg';
        File::copy($file, $targetDir.'/'.$newfilename);
        DB::table('users')->where('id', $id)->update(['foto_url' => $newfilename]);
        $this->command->info("Updated Foto of Sócio $id. File $file copied as $newfilename");
    }

    function saveDocOfUser($id, $file, $prefixo)
    {
        $newFileName = "$prefixo"."_".$id.".pdf";
        $this->command->info("Update Doc $newFileName of Sócio $id");
        $targetDir = storage_path('app/'.$this->docPath);
        File::copy($file, $targetDir.'/'.$newFileName);
    }
}
