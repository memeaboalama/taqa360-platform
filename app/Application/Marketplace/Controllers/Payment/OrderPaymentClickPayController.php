<?php

namespace Application\Marketplace\Controllers\Payment;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Application\Marketplace\requests\Order\SavePaymentRequest;
use App\Application\Marketplace\requests\Order\SaveClickPayRequest;
use App\Domain\AppBase\Actions\Payment\PaymentAction;
use App\Domain\AppBase\Actions\Payment\ClickPayAction;
use App\Domain\Employee\Events\NewOrderEvent;
// use Clickpaysa\Laravel_package\Facades\paypage;
use App\Domain\MarketPlace\Actions\Api\Cart\DeleteItemCartAction;
use App\Domain\MarketPlace\Actions\Order\OrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\Notification\Jobs\Merchant\SendNotifyOrder;
use App\Http\Controllers\Controller;
use App\Payment\ClickPay\Facades\paypage;
// use App\Payment\ClickPay\paypage;
use App\Support\Enum\StatusEnum;
use Domain\AppBase\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
// use Clickpaysa\Laravel_package\Services\IpnRequest;
use App\Payment\ClickPay\Services\IpnRequest;
use Exception;

class OrderPaymentClickPayController extends Controller
{


    public function paymentIframe($lang, $token, ClickPayAction $orderClickPayAction)
    {

        // $res = Session::get('click_pay_merchant_iframe_'.$token.'');
        // if($res)
        // {
        //     if(isset($res["payment_url"]))
        //     return Redirect::to($res["payment_url"]);
        //     elseif(isset($res["redirect_url"]))
        //     return Redirect::to($res["redirect_url"]);
        // }
        $order = $orderClickPayAction->handle($token, lang: $lang);


        // $pay = paypage::sendPaymentCode('all')
        //     ->sendTransaction('sale')
        //     ->sendCart($order->id, $order->total_after_vat, 'test')
        //     ->sendCustomerDetails($this->orderUser->name, $this->orderUser->email ?? 'email@email.com', $this->orderUser->phone, 'test', $this->orderUser->country_name, 'Riyadh', 'SA', '1234', '10.0.0.10')
        //     ->sendShippingDetails($this->order?->branch?->name ?? 'azzam zaid', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234', '10.0.0.10')
        //     //    ->sendShippingDetails('Name', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234','10.0.0.10')
        //     //    ->sendURLs('return_url', 'callback_url')
        //     ->sendLanguage('en')
        //     ->create_pay_page();
        if ($order && isset($order->payment_url)) // return $order;
            return Redirect::to($order->payment_url);
        if ($order) return view('marketplace::pages.payment.clickpay', get_defined_vars());
        return abort(404);
    }
    public function paymentIframeOld($lang, $token, ClickPayAction $orderClickPayAction)
    {
        $order = $orderClickPayAction->handle($token, lang: $lang);


        // $pay = paypage::sendPaymentCode('all')
        //     ->sendTransaction('sale')
        //     ->sendCart($order->id, $order->total_after_vat, 'test')
        //     ->sendCustomerDetails($this->orderUser->name, $this->orderUser->email ?? 'email@email.com', $this->orderUser->phone, 'test', $this->orderUser->country_name, 'Riyadh', 'SA', '1234', '10.0.0.10')
        //     ->sendShippingDetails($this->order?->branch?->name ?? 'azzam zaid', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234', '10.0.0.10')
        //     //    ->sendShippingDetails('Name', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234','10.0.0.10')
        //     //    ->sendURLs('return_url', 'callback_url')
        //     ->sendLanguage('en')
        //     ->create_pay_page();
        if ($order) return $order;
        // if ($order) return view('marketplace::pages.payment.clickpay', get_defined_vars());
        return abort(404);
    }


