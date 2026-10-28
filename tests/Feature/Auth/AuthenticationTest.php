<?php

namespace Tests\Feature\Auth;

use App\Domain\User\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_are_redirected_back_to_checkout_after_login(): void
    {
        $user = User::factory()->create();
        $checkoutUrl = 'http://localhost/ar/checkout';

        $response = $this->from('/ar/checkout')->post('/ar/login', [
            'email' => $user->email,
            'password' => 'password',
            'redirect' => $checkoutUrl,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect($checkoutUrl);
    }
}
