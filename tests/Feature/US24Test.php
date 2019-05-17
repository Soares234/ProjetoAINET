<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US24Test extends US07Test
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->userToSimulate = $this->direcaoUser;
    }

    public function testValidacaoNumSocio()
    {
        $newdata = ["num_socio" => "xxxx"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('num_socio', 'Aceita o nº sócio inválido "xxxx"');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["num_socio" => "-1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('num_socio', 'Aceita o nº sócio negativo "-1"');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["num_socio" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('num_socio', 'Aceita nº sócio vazio');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["num_socio" => "8374853"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("num_socio", "Não aceita nº sócio válido (8374853)")
            ->assertSuccessfulOrRedirect();

        $newdata = ["num_socio" => $this->normalUser2->num_socio];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('num_socio', 'Nº sócio "'.$this->normalUser2->num_socio.'" não é unico - já está a ser usado por outro sócio');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["num_socio" => $this->normalUser->num_socio];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("num_socio", 'Nº sócio "'.$this->normalUser->num_socio.'" já estava a ser usado pelo próprio')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }


    public function testValidacaoSexo()
    {
        $newdata = ["sexo" => "M"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('sexo', 'Não foi possível guardar na tabela [users] o valor de sexo = "M"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["sexo" => "F"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('sexo', 'Não foi possível guardar na tabela [users] o valor de sexo = "F"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["sexo" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('sexo', "O campo [Sexo] é obrigatório");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["sexo" => "C"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('sexo', "Valor (C) inválido para o campo [Sexo]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoDataNascimento()
    {
        $newdata = ["data_nascimento" => "2019-10-20"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('data_nascimento', "O campo [data_nascimento] tem que ser anterior à data de hoje");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["data_nascimento" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('data_nascimento', "O campo [data_nascimento] é obrigatório");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["data_nascimento" => "123"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('data_nascimento', "O campo [data_nascimento] não é uma data");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["data_nascimento" => "2-3-2000"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('data_nascimento', "O formato do campo [data_nascimento] é errado");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["data_nascimento" => "1999-09-20"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('data_nascimento', 'Não foi possível guardar na tabela [users] o valor de data_nascimento = "1999-09-20"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoTipoSocio()
    {
        $newdata = ["tipo_socio" => "A"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('tipo_socio', 'Não foi possível guardar na tabela [users] o valor de tipo_socio = "A"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["tipo_socio" => "P"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('tipo_socio', 'Não foi possível guardar na tabela [users] o valor de tipo_socio = "P"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["tipo_socio" => "NP"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('tipo_socio', 'Não foi possível guardar na tabela [users] o valor de tipo_socio = "NP"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["tipo_socio" => ""];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('tipo_socio', "O campo [tipo_socio] é obrigatório");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["tipo_socio" => "B"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('tipo_socio',  "Valor (B) inválido para o campo [tipo_socio]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoQuotaPaga()
    {
        $newdata = ["quota_paga" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('quota_paga', 'Não foi possível guardar na tabela [users] o valor de quota_paga = "0"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["quota_paga" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('quota_paga', 'Não foi possível guardar na tabela [users] o valor de quota_paga = "1"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["quota_paga" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('quota_paga', "O campo [quota_paga] é obrigatório");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["quota_paga" => "2"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('quota_paga', "Valor (2) inválido para o campo [quota_paga]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoAtivo()
    {
        $newdata = ["ativo" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('ativo', 'Não foi possível guardar na tabela [users] o valor de ativo = "0"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["ativo" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('ativo', 'Não foi possível guardar na tabela [users] o valor de ativo = "1"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["ativo" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('ativo', "O campo [ativo] é obrigatório");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["ativo" => "2"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('ativo', "Valor (2) inválido para o campo [ativo]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoDirecao()
    {
        $newdata = ["direcao" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('direcao', 'Não foi possível guardar na tabela [users] o valor de direcao = "0"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["direcao" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('direcao', 'Não foi possível guardar na tabela [users] o valor de direcao = "1"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["direcao" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('direcao', "O campo [direcao] é obrigatório");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["direcao" => "2"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('direcao', "Valor (2) inválido para o campo [direcao]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoLicencaConfirmada()
    {
        $newdata = ["licenca_confirmada" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('licenca_confirmada', 'Não foi possível guardar na tabela [users] o valor de licenca_confirmada = "0"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["licenca_confirmada" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('licenca_confirmada', 'Não foi possível guardar na tabela [users] o valor de licenca_confirmada = "1"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["licenca_confirmada" => "2"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('licenca_confirmada', "Valor (2) inválido para o campo [licenca_confirmada]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoCertificadoConfirmado()
    {
        $newdata = ["certificado_confirmado" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('certificado_confirmado', 'Não foi possível guardar na tabela [users] o valor de certificado_confirmado = "0"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["certificado_confirmado" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('certificado_confirmado', 'Não foi possível guardar na tabela [users] o valor de certificado_confirmado = "1"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["certificado_confirmado" => "2"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('certificado_confirmado', "Valor (2) inválido para o campo [certificado_confirmado]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoNumLicenca()
    {
        $newdata = ["num_licenca" => "123456789012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("num_licenca", "Não aceita num_licenca com 30 caracteres")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["num_licenca" => "0123456789012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid("num_licenca", "Aceita num_licenca com mais do que 30 caracteres");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoTipoLicenca()
    {
        $newdata = ["tipo_licenca" => "NEWTYPE"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("tipo_licenca", "Não aceita tipo_licenca com valor válido (NEWTYPE)")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["tipo_licenca" => "XIBDAS"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid("tipo_licenca", "Aceita tipo_licenca inexistente na BD (XIBDAS)");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoNumCertificado()
    {
        $newdata = ["num_certificado" => "123456789012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("num_certificado", "Não aceita num_certificado com 30 caracteres")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["num_certificado" => "0123456789012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid("num_certificado", "Aceita num_certificado com mais do que 30 caracteres");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoClasseCertificado()
    {
        $newdata = ["classe_certificado" => "NEWCLS"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("classe_certificado", "Não aceita classe_certificado com valor válido (NEWCLS)")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["classe_certificado" => "KSWNFJS"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid("classe_certificado", "Aceita classe_certificado inexistente na BD (KSWNFJS)");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoValidadeLicenca()
    {
        $newdata = ["validade_licenca" => "123"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('validade_licenca', "O campo [validade_licenca] não é uma data");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["validade_licenca" => "2-3-2000"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('validade_licenca', "O formato do campo [validade_licenca] é errado");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["validade_licenca" => "1999-09-20"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('validade_licenca', 'Não foi possível guardar na tabela [users] o valor de validade_licenca = "1999-09-20"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoValidadeCertificado()
    {
        $newdata = ["validade_certificado" => "123"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('validade_certificado', "O campo [validade_certificado] não é uma data");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["validade_certificado" => "2-3-2000"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('validade_certificado', "O formato do campo [validade_certificado] é errado");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["validade_certificado" => "1999-09-20"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('validade_certificado', 'Não foi possível guardar na tabela [users] o valor de validade_certificado = "1999-09-20"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoAluno()
    {
        $newdata = ["aluno" => "0", "instrutor" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('aluno', 'Não foi possível guardar na tabela [users] o valor de aluno = "0"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["aluno" => "1", "instrutor" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('aluno', 'Não foi possível guardar na tabela [users] o valor de aluno = "1"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["aluno" => "2", "instrutor" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('aluno', "Valor (2) inválido para o campo [aluno]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["aluno" => "1", "instrutor" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('aluno', "Não é possível ser [aluno] e [instrutor] em simultâneo (aluno=1 e instrutor=1)");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoInstrutor()
    {
        $newdata = ["aluno" => "0", "instrutor" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('instrutor', 'Não foi possível guardar na tabela [users] o valor de instrutor = "0"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["aluno" => "0", "instrutor" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid('instrutor', 'Não foi possível guardar na tabela [users] o valor de instrutor = "1"')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["aluno" => "0", "instrutor" => "2"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('instrutor', "Valor (2) inválido para o campo [instrutor]");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["aluno" => "1", "instrutor" => "1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('instrutor', "Não é possível ser [aluno] e [instrutor] em simultâneo (aluno=1 e instrutor=1)");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testAlterarPerfilComoDirecaoComSucesso()
    {
        $newdata = [
            "id" => $this->normalUser->id,
            "num_socio" => "93485489",
            "name" => "Novo Nome Para Normal User",
            "nome_informal" => "Novo Informal 123",
            "email" => "xptop@naemail.pt",
            "nif" => "999999999",
            "telefone" => "999999999",
            "direcao" =>"1",
            "tipo_socio" => "P",
            "ativo" => "1",
            "quota_paga" => "1",
            "data_nascimento" => "1982-03-13",
            "sexo" => "M",
            "endereco" => "Av. Maia, nº 241 7200 Valongo",
            "aluno" => "0",
            "instrutor" => "0",
            "num_licenca" => "7351",
            "tipo_licenca" => "CPL(A)",
            "validade_licenca" => "2020-05-18",
            "licenca_confirmada" => "1",
            "num_certificado" => "PT.76721",
            "classe_certificado" => "Class 2",
            "certificado_confirmado" => "1"
        ];

        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid(null, 'Não foi possível guardar uma alteração válida na tabela [users]')
            ->assertSuccessfulOrRedirect();

        $this->assertDatabaseHas('users', $newdata);
    }
}
