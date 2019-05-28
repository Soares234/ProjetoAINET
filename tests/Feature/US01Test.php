<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US01Test extends USTestBase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
        $this->seedSoftDeletedUser();
    }

    public function testExisteRotaGetLogin()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function testEstruturaLoginPage()
    {
        $this->get('/login')
            ->assertSeeInOrder_2(['<form', 'method="POST"', '/login"'], 'Tem que incluir um formulário com o método POST e cuja [action] que acaba em login"')
            ->assertSeeAll(['name="email"','name="password"']);
    }

    public function testLoginFalhaComMailInvalido()
    {
        $response = $this->post('/login', ['email' => 'a@a.pt', 'password' => '123123123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testLoginFalhaComPasswordInvalida()
    {
        $this->post('/login', ['email' => $this->normalUser->email, 'password' => '123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testLoginSucessoComCredenciaisValidas()
    {
        $this->post('/login', ['email' => $this->normalUser->email, 'password' => '123123123'])
            ->assertSessionHasNoErrors()
            ->assertSuccessfulOrRedirect();
        $this->assertAuthenticatedAs($this->normalUser);
    }

    public function testLoginFalhaComCredenciasUserSoftDeleted()
    {
        $response = $this->post('/login', ['email' => $this->softDeletedUser->email, 'password' => '123123123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

}
