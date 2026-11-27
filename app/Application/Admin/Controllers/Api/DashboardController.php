<?php

namespace App\Application\Admin\Controllers\Api;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    /**
     * Return the count of pending marketplace/product orders for the SPA admin dashboard.
     */
    public function pendingProductOrdersCount(): JsonResponse
    {
        $count = DB::table('merchant_orders')
            ->where('status', MerchantOrderStatuses::PENDING->value)
            ->count();

        return $this->responseData(data: [
            'count' => $count,
        ]);
    }
}
