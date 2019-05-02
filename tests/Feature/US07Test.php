<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US07Test extends USTestBase
{
    protected $userToSimulate;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
        $this->seedDirecaoUser();
        $this->userToSimulate = $this->normalUser;
    }

    public function testValidacaoNome()
    {
        $newdata = ["name" => "Abc 123"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('name', 'Aceita números');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["name" => "Abc '#€$&/^|\\±"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('name', 'Aceita simbolos');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["name" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('name', 'Aceita valores vazios');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoNomeAceitaLetrasPortugues()
    {
        $newdata = ["name" => "Abc çÇ áÁéÉíÍóÓúÚ àÀèÈìÌòÒùÙ ãÃõÕ âÂêÊîÎôÔûÛ"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("name", "Não aceita letras do alfabeto 'Português' (por exemplo, á, ç ou ã)")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoNomeInformal()
    {
        $newdata = ["nome_informal" => "1234567890123456789012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("nome_informal", "Não aceita nome_informal com 40 caracteres e com números (nome_informal deve aceitar número)")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["nome_informal" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('nome_informal', 'Aceita valores vazios');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["nome_informal" => "1234567890123456789012345678901234567890Z"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('nome_informal');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoNif()
    {
        $newdata = ["nif" => "888888888"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("nif", "Não aceita NIF com 9 caracteres")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["nif" => "8888888889"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('nif', 'Aceita nif com mais do que 9 caracteres');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoTelefone()
    {
        $newdata = ["telefone" => "12345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("telefone", "Não aceita telefone com 20 caracteres")
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["telefone" => "012345678901234567890"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid("telefone", "Aceita telefone com mais do que 20 caracteres");
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testValidacaoEmail()
    {
        $newdata = ["email" => "xxxx"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('email', 'Aceita o email "xxxx"');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["email" => null];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('email', 'Aceita email vazio');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["email" => "asddsfgd@asas.pt"];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("email", "Não aceita e-mail válido (asddsfgd@asas.pt)")
            ->assertSuccessfulOrRedirect();

        $newdata = ["email" => $this->normalUser2->email];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertInvalid('email', 'Email "'.$this->normalUser2->email.'" não é unico - já está a ser usado por outro sócio');
        $this->assertDatabaseMissing('users', array_merge(["id" => $this->normalUser->id], $newdata));

        $newdata = ["email" => $this->normalUser->email];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid("email", 'Email "'.$this->normalUser->email.'" já estava a ser usado pelo próprio')
            ->assertSuccessfulOrRedirect();
        $this->assertDatabaseHas('users', array_merge(["id" => $this->normalUser->id], $newdata));
    }

    public function testAlterarPerfilSimplesComSucesso()
    {
        $newdata = [
            "id" => $this->normalUser->id,
            "name" => "Novo Nome Para Normal User",
            "nome_informal" => "Novo Informal 123",
            "email" => "xptop@naemail.pt",
            "nif" => "999999999",
            "telefone" => "999999999"
        ];
        $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
        $this->actingAs($this->userToSimulate)->put('/socios/'. $this->normalUser->id, $requestData)
            ->assertValid(null, 'Não foi possível guardar uma alteração válida na tabela [users]')
            ->assertSuccessfulOrRedirect();

        $this->assertDatabaseHas('users', $newdata);
    }

    // TODO: FALTA TRATAR DO UPLOAD DA IMAGEM / FOTO
    // TODO: FALTA PROIBIR ALTERAÇÕES DE OUTROS CAMPOS DO PERFIL

    // // Protecção de recursos será testada posterioremente
    // public function testProtecaoAlterarSociosParaAnonimos()
    // {
    //     $newdata = ["nome_informal" => "Valor Válido"];
    //     $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
    //     $this->put('/socios/'. $this->normalUser->id, $requestData)
    //         ->assertUnauthorized('PUT', "/socios/". $this->normalUser->id, 'Anónimos conseguem alterar dados do user!');
    // }

    // public function testProtecaoAlterarSociosParaOutroSocio()
    // {
    //     $newdata = ["nome_informal" => "Valor Válido"];
    //     $requestData = array_merge($this->getRequestArrayFromUser($this->normalUser), $newdata);
    //     $this->actingAs($this->normalUser2)->put('/socios/'. $this->normalUser->id, $requestData)
    //         ->assertUnauthorized('PUT', "/socios/". $this->normalUser->id, 'Sócios normais conseguem alterar dados de outros sócios!');
    // }
}
