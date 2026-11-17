<?php

namespace App\Domain\Admin\Actions\Dashboard\Notification;

use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\Notification\Actions\SendBroadcastPushNotificationAction;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendNotificationAction
{
    public function __construct(private SendBroadcastPushNotificationAction $pushNotification)
    {
    }

    public function handle(array $data): NotificationNew
    {
        $notification = NotificationNew::create($data + [
            'channel' => 'broadcast',
            'delivery_status' => NotificationNew::DELIVERY_PENDING,
            'sent_count' => 0,
            'failed_count' => 0,
        ]);

        try {
            $delivery = $this->pushNotification->handle($data + [
                'notification_id' => $notification->id,
            ]);

            $status = match (true) {
                $delivery['attempted'] === 0 => NotificationNew::DELIVERY_NO_RECIPIENTS,
                $delivery['sent'] === 0 => NotificationNew::DELIVERY_FAILED,
                $delivery['failed'] > 0 => NotificationNew::DELIVERY_PARTIAL,
                default => NotificationNew::DELIVERY_SENT,
            };

            $notification->update([
                'delivery_status' => $status,
                'sent_count' => $delivery['sent'],
                'failed_count' => $delivery['failed'],
            ]);
        } catch (Throwable $exception) {
            $notification->update(['delivery_status' => NotificationNew::DELIVERY_FAILED]);
            Log::error('Admin notification delivery failed.', [
                'notification_id' => $notification->id,
                'exception' => $exception,
            ]);

            throw $exception;
        }

        return $notification->refresh();
    }
}
