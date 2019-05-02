<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US04Test extends USTestBase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedNormalUsers();
    }

    public function testLogoutSucesso()
    {
        $this->actingAs($this->normalUser)->post('/logout')
            ->assertLocation("/");
        $this->assertGuest();
    }
}
