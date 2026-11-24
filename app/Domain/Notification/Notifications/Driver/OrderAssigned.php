<?php

namespace App\Domain\Notification\Notifications\Driver;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderAssigned extends Notification
{
    use Queueable;

    public function __construct(private MerchantOrder $order)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->code,
            'title' => __('driver::home.order_assigned_notification_title'),
            'body' => __('driver::home.order_assigned_notification_body', [
                'code' => $this->order->code,
            ]),
        ];
    }
}
