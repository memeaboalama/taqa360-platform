<?php

namespace App\Domain\Notification\Actions;

use App\Domain\AppBase\Models\NotificationNew;
use App\Domain\User\Models\User;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class FetchNotificationFeedAction
{
    private const AUDIENCES = [
        User::class,
        Driver::class,
        MerchantEmployee::class,
    ];

    public function handle(string $audience): Collection
    {
        if (! in_array($audience, self::AUDIENCES, true)) {
            throw new InvalidArgumentException('Unsupported notification audience.');
        }

        return NotificationNew::query()
            ->where('device_type', $audience)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get(['id', 'title', 'body', 'created_at']);
    }
}
