<?php

namespace Tests\Feature\Marketplace;

use Tests\TestCase;

class MerchantLoginTest extends TestCase
{
    public function test_login_post_route_validates_credentials(): void
    {
        $response = $this->post(route('marketplace:login.submit'));

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email', 'password']);
    }
}
