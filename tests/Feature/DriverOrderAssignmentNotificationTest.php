<?php

namespace Tests\Feature;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Domain\ElmApp\Actions\Order\ManuallyAssignDriverAction;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\Notification\Notifications\Driver\OrderAssigned;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DriverOrderAssignmentNotificationTest extends TestCase
{
    public function test_manual_assignment_sends_a_database_notification_to_the_assigned_driver(): void
    {
        Notification::fake();

        $order = new MerchantOrder();
        $order->id = 373;
        $order->code = 'ORD-373';
        $driver = new Driver();
        $driver->id = 42;
        $order->setRelation('driver', $driver);

        $repository = \Mockery::mock(MerchantOrderRepository::class);
        $repository->shouldReceive('findByCols')
            ->once()
            ->with(['id' => $order->id])
            ->andReturn(new MerchantOrder());
        $repository->shouldReceive('update')
            ->once()
            ->with($order->id, ['driver_id' => $driver->id])
            ->andReturn($order);

        $history = \Mockery::mock(LogOrderHistoryAction::class);
        $history->shouldReceive('handle')
            ->once()
            ->with(
                $order->id,
                MerchantOrderHistoryStatuses::DRIVER_ASSIGNED_MANUALLY->value,
                'Driver Assign Manually By Admin',
                \Mockery::type(MerchantOrder::class)
            );

        $action = new ManuallyAssignDriverAction($repository, $history);
        $action->handle($order->id, ['driver_id' => $driver->id], new MerchantOrder());

        Notification::assertSentTo($driver, OrderAssigned::class);
    }
}
