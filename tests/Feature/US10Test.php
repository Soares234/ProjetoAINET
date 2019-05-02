<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US10Test extends USTestBase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
        $this->seedEmailNaoVerificadoUser();
        $this->seedDesativadoUser();
    }

    public function testExisteRotaAeronaves()
    {
        $this->actingAs($this->normalUser)->get('/aeronaves')
            ->assertStatus(200);
    }

    public function testTotalAeronaves()
    {
        $total = USTestBase::$totalAeronaves + 1; // + 1 cabeçalho
        $this->actingAs($this->normalUser)->get('/aeronaves')
                ->assertStatus(200)
                ->assertPatternCount('/<tr/u',$total,
                    "Total de linhas (elementos <tr>) deve ser = $total (1 para o cabeçalho). Nota: Podem ocorrer falhas se $total > nº de linhas por página+1");
    }


    public function testMostraAeronave()
    {
        $this->actingAs($this->normalUser)->get('/aeronaves')
                ->assertStatus(200)
                ->assertSeeAll([
                    $this->aeronave->matricula,
                    $this->aeronave->marca,
                    $this->aeronave->modelo,
                    $this->aeronave->num_lugares,
                    (integer)$this->aeronave->preco_hora,
                ]);
        // Total de horas não é verificados, porque o formato pode (deve) ser ajustado.
        // Exemplo: 11 254,4 horas
        // O mesmo se aplica ao preço hora, mas como este não tem mais de 3 digitos, há sempre uma parte da string que é igual
        // Exemplo: 130 €
    }

    public function testNaoMostraAeronaveSoftdeleted()
    {
        $this->actingAs($this->normalUser)->get('/aeronaves')
                ->assertStatus(200)
                ->assertDontSeeAll([
                    $this->aeronaveDeleted->matricula
                ]);
    }

    // // Protecção de recursos será testada posterioremente
    // public function testProtecaoGetAeronavesParaAnonimo()
    // {
    //     $this->get('/aeronaves')
    //             ->assertUnauthorized('GET', '/aeronaves');
    // }

    // public function testProtecaoGetAeronavesParaUserComEmailNaoVerificado()
    // {
    //     $this->actingAs($this->emailNaoVerificadoUser)->get('/aeronaves')
    //             ->assertUnauthorized('GET', '/aeronaves');
    // }

    // public function testProtecaoGetAeronavesPasswordParaSocioDesativado()
    // {
    //     $this->actingAs($this->desativadoUser)->get('/aeronaves')
    //             ->assertUnauthorized('GET', '/aeronaves');
    // }
}
