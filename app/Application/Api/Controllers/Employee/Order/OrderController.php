<?php

namespace App\Application\Api\Controllers\Employee\Order;

use App\Application\Api\Requests\Employee\Order\AcceptOrderRequest;
use App\Application\Api\Requests\Employee\Order\FetchOrderRequest;
use App\Application\Api\Requests\Employee\Order\MarkReadyRequest;
use App\Application\Api\Requests\Employee\Order\RejectOrderRequest;
use App\Application\Api\Requests\Employee\Order\StartOrderRequest;
use App\Application\Api\Requests\Employee\Order\CompleteOrderRequest;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\Driver\Jobs\SearchForDriverJob;
use App\Domain\Employee\Actions\Order\FetchOrderAction;
use App\Domain\Employee\Actions\Order\UpdateStatusAction;
use App\Domain\Employee\Resources\Order\OrderDetailsResource;
use App\Domain\Employee\Resources\Order\OrderResource;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;

use App\Domain\Notification\Jobs\User\Order\NotifyAcceptedOrder;
use App\Domain\Notification\Jobs\User\SendNotifyOrderToUser;
use App\Http\Controllers\Controller;
use App\Support\Enum\Elm\ElmOrderStatuses;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\MarketPlace\Models\ElmOrder;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Illuminate\Support\Facades\DB;
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

    public function index(FetchOrderRequest $request, FetchOrderAction $fetchOrderAction): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()?->status != "active")
            return $this->errorResponse(401, __('auth.employee_not_active'));
        $orders = $fetchOrderAction->handle($request->validated(), $request->user());
        return $this->responseData([
            'orders' => OrderResource::collection($orders)
        ]);
    }

    public function show($id, FetchOrderAction $fetchOrderAction): \Illuminate\Http\JsonResponse
    {
        // Log::info('Employee Show order in OrderController');
        // Log::info($id);
        if (is_numeric($id)) {
            // Log::error("show orderbyId is_numeric" . $id);
            try {
                $order = $fetchOrderAction->show($id);
                if (!$order)
                    return $this->errorResponse('400', 'data not found');
                return $this->responseData([
                    'order' => new OrderDetailsResource($order)
                ]);
            } catch (\Exception $exception) {
                // dd($exception->getMessage());
                Log::error("show orderbyId " . $id);
            }
        }
        Log::error("Employee show order by Id is not _numeric " . $id);
        return $this->errorResponse('400', 'data not found');
        // if (!$order) 
        // return $this->errorResponse('400', 'data not found');
        //     return $this->responseData([
        //         'order' => new OrderDetailsResource($order)
        //     ]);
    }


    public function accept(AcceptOrderRequest $acceptOrderRequest, UpdateStatusAction $updateStatusAction): \Illuminate\Http\JsonResponse
    {

        $data = $acceptOrderRequest->validated();
        $data['status'] = MerchantOrderStatuses::UPCOMING->value;
        $order = $updateStatusAction->update($data);
        if (!$order)
            return $this->errorResponse('400', 'data not found');

        $employee_auth_user = auth()->user();
        $employee_auth_id = $employee_auth_user?->id ?? auth()->id();
        // $employee_auth_user = auth('sanctum')->guard('employee-api')->user();
        // $employee_auth_id = $employee_auth_user?->id ?? auth('sanctum')->guard('employee-api')->id();
        if (!($employee_auth_user && $employee_auth_user instanceof \Domain\MarketPlace\Models\MerchantEmployee))
            return $this->errorResponse('401', 'unAuthorized Employee');
        $reason = "Employee accept";
        $this->logOrderHistoryAction->handle(
            $order->id,
            MerchantOrderHistoryStatuses::EMPLOYEE_ACCEPT->value,
            $reason,
            // auth()->user()
            $employee_auth_user,
        );

        $order->elmOrder->update(['elm_upload_status' => ElmOrderStatuses::ACCEPTED_BY_MERCHANT->value]);
        try {
            dispatch(new NotifyAcceptedOrder($order, $order->user));
            if ($order->service_type_id != 2)
                dispatch(new SearchForDriverJob($order));
        } catch (\Exception $exception) {
            //dd($exception->getMessage());
            Log::error("Order Controller" . $exception->getMessage());
        }

        return $this->responseData([
            'msg' => 'Confirmed Successfully',
            'order' => new OrderDetailsResource($order)
        ]);
    }

    public function reject(RejectOrderRequest $rejectOrderRequest, UpdateStatusAction $updateStatusAction, WalletOrderAction $walletOrderAction): \Illuminate\Http\JsonResponse
    {
        $data = $rejectOrderRequest->validated();
        $employee_auth_user = auth()->user();
        $employee_auth_id = $employee_auth_user?->id ?? auth()->id();
        // $employee_auth_user = auth('sanctum')->guard('employee-api')->user();
        // $employee_auth_id = $employee_auth_user?->id ?? auth('sanctum')->guard('employee-api')->id();
        if (!($employee_auth_user && $employee_auth_user instanceof \Domain\MarketPlace\Models\MerchantEmployee))
            return $this->errorResponse('401', 'unAuthorized Employee');
        $data['status'] = MerchantOrderStatuses::CANCELLED->value;
        $order = $updateStatusAction->update($data);
        if (!$order)
            return $this->errorResponse('400', 'data not found');

        $reason = "Employee reject";
        $this->logOrderHistoryAction->handle(
            $order->id,
            MerchantOrderHistoryStatuses::EMPLOYEE_REJECT->value,
            $reason,
            // auth()->user()
            $employee_auth_user
        );
        $order->elmOrder->update(['elm_upload_status' => ElmOrderStatuses::REJECTED_BY_MERCHANT->value]);
        $walletOrderAction->deposit($order, "Wallet Canceled Order");
        dispatch_sync(new SendNotifyOrderToUser("طلب مرفوض", "لقد تم رفض الطلب من المتجر !", $order, $order->user));
        return $this->responseData([
            'msg' => 'Rejected Successfully',
            'order' => new OrderDetailsResource($order)
        ]);
    }

    public function start(StartOrderRequest $startOrderRequest, UpdateStatusAction $updateStatusAction): \Illuminate\Http\JsonResponse
    {
        $data = $startOrderRequest->validated();
        $employee_auth_user = auth()->user();
        $employee_auth_id = $employee_auth_user?->id ?? auth()->id();
        // $employee_auth_user = auth('sanctum')->guard('employee-api')->user();
        // $employee_auth_id = $employee_auth_user?->id ?? auth('sanctum')->guard('employee-api')->id();
        if (!($employee_auth_user && $employee_auth_user instanceof \Domain\MarketPlace\Models\MerchantEmployee))
            return $this->errorResponse('401', 'unAuthorized Employee');
        $data['status'] = MerchantOrderStatuses::CURRENT->value;
        $order = $updateStatusAction->update($data);
        if (!$order)
            return $this->errorResponse('400', 'data not found');


        $reason = "Employee start";
        $this->logOrderHistoryAction->handle(
            $order->id,
            MerchantOrderHistoryStatuses::START_COOKING->value,
            $reason,
            // auth()->user()
            $employee_auth_user
        );

        return $this->responseData([
            'msg' => 'Start Cooking Successfully',
            'order' => new OrderDetailsResource($order)
        ]);
    }
    public function mark_complete(CompleteOrderRequest $startOrderRequest, UpdateStatusAction $updateStatusAction): \Illuminate\Http\JsonResponse
    {
        $data = $startOrderRequest->validated();
        $employee_auth_user = auth()->user();
        $employee_auth_id = $employee_auth_user?->id ?? auth()->id();
        // $employee_auth_user = auth('sanctum')->guard('employee-api')->user();
        // $employee_auth_id = $employee_auth_user?->id ?? auth('sanctum')->guard('employee-api')->id();
        if (!($employee_auth_user && $employee_auth_user instanceof \Domain\MarketPlace\Models\MerchantEmployee))
            return $this->errorResponse('401', 'unAuthorized Employee');
        $data['status'] = MerchantOrderStatuses::PREVIOUS->value;
        $order = $updateStatusAction->update($data);
        if (!$order)
            return $this->errorResponse('400', 'data not found');


        $reason = "Employee mark_complete";
        $this->logOrderHistoryAction->handle(
            $order->id,
            MerchantOrderHistoryStatuses::EMPLOYEE_COMPLETED->value,
            $reason,
            // auth()->user()
            $employee_auth_user
        );

        return $this->responseData([
            'msg' => 'Complete Successfully',
            'order' => new OrderDetailsResource($order)
        ]);
    }
    public function mark_ready(MarkReadyRequest $markReadyRequest, UpdateStatusAction $updateStatusAction): \Illuminate\Http\JsonResponse
    {
        $data = $markReadyRequest->validated();
        $employee_auth_user = auth()->user();
        $employee_auth_id = $employee_auth_user?->id ?? auth()->id();
        // $employee_auth_user = auth('sanctum')->guard('employee-api')->user();
        // $employee_auth_id = $employee_auth_user?->id ?? auth('sanctum')->guard('employee-api')->id();
        if (!($employee_auth_user && $employee_auth_user instanceof \Domain\MarketPlace\Models\MerchantEmployee))
            return $this->errorResponse('401', 'unAuthorized Employee');
        $data['status'] = MerchantOrderStatuses::READY->value;
        $order = $updateStatusAction->update($data);
        if (!$order)
            return $this->errorResponse('400', 'data not found');


        $reason = "Employee mark_ready";
        $this->logOrderHistoryAction->handle(
            $order->id,
            MerchantOrderHistoryStatuses::MARKED_READY->value,
            $reason,
            // auth()->user()
            $employee_auth_user
        );
        try {
            // dispatch(new \App\Domain\Notification\Jobs\Driver\SendNotifyChangeOrderStatusV2($order,$order?->driver))->afterResponse();
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
        dispatch_sync(new SendNotifyOrderToUser("طلب جاهز", "لقد تم الانتهاء من تجهيز طلبك من المتجر ", $order, $order->user));

        return $this->responseData([
            'msg' => 'Order Marked Ready Successfully',
            'order' => new OrderDetailsResource($order)
        ]);
    }
}
