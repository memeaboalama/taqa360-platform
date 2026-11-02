<?php

namespace Tests\Feature\Wallet;

use App\Domain\MarketPlace\Actions\Api\Wallet\ApplyWalletCouponAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletDepositAction;
use App\Domain\User\Models\User;
use Application\Marketplace\Controllers\WalletDepositController;
use Tests\TestCase;

class WalletDepositConfigurationTest extends TestCase
{
    public function test_deposit_is_not_created_when_moyasar_is_not_configured(): void
    {
        config([
            'services.moyasar.key' => null,
            'services.moyasar.secret' => null,
        ]);

        $depositAction = $this->mock(WalletDepositAction::class);
        $depositAction->shouldNotReceive('create');
        $this->mock(ApplyWalletCouponAction::class);

        $this->actingAs(User::factory()->make(['id' => 123456]))
            ->from(route('account.wallet.deposit'))
            ->post(route('account.wallet.deposit.process'), ['amount' => 10])
            ->assertRedirect(route('account.wallet.deposit'))
            ->assertSessionHas(
                'error',
                __('ecommerce::ecommerce.wallet_payment_not_configured')
            );
    }

    public function test_existing_deposit_link_shows_configuration_message_instead_of_invalid_key(): void
    {
        config([
            'services.moyasar.key' => null,
            'services.moyasar.secret' => null,
        ]);

        $this->actingAs(User::factory()->make(['id' => 123456]));
        $depositAction = $this->mock(WalletDepositAction::class);
        $depositAction->shouldNotReceive('handle');

        $response = app(WalletDepositController::class)->paymentIframe('ar', 12, $depositAction);

        $this->assertSame(route('account.wallet.deposit'), $response->getTargetUrl());
        $this->assertSame(
            __('ecommerce::ecommerce.wallet_payment_not_configured'),
            session('error')
        );
    }
}
