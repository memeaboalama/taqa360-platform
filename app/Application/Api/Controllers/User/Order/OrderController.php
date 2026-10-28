<?php

namespace App\Application\Api\Controllers\User\Order;

use App\Application\Api\Requests\Order\CancelOrderRequest;
use App\Application\Api\Requests\Order\OrderRequest;
use App\Application\Api\Requests\Order\Payment\CreatePaymentRequest;
use App\Application\Api\Requests\Order\PlaceOrderRequest;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\Employee\Events\NewOrderEvent;
use App\Domain\Employee\Resources\Order\OrderDetailsResource;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Actions\Api\Order\OrderActionV2;
use App\Domain\MarketPlace\Actions\Api\Order\OrderAction;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\resources\Order\OrderResource;
// use App\Domain\Notification\Jobs\Merchant\SendNotifyOrder;
use App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder;
use App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrderV3;
use App\Domain\User\Actions\Order\UpdateStatusAction;
use App\Http\Controllers\Controller;
use App\Payment\Payment;
use App\Support\Enum\ChannelTypesEnum;
use App\Support\Enum\Elm\ElmOrderStatuses;
use App\Support\Enum\PaymentMethod;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public PaymentAction $paymentAction;
    public OrderAction $orderAction;
    public OrderActionV2 $orderActionV2;
    public WalletOrderAction $walletOrderAction;
    private LogOrderHistoryAction $logOrderHistoryAction;

    public function __construct(OrderActionV2 $orderActionV2, OrderAction $orderAction, PaymentAction $paymentAction, WalletOrderAction $walletOrderAction, LogOrderHistoryAction $logOrderHistoryAction)
    {
        $this->paymentAction = $paymentAction;
        $this->orderAction = $orderAction;
        $this->orderActionV2 = $orderActionV2;
        $this->walletOrderAction = $walletOrderAction;
        $this->logOrderHistoryAction = $logOrderHistoryAction;
    }


    public function store(PlaceOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
      

        if ($user->status?->value != "active") return $this->errorResponse(401, __('auth.employee_not_active'));

        $cart = $user->cart;
        $response = [];


        try {

            $order = $this->orderAction->handle($data, $user);

            if (!$order["status"]) return $this->errorResponse(400, $order["message"]);
            DB::beginTransaction();
            // DB::commit();

            $reason = 'User create';
            $this->logOrderHistoryAction->handle(
                $order['data']->id,
                MerchantOrderHistoryStatuses::CREATE_ORDER->value,
                $reason,
                $user,
            );

            $response = [
                'order' => new OrderResource($order["data"]),
            ];

            if ($order['data']->total_after_discount > 0) {
                $this->processPayment($data, $order, $response, $cart);
            } else {
                $this->walletOrCachPayment($data, $order, $response, $cart);
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return $this->responseData($response);
    }
    public function storeV2(PlaceOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();
      

        if ($user->status?->value != "active") return $this->errorResponse(401, __('auth.employee_not_active'));

        $cart = $user->cart;
        $response = [];


        try {

            $order = $this->orderActionV2->handle($data, $user);

            if (!$order["status"]) return $this->errorResponse(400, $order["message"]);
            DB::beginTransaction();
            // DB::commit();

            $reason = 'User create';
            $this->logOrderHistoryAction->handle(
                $order['data']->id,
                MerchantOrderHistoryStatuses::CREATE_ORDER->value,
                $reason,
                $user,
            );

            $response = [
                'order' => new OrderResource($order["data"]),
            ];

            if ($order['data']->total_after_discount > 0) {
                $this->processPayment($data, $order, $response, $cart);
            } else {
                $this->walletOrCachPayment($data, $order, $response, $cart);
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return $this->responseData($response);
    }
    private function processPayment($data, $order, &$response, $cart)
    {
        if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::COD->value) {
            $response["COD"] = true;
            $this->walletOrCachPayment($data, $order, $response, $cart);
        }

        if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::APPLE->value) {
            $response["apple"] = $this->payWithApple($order['data']);
        }

        if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::MADA->value) {
            $response["paymentUrl"] = $this->payWithMada($order['data']);
        }
    }


    private function walletOrCachPayment($data, $order, &$response, $cart)
    {
        try {
            if ($data['wallet'] == 1 || $data['merchantBalance'] == 1) {
                $payment = $this->walletOrderAction->withdraw($order["data"], "Wallet Order Pay");
                if ($payment && !$payment["status"]) {
                    Log::error(json_encode($payment));
                    return $this->errorResponse(400, $payment["message"]);
                }
            }
            $this->notifyEmployees($order["data"]);
            $this->notifyBranchManager($order["data"]);
            $order["data"]->update(['status' => MerchantOrderStatuses::PENDING]);
            $this->clearCart($cart);
            $response["paidFromWallet"] = true;
            $this->notifyBranchEmployees($order["data"]->id);
        } catch (Exception $exception) {

            Log::error($exception->getMessage());
            Log::error($exception);
        }
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

            if ($data["channel"] == ChannelTypesEnum::MOBILE->value && $data["payment_method"] == PaymentMethod::MADA->value) {
                $response["paymentUrl"] = $this->payWithMada($order);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }
        return $this->responseData($response);
    }

    public function index(OrderRequest $request, OrderAction $orderAction): \Illuminate\Http\JsonResponse
    {
        // Log::debug('request++++++++++++'.$request);
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

        $reason = "User cancel";
        $this->logOrderHistoryAction->handle(
            $order->id,
            MerchantOrderHistoryStatuses::USER_CANCELLED->value,
            $reason,
            $request->user()
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
        // return route('marketplace:order.payment.clickpay.iframe', ['lang' => app()->getLocale(), 'token' => $order->id]);
        return route('marketplace:order.payment.iframe', ['lang' => app()->getLocale(), 'token' => $order->id]);
    }

    protected function notifyEmployees(MerchantOrder $order): void
    {
        $employees = $order->branch->employees()->where('status', StatusEnum::ACTIVE->value)->get();
        foreach ($employees as $employee) {
            NewOrderEvent::dispatch($employee, $order);
            // dispatch(new SendNotifyOrder($order, $employee))->afterResponse();
            // dispatch(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $employee))->afterResponse();
            dispatch_sync(new SendNotifyOrder($order, $employee));
            dispatch_sync(new \App\Domain\Notification\Jobs\Merchant\SendNotifyOrder($order, $employee));
        }
    }

    protected function notifyBranchManager(MerchantOrder $order): void
    {
        $manager = $order->branch->manager()->first();
        if ($manager) {
            NewOrderEvent::dispatch($manager, $order);
            // dispatch(new SendNotifyOrder($order, $manager))->afterResponse();
            // dispatch(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $manager))->afterResponse();
            dispatch_sync(new SendNotifyOrder($order, $manager));
            dispatch_sync(new \App\Domain\Notification\Jobs\Merchant\SendNotifyOrder($order, $manager));
        }
    }

    private function clearCart($cart)
    {
        $cart->cartChoices()->delete();
        $cart->cartItems()->delete();
        $cart->delete();
    }

    protected function notifyBranchEmployees($order_id)
    {
        try {
            dispatch(new SendNotifyOrderV3($order_id))->afterResponse();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
    }
}
