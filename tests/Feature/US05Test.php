<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US05Test extends USTestBase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
        $this->seedDesativadoUser();
        $this->seedEmailNaoVerificadoUser();

    }

   public function testGetPasswordRouteExists()
    {
        $this->actingAs($this->normalUser)->get('/password')
            ->assertStatus(200);
    }

    public function testEstruturaChangePasswordPage()
    {
        $this->actingAs($this->normalUser)->get('/password')
                ->assertSeeInOrder_2(['<form', 'method="POST"', '/password"'],
                    'Tem que incluir um formulário com o método POST e cuja [action] que acaba em password"')
                ->assertSeeInOrder_2(['<input', 'name="old_password"', '>'],
                    'Campo [old_password] não incluido ou inválido')
                ->assertSeeInOrder_2(['<input', 'name="password"', '>'],
                    'Campo [password] não incluido ou inválido')
                ->assertSeeInOrder_2(['<input', 'name="password_confirmation"', '>'],
                    'Campo [password_confirmation] não incluido ou inválido');
    }

    public function testChangePasswordFalhaComPasswordMenorQue8Caracteres()
    {
        $this->actingAs($this->normalUser)->patch('/password',
                ['old_password' => '123123123', 'password' => '1234567',  'password_confirmation' => '1234567'])
            ->assertSessionHasErrors('password');
    }

    public function testChangePasswordFalhaComPasswordsDiferentes()
    {
        $this->actingAs($this->normalUser)->patch('/password',
                ['old_password' => '123123123', 'password' => '123456789',  'password_confirmation' => '12345678qwe'])
            ->assertSessionHasErrors('password');
    }

    public function testChangePasswordFalhaComOldPasswordErrada()
    {
        $this->actingAs($this->normalUser)->patch('/password',
                ['old_password' => 'qwertyuiop', 'password' => '123456789',  'password_confirmation' => '123456789'])
            ->assertSessionHasErrors('old_password');
    }

    public function testChangePasswordSucesso()
    {
        $this->actingAs($this->normalUser)->patch('/password',
                ['old_password' => '123123123', 'password' => '123456789',  'password_confirmation' => '123456789'])
            ->assertSessionHasNoErrors()
            ->assertSuccessfulOrRedirect();
        //Mantem o mesmo user
        $this->assertAuthenticatedAs($this->normalUser);
        //Verificar se user consegue fazer login com a nova senha.
        // 1º Logout
        // 2º Novo login (com password antiga) - tem que falhar
        // 3º Novo login (com nova password) - tem que ser sucesso

        // 1º Logout
        $this->actingAs($this->normalUser)->post('/logout')
            ->assertLocation("/");
        $this->assertGuest();

        // 2º Novo login (com password antiga) - tem que falhar
        $this->post('/login', ['email' => $this->normalUser->email, 'password' => '123123123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        // 3º Novo login (com nova password) - tem que ser sucesso
        $this->post('/login', ['email' => $this->normalUser->email, 'password' => '123456789'])
            ->assertSessionHasNoErrors()
            ->assertSuccessfulOrRedirect();
        $this->assertAuthenticatedAs($this->normalUser);
    }

    public function testProtecaoGetPasswordParaAnonimo()
    {
        $this->get('/password')
                ->assertUnauthorized('GET', '/password');
    }

}
