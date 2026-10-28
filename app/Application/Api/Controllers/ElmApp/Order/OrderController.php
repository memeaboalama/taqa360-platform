<?php

namespace App\Application\Api\Controllers\ElmApp\Order;

use App\Application\Api\Requests\ElmApp\Order\ManuallyAssignDriverRequest;
use App\Application\Api\Requests\ElmApp\Order\UpdateOrderRequest;
use App\Application\Api\Requests\ElmApp\Order\UpdateOrderStatusRequest;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\ElmApp\Actions\Order\ManuallyAssignDriverAction;
use App\Domain\ElmApp\Actions\Order\UpdateOrderStatusAction;
use App\Domain\ElmApp\resources\OrderResource;
use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Http\Controllers\Controller;
use App\Application\Api\Requests\ElmApp\Order\FetchOrdersRequest;
use App\Support\Traits\Api\ApiResponseTrait;
use App\Domain\ElmApp\Actions\Order\FetchOrderAction;


class OrderController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    protected MerchantOrderRepository $merchantOrderRepository;

    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
    }

    public function fetch(FetchOrdersRequest $request, FetchOrderAction $fetchOrderAction)
    {
        $orders = $fetchOrderAction->handle($request->validated());
        return $this->responseData([
            'orders' => OrderResource::collection($orders)
        ]);
    }

    public function updateOrderStatus(UpdateOrderStatusRequest $request, UpdateOrderStatusAction $updateOrderStatusAction)
    {
        $data = $request->validated();
        $order_id = $data['order_id'];
        unset($data['order_id']);

        $order = $updateOrderStatusAction->handle($order_id, $data, auth()->user());

        return $this->responseData(
            code: 200,
            msg: "Order Updated successfully"
        );
    }

    public function manuallyAssignDriver(ManuallyAssignDriverRequest $request, ManuallyAssignDriverAction $manuallyAssignDriverAction, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $data = $request->validated();
        $order_id = $data['order_id'];
        unset($data['order_id']);

        $order = $fetchSingleOrderAction->getById(['id' => $order_id]);
        if (!$order)
            return $this->errorResponse('404', 'Order not found' . $order_id . '');
        if ($order->service_type_id == 2)
            return $this->errorResponse('400', 'can not assign this order type is With out Deleviry.');
        if ($order->driver_id != null) {
            return $this->errorResponse('400', 'can not assign this order already assigned');
        } elseif ($order->status == MerchantOrderStatuses::UPCOMING) {
            $manuallyAssignDriverAction->handle($order_id, $data, auth()->user());
        } else {
            return $this->errorResponse('400', 'can not assign this order');
        }

        return $this->responseData(
            code: 200,
            msg: "Driver assigned successfully"
        );
    }

    public function updateOrderInfo(UpdateOrderRequest $elmOrderRequest): \Illuminate\Http\JsonResponse
    {
        $data = $elmOrderRequest->validated();

        $order = $this->merchantOrderRepository->findWithElmOrder($data['order_id']);
        unset($data['order_id']);

        $updateData = array_filter($data, fn($value) => $value !== null);
        $order->update($updateData);

        return $this->responseData(
            code: 200,
            msg: "Order Update successfully"
        );
    }
}
