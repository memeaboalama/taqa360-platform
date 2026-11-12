<?php

namespace Tests\Feature;

use Domain\Admin\Actions\Dashboard\Auth\LoginAction;
use Livewire\Livewire;
use Mockery\MockInterface;
use Tests\TestCase;

class AdminLoginRedirectTest extends TestCase
{
    public function test_successful_admin_login_redirects_to_the_dashboard(): void
    {
        $this->mock(LoginAction::class, function (MockInterface $mock): void {
            $mock->shouldReceive('handle')->once()->andReturn(true);
        });

        Livewire::test(\Application\Admin\Livewire\Auth\Login::class)
            ->set('email', 'admin@example.com')
            ->set('password', 'valid-password')
            ->call('store')
            ->assertRedirect(route('admin:home'))
            ->assertSessionHas(
                'login_welcome',
                __('admin::home.welcome_back_msg', ['name' => 'admin@example.com'])
            );
    }
}
