<?php

namespace App\Application\Api\Controllers\User\Order;

use App\Application\Api\Requests\Order\CancelOrderRequest;
use App\Application\Api\Requests\Order\OrderRequest;
use App\Application\Api\Requests\Order\Payment\CreatePaymentRequest;
use App\Application\Api\Requests\Order\PlaceOrderRequest;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\AppBase\Models\Promocode;
use App\Domain\Employee\Actions\Order\UpdateElmStatusAction;
use App\Domain\Employee\Events\NewOrderEvent;
use App\Domain\Employee\Resources\Order\OrderDetailsResource;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Actions\Api\Order\OrderAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\MerchantOrderHistory;
use App\Domain\MarketPlace\resources\Order\OrderResource;
// use App\Domain\Notification\Jobs\Merchant\SendNotifyOrder;
use App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder;
use App\Domain\User\Actions\Order\UpdateStatusAction;
use App\Domain\User\Models\User;
use App\Http\Controllers\Controller;
use App\Payment\Payment;
use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\Elm\ElmOrderStatuses;
use App\Support\Enum\PaymentMethod;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderControllerOLD extends Controller
{
    use ApiResponseTrait;

    public PaymentAction $paymentAction;
    public OrderAction $orderAction;
    public WalletOrderAction $walletOrderAction;

    public LogOrderHistoryAction $logOrderHistoryAction;

    public function __construct(
        OrderAction $orderAction,
        PaymentAction $paymentAction,
        WalletOrderAction $walletOrderAction,
        LogOrderHistoryAction $logOrderHistoryAction
    )
    {
        $this->paymentAction = $paymentAction;
        $this->orderAction = $orderAction;
        $this->walletOrderAction = $walletOrderAction;
        $this->logOrderHistoryAction = $logOrderHistoryAction;
    }

    /**
     * Display a listing of the resource.
     */

    public function store(PlaceOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
        if ($user->status?->value != "active") return $this->errorResponse(401, __('auth.employee_not_active'));
        $cart=$user->cart;
        $response=[];
        try {
            DB::beginTransaction();
            $order = $this->orderAction->handle($data, $user);
            if (!$order["status"]) return $this->errorResponse(400, $order["message"]);

            $this->logOrderHistoryAction->handle(
                $order['data']->id,
                MerchantOrderHistoryStatuses::CREATE_ORDER->value,
            );
            $response = [
                'order' => new OrderResource($order["data"]),
            ];
            if($order['data']->total_after_discount > 0){
            if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == (PaymentMethod::COD->value || PaymentMethod::ONLINE_LATER->value)) {
                $response["COD"] = true;
                try {
                    if($data['wallet'] == 1 || $data['merchantBalance'] == 1){
                        $this->walletOrderAction->withdraw($order["data"],"Wallet Order Pay");
                    }
                    $this->notifyEmployees($order["data"]);
                    $this->notifyBranchManager($order["data"]);
                    $order["data"]->update(['status' => MerchantOrderStatuses::PENDING]);
                    $cart->cartChoices()->delete();
                    $cart->cartItems()->delete();
                    $cart->delete();
                } catch (\Exception $exception) {
                    Log::error($exception);
                }
            }

            if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::APPLE->value) {
                $response["apple"] = $this->payWithApple($order['data']);
            }

            if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::MADA->value)
            {
                $response["paymentUrl"] = $this->payWithMada($order['data']);
            }

            }else{
                try {
                    if($data['wallet'] == 1 || $data['merchantBalance'] == 1){
                        $this->walletOrderAction->withdraw($order["data"],"Wallet Order Pay");
                    }
                    $this->notifyEmployees($order["data"]);
                    $this->notifyBranchManager($order["data"]);
                    $order["data"]->update(['status' => MerchantOrderStatuses::PENDING]);
                    $cart->cartChoices()->delete();
                    $cart->cartItems()->delete();
                    $cart->delete();
                } catch (Exception $exception) {
                    Log::error($exception);
                }
                $response["paidFromWallet"] = true;
            }
            DB::commit();
        }catch (Exception $exception)
        {
            DB::rollBack();
            Log::error($exception);
        }
        return $this->responseData($response);
    }

    public function createPayment(CreatePaymentRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            $order = $this->orderAction->show($data['order_id']);
            if (!$order) return $this->errorResponse(400, $order["message"]);
            $response = [
                'order' => new OrderResource($order),
            ];

            if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::APPLE->value) {
                $response["apple"] = $this->payWithApple($order);
            }

            if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::MADA->value)
            {
                $response["paymentUrl"] = $this->payWithMada($order);
            }
            DB::commit();
        }catch (Exception $exception)
        {
            DB::rollBack();
            Log::error($exception);
        }
        return $this->responseData($response);
    }

    public function index(OrderRequest $request, OrderAction $orderAction): \Illuminate\Http\JsonResponse
    {
        return $this->responseData([
            'orders' => OrderResource::collection($orderAction->all($request->validated()))
        ]);
    }

    public function show(int $id = null, OrderAction $orderAction): \Illuminate\Http\JsonResponse
    {
        $order = $orderAction->show($id);
        if (!$order) return $this->errorResponse(400, "data not found");
        return $this->responseData([
            'order' => new OrderResource($order)
        ]);
    }

    public function cancel(CancelOrderRequest $request, UpdateStatusAction $updateStatusAction): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $data['status'] = MerchantOrderStatuses::CANCELLED->value;
        $order = $updateStatusAction->update($data);
        if (!$order) return $this->errorResponse('400', 'data not found');

        $this->logOrderHistoryAction->handle(
            $order->id,
            MerchantOrderHistoryStatuses::USER_CANCELLED->value
        );

        $order->elmOrder->update(['elm_upload_status' =>  ElmOrderStatuses::CANCELLED_BY_CLIENT->value]);
        $this->walletOrderAction->deposit($order, "Wallet Canceled Order");
        return $this->responseData([
            'msg' => 'Canceled Successfully',
            'order' => new OrderDetailsResource($order)
        ]);
    }

    private function payWithApple($order)
    {
        $apple = Payment::applePay([
            'amount' => (int)($order->total_after_vat * 100),
            'publishable_api_key' => config('services.moyasar.key'),
            'description' => config('app.name','ECommarce-App')." Co",
            'currency' => "SAR",
            'source' => [
                'type' => 'applepay',
                'token' => json_decode($order->id),
            ]
        ]);

        if (!$apple) return $this->errorResponse(400, "Please try again after few seconds .");
        $this->paymentAction->handle($apple, $order["data"]);
        return $apple["status"];
    }

    private function payWithMada($order)
    {
        return route('marketplace:order.payment.iframe', ['lang' => app()->getLocale(), 'token' => $order->id]);
    }

    protected function notifyEmployees(MerchantOrder $order) : void
    {
        $employees = $order->branch->employees()->where('status' , StatusEnum::ACTIVE->value)->get();
        foreach ($employees as $employee)
        {
            NewOrderEvent::dispatch($employee, $order);
            dispatch_sync(new SendNotifyOrder($order, $employee));
        }
    }

    protected function notifyBranchManager(MerchantOrder $order) : void
    {
        $manager = $order->branch->manager()->first();
        if ($manager)
        {
            NewOrderEvent::dispatch($manager, $order);
            dispatch_sync(new SendNotifyOrder($order, $manager));
        }
    }
}