    public function payment_ipn(Request $request)
    {
        try {
            $ipnRequest = new IpnRequest($request);

            $id = $ipnRequest->getCartId();
            $status = $ipnRequest->getStatus();
            $tran_ref = $ipnRequest->getTranRef();
            $code = $ipnRequest->getCode();
            $client_key = $ipnRequest->getIpnRequestClientKey();
            Log::channel('daily')->info(" OrderPaymentClickPayController order_id: " . $id . " status: " . $status . " tranRef: " . $tran_ref . " code: " . $code . " clientKey: " . $client_key . "");
            if ($ipnRequest->getStatus() == 'D') {
                if (Session::has('click_pay_merchant_iframe_' . $id . ''))
                    Session::forget('click_pay_merchant_iframe_' . $id . '');
                return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
            }
            if ($ipnRequest->getStatus() == 'A') {
                $orderClickPayAction = new ClickPayAction();
                $order = $orderClickPayAction->updateCartByIPN($ipnRequest);
                if ($order) {
                    if (($order->status == MerchantOrderStatuses::PENDING || $order->status == MerchantOrderStatuses::PENDING->value)) {
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
                    // return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
                }
            }

            // $callbackClass = config('clickpay.callback');
            // $callback = new $callbackClass();
            // if(is_object($callback) && method_exists($callback, 'updateCartByIPN') ){
            //     $callback->updateCartByIPN($ipnRequest);
            // }
            // $response= 'valid IPN request. Cart updated';
            // return response($response, 200)
            //     ->header('Content-Type', 'text/plain');
            return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
        } catch (\Exception $e) {
            // return response($e, 400)
            // ->header('Content-Type', 'text/plain');
            return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
        }
        return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
    }



    public function payment_status(SaveClickPayRequest $request) //: \Illuminate\Http\RedirectResponse
    {


        try {
            $data = $request->validated();
            Log::channel('daily')->info(" OrderPaymentClickPayController order_id:  payment_status:  " . json_encode($data) . "");
            $allRequestData = $request->all();
            Log::channel('daily')->info(" OrderPaymentClickPayController order_id:  All Request Data payment_status:  " . json_encode($allRequestData) . "");
            // $ipnRequest = new IpnRequest($request);
            $id = $data->cartId;

            $acquirerMessage = $data->acquirerMessage;
            $acquirerRRN = $data->acquirerRRN;
            $code = $data->respCode;
            $message = $data->respMessage;
            $status = $data->respStatus;
            $token = $data->token;
            $tran_ref = $data->tranRef;
            $customerEmail = $data->customerEmail;

            Log::channel('daily')->info(" OrderPaymentClickPayController order_id: step 2  payment_status:   acquirerMessage: " . $acquirerMessage . " acquirerRRN: " . $acquirerRRN . " code: " . $code . " message: " . $message . " status: " . $status . " token: " . $token . " tran_ref: " . $tran_ref . " customerEmail: " . $customerEmail . "");
            // $client_key = $ipnRequest->getIpnRequestClientKey();
            Log::channel('daily')->info(" OrderPaymentClickPayController order_id: " . $id . " status: " . $status . " tranRef: " . $tran_ref . " code: " . $code .  "");
            // // $id = $ipnRequest->getCartId();
            if ($status == 'D')
                return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
            else if ($status == 'A') {
                $orderClickPayAction = new ClickPayAction();
                $order = $orderClickPayAction->update_cart_by_ipn(
                    $id,
                    $status,
                    $tran_ref,
                    $code
                );
                if ($order) {
                    if (($order->status == MerchantOrderStatuses::PENDING || $order->status == MerchantOrderStatuses::PENDING->value)) {
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
                    // return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
                }
            }

            // $callbackClass = config('clickpay.callback');
            // $callback = new $callbackClass();
            // if(is_object($callback) && method_exists($callback, 'updateCartByIPN') ){
            //     $callback->updateCartByIPN($ipnRequest);
            // }
            // $response= 'valid IPN request. Cart updated';
            // return response($response, 200)
            //     ->header('Content-Type', 'text/plain');
            return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error($e);
            return response($e, 400)
                ->header('Content-Type', 'text/plain');
            // return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
        }
        return response("unknown error", 400)
            ->header('Content-Type', 'text/plain');
        // return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
    }

    public function return_payment_status(
        $id
    ) {

        // $data = request()->all();
        Log::channel('daily')->info(json_encode(request()->all()));
        // $res = Session::get('click_pay_merchant_iframe');
        $res = Session::get('click_pay_merchant_iframe_' . $id . '');
        Log::channel('daily')->debug(json_encode($res));
        // $pp = new paypage();

        // $transaction = $pp->queryTransaction($res["tran_ref"]);
        if ($res && isset($res["tran_ref"])) {
            $verify = paypage::queryTransaction($res["tran_ref"]);
            Log::channel('daily')->debug(json_encode($verify));
            if ($verify->success) {
                $orderClickPayAction = new ClickPayAction();
                $order = $orderClickPayAction->update_cart_by_ipn(
                    $id,
                    // $verify->reference_no,
                    $verify->payment_result->response_status,
                    $verify->transaction_id,
                    $verify->payment_result->response_code
                );
                if ($order) {
                    if (($order->status == MerchantOrderStatuses::PENDING || $order->status == MerchantOrderStatuses::PENDING->value)) {
                        try {
                            $this->notifyBranchEmployees($order);

                            // $this->notifyEmployees($order);
                            // $this->notifyBranchManager($order);
                        } catch (\Exception $exception) {
                            dd($exception->getMessage());
                            Log::error($exception);
                        }
                        Session::put('click_pay_merchant_iframe_' . $id . '', [
                            'order_id' => $order->id,
                            'redirect_url' => config('app.url')."/payment-done?&order_id=$id&confirm=done",
                            'tran_ref' => $order->tran_ref,
                            'payment_url' => config('app.url')."/payment-done?&order_id=$id&confirm=done",
                        ]);
                        // return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
                        return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
                    }
                }
            }
        }
        Session::forget('click_pay_merchant_iframe_' . $id . '');
        return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
    }
    public function return_payment_status1(
        $cartId,
        $respCode,
        $respStatus,
        $signature,
        $tranRef
    ): \Illuminate\Http\RedirectResponse {
        $id = $cartId;

        $signature = $signature;
        $code = $respCode;
        $status = $respStatus;
        $tran_ref = $tranRef;


        Log::channel('daily')->info(" OrderPaymentClickPayController order_id: " . $id . " status: " . $status . " tranRef: " . $tran_ref . " code: " . $code .  " signature: " . $signature . "");
        try {
            if ($status == 'D')
                return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
            else if ($status == 'A') {
                $orderClickPayAction = new ClickPayAction();
                $order = $orderClickPayAction->update_cart_by_ipn(
                    $id,
                    $status,
                    $tran_ref,
                    $code
                );
                if ($order) {
                    if (($order->status == MerchantOrderStatuses::PENDING || $order->status == MerchantOrderStatuses::PENDING->value)) {
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
                    // return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
                }
            }

            // $callbackClass = config('clickpay.callback');
            // $callback = new $callbackClass();
            // if(is_object($callback) && method_exists($callback, 'updateCartByIPN') ){
            //     $callback->updateCartByIPN($ipnRequest);
            // }
            // $response= 'valid IPN request. Cart updated';
            // return response($response, 200)
            //     ->header('Content-Type', 'text/plain');
            return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
        } catch (\Exception $e) {
            // return response($e, 400)
            // ->header('Content-Type', 'text/plain');
            return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
        }
        return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
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
    public function payment_check($id, OrderAction $orderAction)
    {
        if (isset($id)) {
            $order = $orderAction->getById($id);
            if ($order) {
                if (!($order->status == MerchantOrderStatuses::PENDING || $order->status == MerchantOrderStatuses::PENDING->value))
                    Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
                // else 
                // return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");

                // $order = $orderAction->update($id, MerchantOrderStatuses::PENDING->value) ?? $order;
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
                return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=done");
            }
            // return Redirect::to(config('app.url')."/payment-done?&order_id=$id&confirm=failed");
            return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
        }
        return Redirect::to(config('app.url')."/payment-done?&order_id=&confirm=failed");
        return response()->json([], 201);
    }




    // public function createOrderPaymentPage(): void
    // {
    //     try {


    //         $pay = paypage::sendPaymentCode('all')
    //             ->sendTransaction('sale')
    //             ->sendCart($this->order->id, $this->order->total_after_vat, 'test')
    //             ->sendCustomerDetails($this->orderUser->name, $this->orderUser->email ?? 'email@email.com', $this->orderUser->phone, 'test', $this->orderUser->country_name, 'Riyadh', 'SA', '1234', '10.0.0.10')
    //             ->sendShippingDetails($this->order?->branch?->name ?? 'azzam zaid', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234', '10.0.0.10')
    //             //    ->sendShippingDetails('Name', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234','10.0.0.10')
    //             //    ->sendURLs('return_url', 'callback_url')
    //             ->sendLanguage('en')
    //             ->create_pay_page();

    //         //    Log::debug(json_encode($pay->clickpay_core->pt_build()));

    //         // $pay1 =  $pay->create_pay_page();
    //         // $pay1 =  $pay->
    //         // return
    //         $pay;
    //         Log::debug(json_encode($pay));
    //     } catch (\Exception $ex) {
    //         Log::debug($ex->getMessage());
    //     }
    // }


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
