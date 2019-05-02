<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class US00Test extends USTestBase
{
   public function testHomePageExists()
    {
        $this->get('/')->assertSuccessfulOrRedirect();
    }
}
