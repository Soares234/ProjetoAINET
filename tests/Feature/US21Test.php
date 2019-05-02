<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US21Test extends USTestBase
{
    protected $userToSimulate;
    protected $urlGet;
    protected $urlPut;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
        $this->seedEmailNaoVerificadoUser();
        $this->seedDesativadoUser();
        $this->seedDirecaoUser();
        $this->urlGet = '/aeronaves/'. $this->aeronave->matricula.'/edit';
        $this->urlPut = '/aeronaves/'. $this->aeronave->matricula;
        $this->userToSimulate = $this->direcaoUser;
    }

    public function testExisteRotaGetAeronaveEdit()
    {
        $this->actingAs($this->userToSimulate)->get($this->urlGet)
            ->assertSuccessfulOrRedirect();
        $this->actingAs($this->userToSimulate)->get('/aeronaves/'. $this->aeronaveDeleted->matricula.'/edit')
            ->assertStatus(404);
        $this->actingAs($this->userToSimulate)->get('/aeronaves/XK-SWQW34/edit')
            ->assertStatus(404);
    }

    public function testEstruturaDadosAeronavePage()
    {
        $response = $this->actingAs($this->userToSimulate)->get($this->urlGet);
        $response->assertStatus(200);
        $response->assertSeeInOrder_2(['<form', 'method="POST"', '/aeronaves/'. $this->aeronave->matricula],
            'Tem que incluir um formulário com o método POST e [action] que acaba em /aeronaves/'. $this->aeronave->matricula);
        $response->assertSeeAll([
                '<input type="hidden" name="_method" value="PUT">',
                '<input type="hidden" name="_token"',
                $this->aeronave->matricula
            ]);
        $response->assertSeeInOrder_2(['<input', 'name="marca"', 'value="'. $this->aeronave->marca .'"', '>'],
                'Campo [marca] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input', 'name="modelo"', 'value="'. $this->aeronave->modelo .'"', '>'],
                'Campo [modelo] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input', 'name="num_lugares"', 'value="'. $this->aeronave->num_lugares .'"', '>'],
                'Campo [num_lugares] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input', 'name="conta_horas"', 'value="'. $this->aeronave->conta_horas .'"', '>'],
                'Campo [conta_horas] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input', 'name="preco_hora"', 'value="', (integer)$this->aeronave->preco_hora, '>'],
                'Campo [preco_hora] não incluido ou inválido');
    }


    public function testValidacaoMatricula()
    {
        $newdata = ["matricula" => null];
        $requestData = array_merge($this->getRequestArrayFromAeronave($this->aeronave), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('matricula', 'Aceita valores vazios');

        $newdata = ["matricula" => "N12345678"];
        $requestData = array_merge($this->getRequestArrayFromAeronave($this->aeronave), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('matricula', 'Matricula aceita valores com mais de 8 caracteres');
        $this->assertDatabaseMissing('aeronaves', $newdata);

        $newdata = ["matricula" => "N123123"];
        $requestData = array_merge($this->getRequestArrayFromAeronave($this->aeronave), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('matricula', 'Valor da matricula não pode ser alterada');
        $this->assertDatabaseMissing('aeronaves', $newdata);
    }

    public function testValidacaoMarca()
    {
        $newdata = ["marca" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('marca', 'Aceita valores vazios');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["marca" => "X1234567890123456789012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('marca', 'Aceita valores da Marca com mais de 40 caracteres');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));
    }


    public function testValidacaoModelo()
    {
        $newdata = ["modelo" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('modelo', 'Aceita valores vazios');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["modelo" => "X1234567890123456789012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('modelo', 'Aceita valores da modelo com mais de 40 caracteres');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));
    }

    public function testValidacaoNumLugares()
    {
        $newdata = ["num_lugares" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('num_lugares', 'Aceita valores vazios');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["num_lugares" => "A1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('num_lugares', 'Aceita valores não inteiros');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["num_lugares" => "12.23"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('num_lugares', 'Aceita valores não inteiros');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["num_lugares" => "-1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('num_lugares', 'Aceita valores negativos');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["num_lugares" => "0"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('num_lugares', 'Aceita valor zero');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));
    }

    public function testValidacaoContaHoras()
    {
        $newdata = ["conta_horas" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('conta_horas', 'Aceita valores vazios');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["conta_horas" => "A1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('conta_horas', 'Aceita valores não inteiros');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["conta_horas" => "12.23"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('conta_horas', 'Aceita valores não inteiros');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["conta_horas" => "-1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('conta_horas', 'Aceita valores negativos');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));
    }

   public function testValidacaoPrecoHora()
    {
        $newdata = ["preco_hora" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('preco_hora', 'Aceita valores vazios');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["preco_hora" => "A1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('preco_hora', 'Aceita valores não númericos');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));

        $newdata = ["preco_hora" => "-1"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put($this->urlPut, $requestData)
            ->assertInvalid('preco_hora', 'Aceita valores negativos');
        $this->assertDatabaseMissing('aeronaves', array_merge(["matricula" => $this->aeronave->matricula], $newdata));
    }

    // Protecção de recursos será testada posterioremente
    // public function testProtecaoGetAeronaveParaAnonimo()
    // {
    //     $this->get($this->urlGet)
    //             ->assertUnauthorized('GET', $this->urlGet);
    // }

    // public function testProtecaoGetAeronaveParaUserNormal()
    // {
    //     $this->actingAs($this->normalUser)->get($this->urlGet)
    //             ->assertUnauthorized('GET', $this->urlGet);
    // }

    // public function testProtecaoGetAeronaveParaUserComEmailNaoVerificado()
    // {
    //     $this->actingAs($this->emailNaoVerificadoUser)->get($this->urlGet)
    //             ->assertUnauthorized('GET', $this->urlGet);
    // }

    // public function testProtecaoGetAeronavePasswordParaSocioDesativado()
    // {
    //     $this->actingAs($this->desativadoUser)->get($this->urlGet)
    //             ->assertUnauthorized('GET', $this->urlGet);
    // }
}
