<?php

namespace App\Application\Api\Controllers\User\Order;

use App\Application\Api\Requests\Order\ElmOrderRequest;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\resources\Order\ElmOrderResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\MarketPlace\Models\ElmOrder;
use Illuminate\Http\Request;


class ElmOrderController extends Controller
{
    use ApiResponseTrait;

    public function update(ElmOrderRequest $elmOrderRequest): \Illuminate\Http\JsonResponse
    {
        $data = $elmOrderRequest->validated();
        $order = MerchantOrder::find($data['order_id']);

        $updateData = array_filter($data ,fn($value) => $value !== null);
        $order->elmOrder()->update($updateData);
        $order->refresh();

        return $this->responseData([
            'elm_order_info' => new ElmOrderResource($order),
        ], 200);
    }

    public function fetch(int $id,Request $request): \Illuminate\Http\JsonResponse
    {
        $order = MerchantOrder::find($id);
        
        return $this->responseData([
            'elm_order_info' => new ElmOrderResource($order),
        ], 200);

    }
}
