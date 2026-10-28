<?php

namespace App\Application\Api\Controllers\Driver\Order;


use App\Application\Api\Requests\Driver\Order\DetailsOrderRequest;
use App\Application\Api\Requests\Driver\Order\UpdateOrderRequest;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;

use App\Domain\Driver\Jobs\SearchForDriverJob;
use App\Domain\Driver\Resources\Order\OrderResource;
use App\Domain\ElmApp\Actions\Order\UpdateOrderStatusAction;
use App\Domain\Employee\Actions\Order\UpdateElmStatusAction;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\MerchantOrderHistory;

use App\Domain\Notification\Jobs\User\SendNotifyOrderToUser;
use App\Http\Controllers\Controller;
use App\Support\Enum\Elm\ElmOrderStatuses;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\TempOrder;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    use ApiResponseTrait;

    private LogOrderHistoryAction $logOrderHistoryAction;

    public function __construct(LogOrderHistoryAction $logOrderHistoryAction)
    {
        $this->logOrderHistoryAction = $logOrderHistoryAction;
    }
    /**
     * Display a listing of the resource.
     */


    public function index(): \Illuminate\Http\JsonResponse
    {

        $driver_auth_user = auth()->user();
        $driver_auth_id = $driver_auth_user?->id ?? auth()->id();
        // Log::channel('console')->error("driver : " . json_encode($driver_auth_user) . "rejected ");
        // $driver_auth_user = auth('sanctum')->guard('driver-api')->user();
        // $driver_auth_id = $driver_auth_user?->id ?? auth('sanctum')->guard('driver-api')->id();
        // $driver_auth_id = auth('sanctum')->guard('driver-api')->id();
        $temp_order = TempOrder::query()
            ->where('driver_id', $driver_auth_id)
            ->where('has_accept', false)
            ->pluck('order_id')->toArray();
        ////////

        $order = MerchantOrder::query()
            ->where("driver_id", $driver_auth_id)
            // ->where('status', MerchantOrderStatuses::UPCOMING->value)
            ->orWhere(function ($q) use ($temp_order) {
                $q->whereNull('driver_id')->whereIn('id', $temp_order);
                //->where('status', MerchantOrderStatuses::UPCOMING->value)
            })
            ->orderBy('created_at', 'DESC')->get();
        // Log::channel('console')->error("driver : " . json_encode($temp_order) . "rejected ");
        // Log::channel('console')->error("driver : " . json_encode($order) . "rejected ");
        return $this->responseData(["order" => OrderResource::collection($order)]);
    }


    public function show(DetailsOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        return $this->responseData(["order" => new OrderResource(MerchantOrder::query()->find($data["order_id"]))]);
    }

    public function update(UpdateOrderRequest $request, UpdateOrderStatusAction $updateOrderStatusAction): \Illuminate\Http\JsonResponse
    {
        // Log::info("Driver OrderController UpdateOrderRequest ");
        // Log::info($request);
        $data = $request->validated();
        $driver_auth_user = auth()->user();
        $driver_auth_id = $driver_auth_user?->id ?? auth()->id();
        // $driver_auth_user = auth('sanctum')->guard('driver-api')->user();
        // $driver_auth_id = $driver_auth_user?->id ?? auth('sanctum')->guard('driver-api')->id();
        if (!($driver_auth_user && $driver_auth_user instanceof \Domain\Driver\Models\Driver))
            return $this->errorResponse('401', 'authorized driver');

        // Log::info(auth()->id());
        $order = MerchantOrder::find($data["order_id"]);
        // Log::info("Driver OrderController UpdateOrderRequest data");
        // Log::info($data);
        // Log::info("Driver OrderController UpdateOrderRequest Order");
        // Log::info($order);
        if ($data["status"] == "accept" && !$order->driver_id) {
            // $assigned = MerchantOrder::where('driver_id', auth()->id())->where('id', '<>', $data["order_id"])
            //     ->whereIn('status',

            //         [
            //             MerchantOrderStatuses::UPCOMING->value,
            //             MerchantOrderStatuses::ON_WAY->value,
            //             MerchantOrderStatuses::CURRENT->value,
            //             MerchantOrderStatuses::READY->value

            //         ]
            //     )->get();


            // if ($assigned->isNotEmpty())
            //     return $this->errorResponse('400', 'can not assign this order you have another one now');

            if ($order->driver_id != $driver_auth_id && $order->driver_id != null)
                return $this->errorResponse('400', 'can not assign this order is taken by another driver');
        } else if ($data["status"] == "reject" && !$order->driver_id) {
            Log::channel()->info("driver : " . json_encode($driver_auth_user) . "rejected ");
        }


        if (($data["status"] == "accept" || $data["status"] == "reject") && $order->driver_id) {
            return $this->errorResponse('400', 'can not assign this order already assigned');
        }


        if ($data["status"] == "accept" && $order->status == MerchantOrderStatuses::UPCOMING) {
            $order->update([
                'driver_id' => $driver_auth_id,
                'status' => MerchantOrderStatuses::CURRENT->value,
                'commission_from_driver' => auth()->user()->taken_commission
            ]);
            TempOrder::query()
                ->where('driver_id', $driver_auth_id)
                ->where('order_id', $data["order_id"])
                ->where('has_accept', false)->update(['has_accept' => true]);

            $reason = 'Driver accept';
            $this->logOrderHistoryAction->handle(
                $order->id,
                MerchantOrderHistoryStatuses::DRIVER_ACCEPT->value,
                $reason,
                $driver_auth_user
            );
            $order->elmOrder->update(['elm_upload_status' => ElmOrderStatuses::DRIVER_ASSIGNED->value]);
            try {
                $this->notifyBranchEmployees($order);
                // $this->notifyEmployees($order);
                // $this->notifyBranchManager($order);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        }

        if ($order->driver_id == $driver_auth_id) {

            if (
                ($data["status"] == MerchantOrderStatuses::ON_WAY->value)

                && ($order->status != MerchantOrderStatuses::READY)
            ) {
                return $this->errorResponse('400', 'can not update this order not ready');
            } else if ($data["status"] == MerchantOrderStatuses::ON_WAY->value && $order->status == MerchantOrderStatuses::READY) {
                $order->update(['status' => $data["status"]]);
                TempOrder::query()
                    ->where('driver_id', $driver_auth_id)
                    ->where('order_id', $data["order_id"])
                    ->where('has_accept', true)->update(['status' => $data["status"]]);


                $reason = 'Driver on way';
                $this->logOrderHistoryAction->handle(
                    $order->id,
                    MerchantOrderHistoryStatuses::DRIVER_ON_WAY->value,
                    $reason,
                    $driver_auth_user
                );
                dispatch_sync(new SendNotifyOrderToUser("طلب قيد التوصيل", "لقد تم الانتهاء من تجهيز طلبك من المتجر وجاري التوصيل", $order, $order->user));
            }

            if ($data["status"] == MerchantOrderStatuses::PREVIOUS->value && $order->status == MerchantOrderStatuses::ON_WAY) {

                unset($data['order_id']);

                $order = $updateOrderStatusAction->handle($order->id, $data, $driver_auth_user, "Driver order completed");
                // $order->update(['status' => $data["status"]]);
                TempOrder::query()
                    ->where('driver_id', $driver_auth_id)
                    ->where('order_id', $order->id)
                    ->where('has_accept', true)->update(['status' => $data["status"]]);


                // $reason = 'Driver order completed';
                // $this->logOrderHistoryAction->handle(
                //     $order->id,
                //     MerchantOrderHistoryStatuses::COMPLETED->value,
                //     $reason,
                //     $driver_auth_user
                // );

                dispatch_sync(new SendNotifyOrderToUser("طلب مكتمل", "تم توصيل الطلب", $order, $order->user));
            }
        } else {
            return $this->errorResponse(408, 'can not change order status');
        }
        //$order->setSalesAgentCommission();
        return $this->responseData(["order" => new OrderResource($order)]);
    }

    protected function notifyEmployees(MerchantOrder $order): void
    {
        $employees = $order->branch->employees()->where('status', StatusEnum::ACTIVE->value)->get();
        try {
            dispatch(new \App\Domain\Notification\Jobs\Merchant\SendNotifyDriverOrderV2($order, $employees))->afterResponse();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
        // foreach ($employees as $employee)
        // {
        //     dispatch_sync(new SendNotifyDriverOrder($order,$employee));
        // }
    }

    protected function notifyBranchManager(MerchantOrder $order): void
    {
        $manager = $order->branch->manager()->first();
        try {
            dispatch(new \App\Domain\Notification\Jobs\Merchant\SendNotifyDriverOrderV2($order, $manager))->afterResponse();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
        // if ($manager)
        // {
        //     dispatch_sync(new SendNotifyDriverOrder($order,$manager));
        // }
    }
    public function test(): void
    {
        $order = MerchantOrder::query()->whereNull("driver_id")
            ->whereStatus(MerchantOrderStatuses::UPCOMING)->find(111);

        try {
            dispatch(new SearchForDriverJob($order));
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
    }

    public function ready()
    {
        $order = MerchantOrder::find(111);
        $order->update(['status' => MerchantOrderStatuses::READY]);
        try {
            // dispatch(new \App\Domain\Notification\Jobs\Driver\SendNotifyChangeOrderStatusV2($order, $order?->driver))->afterResponse();
            dispatch(new \App\Domain\Notification\Jobs\Driver\SendNotifyChangeOrderStatusV2($order?->driver_id))->afterResponse();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
        // try {
        //     NewOrderEvent::dispatch($order?->driver, $order);
        //     dispatch_sync(new SendNotifyChangeOrderStatus($order, $order?->driver));
        // } catch (\Exception $exception) {

        //     dd($exception->getMessage());
        // }
    }
    protected function notifyBranchEmployees(MerchantOrder $order): void
    {
        try {
            dispatch(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrderV3($order->id))->afterResponse();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
    }
}
