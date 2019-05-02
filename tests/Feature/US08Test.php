<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US08Test extends USTestBase
{
    protected $userToSimulate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
        $this->seedDesativadoUser();
        $this->seedEmailNaoVerificadoUser();
        $this->userToSimulate = $this->normalUser;
    }

    public function testExisteRotaSocios()
    {
        $this->actingAs($this->userToSimulate)->get('/socios')
            ->assertStatus(200);
    }

    public function testTotalSocios()
    {
        $total = USTestBase::$totalAtivos + 4; // 2 normal users + 1 user não verificado + 1 cabeçalho
        $this->actingAs($this->userToSimulate)->get('/socios')
                ->assertStatus(200)
                ->assertPatternCount('/<tr/u',$total,
                    "Total de linhas (elementos <tr>) deve ser = $total (1 para o cabeçalho). Nota: Podem ocorrer falhas se $total > nº de linhas por página+1");
    }

    public function testMostraSocioAtivo()
    {
        $this->actingAs($this->userToSimulate)->get('/socios')
                ->assertStatus(200)
                ->assertSeeAll([
                    $this->normalUser->nome_informal,
                    $this->normalUser->email
                ]);
    }

    public function testNaoMostraSocioDesativado()
    {
        $this->actingAs($this->userToSimulate)->get('/socios')
                ->assertStatus(200)
                ->assertDontSeeAll([
                    $this->desativadoUser->nome_informal,
                    $this->desativadoUser->email
                ]);
    }

    public function testMostraCamposIncluindLicenca()
    {
        $this->seedPilotoUser();
        $this->actingAs($this->userToSimulate)->get('/socios')
                ->assertStatus(200)
                ->assertSeeAll([
                    $this->pilotoUser->nome_informal,
                    $this->pilotoUser->email,
                    $this->pilotoUser->telefone,
                    $this->pilotoUser->num_socio,
                    $this->pilotoUser->num_licenca,
                ]);
    }

    // // Protecção de recursos será testada posterioremente
    // public function testProtecaoSociosParaAnonimo()
    // {
    //     $this->get('/socios')
    //             ->assertUnauthorized('GET', '/socios');
    // }

    // public function testProtecaoSociosParaUserComEmailNaoVerificado()
    // {
    //     $this->actingAs($this->emailNaoVerificadoUser)->get('/socios')
    //             ->assertUnauthorized('GET', '/socios');
    // }

    // public function testProtecaoSociosPasswordParaSocioDesativado()
    // {
    //     $this->actingAs($this->desativadoUser)->get('/socios')
    //             ->assertUnauthorized('GET', '/socios');
    // }


}
