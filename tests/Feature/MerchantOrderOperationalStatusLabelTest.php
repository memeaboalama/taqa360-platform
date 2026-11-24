<?php

namespace Tests\Feature;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Tests\TestCase;

class MerchantOrderOperationalStatusLabelTest extends TestCase
{
    public function test_confirmed_delivery_order_without_driver_is_labelled_as_awaiting_driver(): void
    {
        $order = new MerchantOrder();
        $order->status = MerchantOrderStatuses::UPCOMING;
        $order->service_type_id = 1;

        $this->assertSame(__('lang.order_status.awaiting_driver'), $order->operational_status_label);
    }

    public function test_confirmed_delivery_order_with_a_driver_is_labelled_as_awaiting_driver_acceptance(): void
    {
        $assignedOrder = new MerchantOrder();
        $assignedOrder->status = MerchantOrderStatuses::UPCOMING;
        $assignedOrder->service_type_id = 1;
        $assignedOrder->driver_id = 42;

        $this->assertSame(
            __('lang.order_status.assigned_driver_awaiting_acceptance'),
            $assignedOrder->operational_status_label
        );
    }

    public function test_confirmed_pickup_order_keeps_the_confirmed_label(): void
    {
        $pickupOrder = new MerchantOrder();
        $pickupOrder->status = MerchantOrderStatuses::UPCOMING;
        $pickupOrder->service_type_id = 2;

        $this->assertSame($pickupOrder->status->trans(), $pickupOrder->operational_status_label);
    }
}
