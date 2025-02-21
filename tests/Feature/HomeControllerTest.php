<?php

namespace Tests\Feature;

use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class HomeControllerTest extends TestCase
{
    public function test_can_get_home_page()
    {
        $this->call('GET', '/')
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Home')
            );
    }

}
