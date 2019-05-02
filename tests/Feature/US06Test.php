<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US06Test extends USTestBase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
        $this->seedDirecaoUser();
    }

    public function testExisteRotaGetSociosEdit()
    {
        $this->actingAs($this->normalUser)->get('/socios/'. $this->normalUser->id.'/edit')
            ->assertStatus(200);
        $this->actingAs($this->direcaoUser)->get("/socios/". $this->normalUser->id.'/edit')
            ->assertStatus(200);
        $this->actingAs($this->normalUser)->get('/socios/30000002/edit')
            ->assertStatus(404);
    }

    public function testEstruturaDadosPerfilPage()
    {
        $response = $this->actingAs($this->normalUser)->get("/socios/". $this->normalUser->id.'/edit');
        $response->assertStatus(200);
        $response->assertSeeInOrder_2(['<form', 'method="POST"', '/socios/'. $this->normalUser->id, 'enctype="multipart/form-data">'],
            'Tem que incluir um formulário com o método POST e [action] que acaba em /socios/'. $this->normalUser->id . ' e que permita fazer upload de ficheiros');
        $response->assertSeeAll([
                '<input type="hidden" name="_method" value="PUT">',
                '<input type="hidden" name="_token"',
                $this->normalUser->num_socio,
                $this->normalUser->sexo == 'M' ? 'Masculino' : 'Feminino',
                $this->normalUser->tipo_socio = 'P' ? 'Piloto' : ($this->normalUser->tipo_socio = 'NP' ? 'Não Piloto' : 'Aeromodelista')
            ]);
        $response->assertSeeInOrder_2(['<input', 'name="nome_informal"', 'value="'.$this->normalUser->nome_informal.'"', '>'],
                'Campo [nome_informal] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input',' name="name"', 'value="'.$this->normalUser->name.'"', '>'],
                'Campo [name] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input',' name="email"', 'value="'.$this->normalUser->email.'"', '>'],
                'Campo [email] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input',' name="nif"', 'value="'.$this->normalUser->nif.'"', '>'],
                'Campo [nif] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<input',' name="telefone"', 'value="'.$this->normalUser->telefone.'"', '>'],
                'Campo [telefone] não incluido ou inválido');
        $response->assertSeeInOrder_2(['<textarea', 'name="endereco"', '>', $this->normalUser->endereco, '</textarea>'],
                'Campo [endereco] não incluido ou inválido');
    }

    // // Protecção de recursos será testada posterioremente
    // public function testProtecaoGetSociosEditParaAnonimo()
    // {
    //     $this->get("/socios/". $this->normalUser2->id.'/edit')
    //             ->assertUnauthorized('GET', "/socios/". $this->normalUser2->id.'/edit');
    // }

    // public function testProtecaoGetSociosEditParaOutroSocio()
    // {
    //     $this->actingAs($this->normalUser)->get("/socios/". $this->normalUser2->id.'/edit')
    //             ->assertUnauthorized('GET', "/socios/". $this->normalUser2->id.'/edit');
    // }
}
