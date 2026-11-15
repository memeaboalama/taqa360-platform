<?php

namespace Tests\Feature\Driver;

use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use Application\Driver\Livewire\Auth\Password\Reset;
use Application\Driver\Livewire\Auth\Password\Verify;
use Domain\Driver\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_driver_otp_authorizes_password_reset(): void
    {
        Driver::query()->create([
            'phone' => '500000001',
            'password' => 'old-password',
        ]);
        Session::put('driver_password_reset_phone', '500000001');

        $this->mock(CheckOtpAction::class)
            ->shouldReceive('handle')
            ->once()
            ->andReturn(true);
        $this->mock(InvalidateOldOtpAction::class)
            ->shouldReceive('handle')
            ->once();

        Livewire::test(Verify::class)
            ->set('otp', '1234')
            ->call('verify')
            ->assertRedirect(route('driver:resetPassword'));

        $this->assertTrue(Session::get('driver_password_reset_verified'));
    }

    public function test_reset_screen_requires_verified_driver_otp(): void
    {
        Session::put('driver_password_reset_phone', '500000001');

        Livewire::test(Reset::class)
            ->assertRedirect(route('driver:verifyOTP'));
    }

    public function test_verified_driver_can_reset_password_and_recovery_session_is_cleared(): void
    {
        $driver = Driver::query()->create([
            'phone' => '500000001',
            'password' => 'old-password',
        ]);
        Session::put('driver_password_reset_phone', '500000001');
        Session::put('driver_password_reset_verified', true);

        Livewire::test(Reset::class)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword')
            ->assertRedirect(\App\Providers\RouteServiceProvider::DRIVER_HOME);

        $this->assertTrue(Hash::check('new-password', $driver->fresh()->password));
        $this->assertSame($driver->id, Auth::guard('driver')->id());
        $this->assertFalse(Session::has('driver_password_reset_phone'));
        $this->assertFalse(Session::has('driver_password_reset_verified'));
    }
}
