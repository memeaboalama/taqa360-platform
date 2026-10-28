<?php

namespace App\Domain\MarketPlace\Actions\Api\Order;

use App\Domain\MarketPlace\Models\MerchantOrderHistory;
use Illuminate\Database\Eloquent\Model;

class LogOrderHistoryAction
{
   
    public function handle($orderId, string $action, string $message = null, ?Model $user = null)
    {
        $user = $user ?? auth()->user() ?? auth('merchant')->user() ?? auth('merchant_employee')->user();
        if ($user && method_exists($user, 'logOrderHistory')) {
            $user->logOrderHistory()->create([
                'order_id' => $orderId,
                'action' => $action,
                'reason' => $message,
            ]);
        }
    }
}
