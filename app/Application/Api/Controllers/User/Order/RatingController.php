<?php

namespace App\Application\Api\Controllers\User\Order;

use App\Application\Api\Requests\Order\Rating\RateRequest;
use App\Domain\Employee\Resources\Order\OrderDetailsResource;
use App\Domain\MarketPlace\Actions\Api\Order\RateOrderAction;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */

    public function rateFood(RateRequest $request, RateOrderAction $orderAction): JsonResponse
    {
        $order = $orderAction->handle(['rate' => $request->rate], $request->order);
        return $this->responseData([
            'msg' => 'Thank you! for rating food',
            'order' => new OrderDetailsResource($order)
        ]);
    }

    public function rateDriver(RateRequest $request, RateOrderAction $orderAction): JsonResponse
    {
        $order = $orderAction->handle(['driver_rate' => $request->rate], $request->order);
        return $this->responseData([
            'msg' => 'Thank you! for rating driver',
            'order' => new OrderDetailsResource($order)
        ]);
    }
}
