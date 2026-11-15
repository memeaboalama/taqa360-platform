<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminLoginPostTest extends TestCase
{
    public function test_admin_login_post_route_validates_credentials(): void
    {
        $response = $this->post(route('admin.login.submit'));

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email', 'password']);
    }
}
