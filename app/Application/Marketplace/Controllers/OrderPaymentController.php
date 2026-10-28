<?php

namespace Application\Marketplace\Controllers;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Application\Marketplace\requests\Order\SavePaymentRequest;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\Employee\Events\NewOrderEvent;
use App\Domain\MarketPlace\Actions\Api\Cart\CartAction;
use App\Domain\MarketPlace\Actions\Api\Cart\DeleteItemCartAction;
use App\Domain\MarketPlace\Actions\Order\OrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\Notification\Jobs\Merchant\SendNotifyOrder;
use App\Http\Controllers\Controller;
use App\Support\Enum\StatusEnum;
use Domain\AppBase\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Exception;

class OrderPaymentController extends Controller
{

    public function paymentIframe($lang, $token, OrderAction $orderAction)
    {
        $order = $orderAction->handle($token);
        if ($order) return view('marketplace::pages.payment.index', get_defined_vars());
        return abort(404);
    }

    public function payment_save(SavePaymentRequest $request, OrderAction $orderAction, PaymentAction $paymentAction): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $order = $orderAction->handle($data["key"]);
        if (!$order) return response()->json(['message' => 'data not found'], 404);
        $paymentAction->handle($data, $order);
        try {
            if ($data["status"] && $data["status"] == 'paid') {
                $ord =  $paymentAction->checkOnCreate($data, $order);
                if ($ord) {
                    $this->test_payment_statusWithOutResponse($ord?->id, $orderAction);
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json([], 201);
        }


        return response()->json([], 201);
    }

    public function payment_check(Request $request, PaymentAction $paymentAction, OrderAction $orderAction): \Illuminate\Http\RedirectResponse
    {
        // Log::channel('daily')->info("OrderPaymentController payment_status".$request.);
        try {
            $data = $paymentAction->check($request->all());
            $res = Session::get('payment_merchant_iframe');
            // $sessinData=$res->all();
            // if($sessinData&&$sessinData['order_id'])
            // $orderId=$sessinData['order_id'];
            // if($res->has('order_id'))
            // $orderId=$res->get('order_id');
            //     if($res)
            //     {
            //         Log::channel('slack')->error("payment_check Session  data : {" .json_encode($res)."}");
            // }
            if ($res && $res['order_id'])
                $orderId = $res['order_id'];
            if ($data && $data->status == 'paid')
                return $this->test_payment_status('success', $data->payable_id ?? $orderId, $orderAction);
            else if ($data)
                return $this->test_payment_status('failed', $data->payable_id ?? $orderId, $orderAction);
            else
                return $this->test_payment_status('failed', $orderId, $orderAction);
        } catch (Exception $exception) {
            Log::error($exception);
            Log::channel('slack')->error("payment_check Moyasar  data : {" . json_encode($request->all()) . "}");
            return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
        }
    }

    public function test_payment_status($status, $id, OrderAction $orderAction): \Illuminate\Http\RedirectResponse
    {
        if (isset($id)) {
            $order = $orderAction->getById($id);
            if ($status == 'failed' || !$order) {
                return Redirect::to(config('app.url')."/payment-done?&order_id=" . $id . "&confirm=failed");
            } else {
                // $order = $orderAction->update($id, MerchantOrderStatuses::PENDING->value)??$order;
                if (($order->status == MerchantOrderStatuses::DRAFT && $order->status == MerchantOrderStatuses::DRAFT->value))
                    $order = $orderAction->update($id, MerchantOrderStatuses::PENDING->value) ?? $order;
                // return Redirect::to(config('app.url')."/payment-done?&order_id=".$id."&confirm=failed");
                try {
                    $this->notifyBranchEmployees($order);

                    // $this->notifyEmployees($order);
                    // $this->notifyBranchManager($order);
                } catch (\Exception $exception) {
                    dd($exception->getMessage());
                    Log::error($exception);
                }
                return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
            }
        } else {
            return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
        }
    }
    public function test_payment_statusWithOutResponse($id, OrderAction $orderAction)
    {
        if (isset($id)) {
            $order = $orderAction->getById($id);
            if ($order) {

                $order = $orderAction->update($id, MerchantOrderStatuses::PENDING->value) ?? $order;
                // if(!$order|| ($order->status != MerchantOrderStatuses::PENDING && $order->status!=MerchantOrderStatuses::PENDING->value))
                // return Redirect::to(config('app.url')."/payment-done?&order_id=".$id."&confirm=failed");

                try {
                    $this->notifyBranchEmployees($order);
                    // $this->notifyEmployees($order);
                    // $this->notifyBranchManager($order);
                } catch (\Exception $exception) {
                    dd($exception->getMessage());
                    Log::error($exception);
                }
            }
        }
        return response()->json([], 201);
    }
    public function payment_status($status, $id, PaymentAction $paymentAction, OrderAction $orderAction): \Illuminate\Http\RedirectResponse
    {
        // Log::channel('daily')->info("OrderPaymentController payment_status".[
        //     'id'=>$id,
        //     'status'=>$status
        // ]);
        $data = $paymentAction->check(
            [
                'id' => $id,
                // 'status'=>$status
            ]
        );
        if (!$data)
            return $this->test_payment_status('failed', null, $orderAction);
        else if ($data->status == 'paid')
            return $this->test_payment_status('success', $data?->payable_id ?? null, $orderAction);
        else
            return $this->test_payment_status('failed', $data?->payable_id ?? null, $orderAction);
    }
    public function oldPayment_status($status, $id, OrderAction $orderAction, DeleteItemCartAction $cartAction): \Illuminate\Http\RedirectResponse
    {
        $lang = app()->getLocale();
        $res = Session::get('payment_merchant_iframe');
        if ($status == 'success' && isset($id)) {
            $order = $orderAction->update($id, MerchantOrderStatuses::PENDING->value);

            $cartAction->clear($order->user->id);
            //dispatch(new SendNotifyOrder($order,$order->user));
            //dispatch(new MerchantNotify($order,$order->merchant));

            try {
                $this->notifyEmployees($order);
                $this->notifyBranchManager($order);
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }
            return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
        } elseif ($status == 'failed' && isset($id)) {
            return Redirect::to(config('app.url')."/payment-done?&order_id=" . $id . "&confirm=failed");
        }
        return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
    }
    protected function notifyEmployees(MerchantOrder $order): void
    {
        $employees = $order->branch->employees()->where('status', StatusEnum::ACTIVE->value)->get();
        foreach ($employees as $employee) {
            NewOrderEvent::dispatch($employee, $order);
            // dispatch(new SendNotifyOrder($order, $employee))->afterResponse();
            // dispatch(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $employee))->afterResponse();
            dispatch_sync(new SendNotifyOrder($order, $employee));
            dispatch_sync(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $employee));
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
            dispatch_sync(new \App\Domain\Notification\Jobs\Merchant\V1\SendNotifyOrder($order, $manager));
        }
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
