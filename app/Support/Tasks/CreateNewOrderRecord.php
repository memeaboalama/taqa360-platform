<?php

namespace App\Support\Tasks;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Closure;
use Illuminate\Support\Facades\DB;

class CreateNewOrderRecord
{
//    public function __invoke(object $payload, Closure $next): mixed
//    {
//        $payload->order = DB::transaction(
//            callable: static fn () => MerchantOrder::query()->create(
//                attributes: [
//                    $payload->orderPayload,
//                    'customer_id'=> $payload->customer,
//                ],
//            ),
//        );
//
//        return $next($payload);
//    }
}
