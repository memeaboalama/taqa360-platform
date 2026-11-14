<?php

namespace Tests\Feature;

use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\AppBase\Models\Message;
use Application\Admin\Livewire\Message\Table\All as MessageTable;
use Application\Driver\Livewire\Profile\Support\Send as DriverMessageForm;
use Application\Marketplace\Livewire\Profile\Support\Send as MerchantMessageForm;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class MessageSubmissionFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'message_flow_test', 'database.connections.message_flow_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        DB::purge('message_flow_test');

        (require app_path('Domain/AppBase/Migrations/2023_08_29_151940_create_messages_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_13_130000_add_source_to_messages_table.php'))->up();

        Schema::create('merchant_orders', function ($table): void {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->unsignedTinyInteger('rate')->default(0);
        });
    }

    public function test_api_submission_is_saved_with_api_source(): void
    {
        $this->postJson('/api/v1/contact-us/send', $this->messagePayload(['source' => 'driver_portal']))
            ->assertOk();

        $this->assertDatabaseHas('messages', [
            'email' => 'sender@example.com',
            'source' => 'api',
        ]);
    }

    public function test_merchant_support_submission_is_saved_with_merchant_source(): void
    {
        $this->app->instance('_InitialMerchant', 1);
        $this->mock(FetchSingleMerchantAction::class, function ($mock): void {
            $merchant = new Merchant();
            $merchant->setAttribute('user_name', 'Merchant Sender');
            $merchant->setAttribute('email', 'merchant@example.com');
            $merchant->setAttribute('phone', '512345678');

            $mock->shouldReceive('handle')
                ->once()
                ->andReturn($merchant);
        });

        Livewire::test(MerchantMessageForm::class)
            ->set('message', 'Merchant support request')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('messages', [
            'email' => 'merchant@example.com',
            'message' => 'Merchant support request',
            'source' => 'merchant_portal',
        ]);
    }

    public function test_driver_support_submission_is_saved_with_driver_source(): void
    {
        $driver = new Driver([
            'name' => 'Driver Sender',
            'email' => 'driver@example.com',
            'phone' => '512345678',
        ]);
        $this->actingAs($driver, 'driver');

        Livewire::test(DriverMessageForm::class)
            ->set('message', 'Driver support request')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('messages', [
            'email' => 'driver@example.com',
            'message' => 'Driver support request',
            'source' => 'driver_portal',
        ]);
    }

    public function test_admin_table_status_action_updates_the_saved_message(): void
    {
        $message = Message::create($this->messagePayload([
            'email' => 'admin@example.com',
            'source' => 'api',
        ]));

        Livewire::test(MessageTable::class)
            ->call('updateStatus', 'active', $message->id);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_table_displays_the_message_source(): void
    {
        Message::create($this->messagePayload([
            'email' => 'listed@example.com',
            'source' => 'merchant_portal',
        ]));

        Livewire::test(MessageTable::class)
            ->assertSee(__('admin::home.Message Source'))
            ->assertSee(__('admin::home.message_source_merchant_portal'));
    }

    public function test_admin_table_rejects_an_invalid_message_status(): void
    {
        $message = Message::create($this->messagePayload([
            'email' => 'admin@example.com',
            'source' => 'api',
        ]));

        Livewire::test(MessageTable::class)
            ->call('updateStatus', 'deleted', $message->id)
            ->assertHasErrors();

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'status' => 'inactive',
        ]);
    }

    private function messagePayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'phone' => '512345678',
            'message' => 'Test support request',
        ], $overrides);
    }
}
