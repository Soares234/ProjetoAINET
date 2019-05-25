<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Auth\Notifications\ResetPassword;


class US03Test extends USTestBase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
    }

    public function testExisteRotaPasswordReset()
    {
        $response = $this->get('/password/reset');
        $response->assertStatus(200);
    }

    public function testEstruturaPasswordResetPage()
    {
        $this->get('/password/reset')
            ->assertSeeInOrder_2(['<form', 'method="POST"', 'password/email"'], 'Tem que incluir um formulário com o método POST e cuja [action] que acaba em password/email"')
            ->assertSeeAll(['<input type="hidden" name="_token"', 'name="email"']);
    }

    public function testFalhaComEmailInvalido()
    {
        $this->post('/password/email', ['email' => 'abcxsd'])
            ->assertSessionHasErrors('email');
    }

    public function testFalhaComEmailInexistente()
    {
        $this->post('/password/email', ['email' => 'naoexiste.dsfdfssdfweds@mail.as'])
             ->assertSessionHasErrors('email');
    }

    public function testSucessoEmailValidoEnviado()
    {
        Notification::fake();
        $this->post('/password/email', ['email' => $this->normalUser->email])
            ->assertSessionHasNoErrors()
            ->assertSuccessfulOrRedirect();

        Notification::assertSentTo($this->normalUser, ResetPassword::class);
    }

}
