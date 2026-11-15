<?php

namespace Tests\Unit;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MerchantOrderHistoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'merchant_order_history_test', 'database.connections.merchant_order_history_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        DB::purge('merchant_order_history_test');

        Schema::connection('merchant_order_history_test')->create('merchant_order_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('action');
            $table->string('reason')->nullable();
            $table->string('changeable_type');
            $table->unsignedBigInteger('changeable_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function test_merchant_ready_transition_is_written_to_order_history(): void
    {
        $merchant = new Merchant();
        $merchant->id = 17;

        (new LogOrderHistoryAction())->handle(
            42,
            MerchantOrderHistoryStatuses::MARKED_READY->value,
            'Merchant marked order as ready',
            $merchant
        );

        $this->assertDatabaseHas('merchant_order_history', [
            'order_id' => 42,
            'action' => MerchantOrderHistoryStatuses::MARKED_READY->value,
            'changeable_type' => $merchant->getMorphClass(),
            'changeable_id' => 17,
        ]);
    }
}
