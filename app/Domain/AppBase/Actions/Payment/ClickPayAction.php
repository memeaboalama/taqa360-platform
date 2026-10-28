<?php

namespace App\Domain\AppBase\Actions\Payment;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Repositories\PaymentRepository;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\MarketPlace\Repositories\Wallet\WalletTransactionRepository;
// use Clickpaysa\Laravel_package\Facades\paypage;
use App\Payment\ClickPay\Facades\paypage;
use Domain\AppBase\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ClickPayAction
{
    private PaymentRepository $paymentRepository;
    private MerchantOrderRepository $merchantOrderRepository;
    private WalletOrderAction $walletOrderAction;



    public function __construct()
    {
        $this->paymentRepository = new PaymentRepository(new Payment());
        $this->merchantOrderRepository = new MerchantOrderRepository(new MerchantOrder());
        $this->walletOrderAction = new WalletOrderAction();
    }

    public function handle(int $token, $lang)
    {
        // $order = $this->merchantOrderRepository->findByCols(cols: ['id' => $token], with: ["user", "merchants", "merchant_branch"]);
        $order = $this->merchantOrderRepository->findByCols(cols: ['id' => $token], with: ["user"]);

        if ($order) {
            if (($order->status == MerchantOrderStatuses::PENDING || $order->status == MerchantOrderStatuses::PENDING->value)) {
                $order->redirect_url = config('app.url')."/payment-done?&order_id=$token&confirm=done";
                // $order->tran_ref = $pay->tran_ref;
                $order->payment_url = config('app.url')."/payment-done?&order_id=$token&confirm=done";
                return $order;
            } elseif (($order->status == MerchantOrderStatuses::EXPIRED || $order->status == MerchantOrderStatuses::EXPIRED->value)) {
                $order->redirect_url = config('app.url')."/payment-done?&order_id=$token&confirm=failed";
                // $order->tran_ref = $pay->tran_ref;
                $order->payment_url = config('app.url')."/payment-done?&order_id=$token&confirm=failed";
                return $order;
            } elseif (($order->status == MerchantOrderStatuses::CANCELLED || $order->status == MerchantOrderStatuses::CANCELLED->value)) {
                $order->redirect_url = config('app.url')."/payment-done?&order_id=$token&confirm=failed";
                // $order->tran_ref = $pay->tran_ref;
                $order->payment_url = config('app.url')."/payment-done?&order_id=$token&confirm=failed";
                return $order;
            } elseif (($order->status == MerchantOrderStatuses::DRAFT || $order->status == MerchantOrderStatuses::DRAFT->value)) {
                $order->PriceAtSmallestUnit = $order->total_after_vat * 100;
                $order->key = $order->id;

                $pay = paypage::sendPaymentCode('all')
                    ->sendTransaction('sale')
                    // ->sendFramed(true)
                    ->sendCart($order->id, $order->total_after_vat, 'Order  ' . $order->code . '')
                    // ->sendCustomerDetails(name: $order->user->name, email: $order->user->email, phone: $order->user->phone, address: 'order address', city: $order->user->country_name, state: $order->user?->country_name ?? 'Riyadh', country: $order->user?->country_code ?? 'SA', zip: '1234', ip: null)
                    // ->sendCustomerDetails($order->user->name, $order->user->email ?? 'email@email.com', $order->user->phone, 'order address', $order->user->country_name, 'Riyadh', 'SA', '1234', '10.0.0.10')
                    // ->sendHideShipping(true)
                    
                    
                    // ->sendShippingDetails($order?->branch?->name ?? 'azzam zaid', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234', '10.0.0.10')
                    //    ->sendShippingDetails('Name', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234','10.0.0.10')
                    //    ->sendURLs(order.payment.save, 'marketplace:order.payment.clickpay.check')
                    //    ->sendURLs(route('marketplace:order.payment.clickpay.save', ['lang' => app()->getLocale(), 'token' => $order->id]), route('marketplace:order.payment.clickpay.check', ['lang' => app()->getLocale(), 'token' => $order->id]))
                    //    ->sendURLs(route('marketplace:order.payment.clickpay.status', ['lang' => $lang, 'id' => $order->id]), route('marketplace:order.payment.clickpay.check', ['lang' => $lang, 'token' => $order->id]))
                    //    ->sendURLs(route('marketplace:order.payment.clickpay.status', ['id' => $order->id]), route('marketplace:order.payment.clickpay.check', ['lang' => $lang, 'token' => $order->id]))
                    // ->sendURLs(route('marketplace:order.payment.clickpay.status', ['id' => $order->id]), route('marketplace:order.payment.clickpay.callback'))
                    // ->sendURLs(route('marketplace:order.payment.clickpay.status'), route('marketplace:order.payment.clickpay.callback'))
                    // ->sendURLs(route('order.payment.clickpay.status'), route('order.payment.clickpay.callback'))
                    // ->sendURLs(null, route('order.payment.clickpay.callback'))
                    // ->sendURLs(route('order.payment.clickpay.status'), route('order.payment.clickpay.callback'))
                    ->sendURLs(route('marketplace:order.payment.clickpay.check', ['id' => $token]), route('order.payment.clickpay.callback'),true)
                    ->sendLanguage($lang)
                    ->create_pay_frame();
                if ($pay && $pay->success) {
                    // return $pay;
                    $order->redirect_url = $pay->redirect_url;
                    $order->tran_ref = $pay->tran_ref;
                    $order->payment_url = $pay->payment_url;
                    Session::put('click_pay_merchant_iframe_' . $token . '', [
                        'order_id' => $order->id,
                        'redirect_url' => $order->redirect_url,
                        'tran_ref' => $order->tran_ref,
                        'payment_url' => $order->payment_url,
                    ]);
                }
            }
            return $order;
        }
        return null;
    }
   

    public function updateCartByIPN($requestData)
    {
        $cartId = $requestData->getCartId();
        $status = $requestData->getStatus();
        $tran_ref = $requestData->getTranRef();
        $code = $requestData->getCode();
        $client_key = $requestData->getIpnRequestClientKey();
        Log::channel('daily')->info("order_id: " . $cartId . " status: " . $status . " tranRef: " . $tran_ref . " code: " . $code . " clientKey: " . $client_key . "");
        return  $this->update_cart_by_ipn(
            $cartId,
            $status,
            $tran_ref,
            $code
        );
    }
    public function update_cart_by_ipn(
        $cartId,
        $status,
        $tran_ref,
        $code
    ) {

        $order = $this->merchantOrderRepository->find(['*'], ['id' => $cartId]);
        if (!$order || $status != 'A')
            return null;
        else if (($order->status == MerchantOrderStatuses::PENDING || $order->status == MerchantOrderStatuses::PENDING->value))
            return $order;
        else if (($order->status == MerchantOrderStatuses::DRAFT || $order->status == MerchantOrderStatuses::DRAFT->value)) {

            $checkOrder = $this->walletOrderAction->CheckIfUserHaveBlanceOrderLowerThanOrEquleOrderWalletPayment($order);
            if ($checkOrder && !$checkOrder["status"]) {
                $order->update([
                    'from_wallet' => 0,
                    'from_merchant_balance' => 0,
                    'status' => MerchantOrderStatuses::CANCELLED,
                    'admin_note' => $checkOrder["message"]
                ]);
                $this->walletOrderAction->depositSuccessPayment($order,$tran_ref, "Wallet Canceled Order Success Payment Order: [transaction_id: " . $tran_ref .", id: " . $order->id .", code: " . $order->code . "] ");
                return $order;
            }
            $this->walletOrderAction->withdraw($order, "Wallet Pay Order: [id: " . $order->id . ", Code: " . $order->code . "] ");
            $order->update(['status' => MerchantOrderStatuses::PENDING]);
            $cart = $order->user?->cart;
            try {
                if ($cart && $cart->exists()) {
                    if ($cart->cartChoices() && $cart->cartChoices()->exists())
                        $cart->cartChoices()->delete();
                    if ($cart->cartItems() && $cart->cartItems()->exists())
                        $cart->cartItems()->delete();
                    $cart->delete();
                }
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        }
        return $order;

        //your logic .. updating cart in DB, notifying the customer ...etc
    }
}
