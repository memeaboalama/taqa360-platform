<?php

namespace App\Domain\AppBase\Resource;

use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\User\Models\User;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $deliveryStatus = $this->delivery_status ?? NotificationNew::DELIVERY_UNKNOWN;

        return [
            'id' => (string) $this->id,
            'type' => match ($deliveryStatus) {
                NotificationNew::DELIVERY_SENT => 'success',
                NotificationNew::DELIVERY_FAILED,
                NotificationNew::DELIVERY_NO_RECIPIENTS => 'error',
                NotificationNew::DELIVERY_PENDING,
                NotificationNew::DELIVERY_PARTIAL => 'warning',
                default => 'info',
            },
            'title' => $this->title,
            'message' => $this->body,
            'timestamp' => $this->created_at?->toIso8601String(),
            'is_dismissible' => false,
            'audience' => match ($this->device_type) {
                User::class => 'user',
                Driver::class => 'driver',
                MerchantEmployee::class => 'merchant',
                default => 'unknown',
            },
            'delivery_status' => $deliveryStatus,
            'sent_count' => (int) ($this->sent_count ?? 0),
            'failed_count' => (int) ($this->failed_count ?? 0),
        ];
    }
}
