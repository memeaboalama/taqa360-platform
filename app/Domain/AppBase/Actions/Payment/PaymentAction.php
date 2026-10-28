<?php

namespace App\Domain\AppBase\Actions\Payment;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Repositories\PaymentRepository;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\MarketPlace\Repositories\Wallet\WalletTransactionRepository;
use App\Payment\Payment;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentAction
{

    private PaymentRepository $paymentRepository;
    private MerchantOrderRepository $merchantOrderRepository;
    private WalletOrderAction $walletOrderAction;

    private WalletTransactionRepository $walletTransactionRepository;


    public function __construct(PaymentRepository $paymentRepository, MerchantOrderRepository $merchantOrderRepository, WalletTransactionRepository $walletTransactionRepository, WalletOrderAction $walletOrderAction)
    {
        $this->paymentRepository = $paymentRepository;
        $this->merchantOrderRepository = $merchantOrderRepository;
        $this->walletTransactionRepository = $walletTransactionRepository;
        $this->walletOrderAction = $walletOrderAction;
    }

    public function handle(array $data, Model $model)
    {
        return Payment::pay($data, $model);
    }


    public function checkOnCreate(array $data, Model $model)
    {
        if ($data["status"] && $data["status"] == 'paid' && $model) {
            $order = $this->merchantOrderRepository->find(['*'], ['id' => $data["key"]]);
            $payment =
                $this->paymentRepository->find(cols: ['id', 'payment_id', 'status', 'amount', 'user_id', 'payable_type', 'payable_id', 'channel'], conditions: ['payment_id' => $data["id"]]);
            if (!$payment)
                return null;
            $checkOrder = $this->walletOrderAction->CheckIfUserHaveBlanceOrderLowerThanOrEquleOrderWalletPayment($order);
            if ($checkOrder && !$checkOrder["status"]) {
                $order->update([
                    'from_wallet' => 0,
                    'from_merchant_balance' => 0,
                    'status' => MerchantOrderStatuses::CANCELLED,
                    'admin_note' => $checkOrder["message"]
                ]);
                return ["status" => 'failed', "message" => $checkOrder["message"], "data" => null];
            }
            $this->walletOrderAction->withdraw($order, "Wallet Order Pay");
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
            return $order;
        }
        return null;
    }
    public function check(array $data)
    {
        $fetch_payment = Payment::paymentStatus($data);
        if (!$fetch_payment &&  $data["id"]) {
            $payment =
                $this->paymentRepository->find(cols: ['id', 'payment_id', 'status', 'amount', 'user_id', 'payable_type', 'payable_id', 'channel'], conditions: ['payment_id' => $data["id"]]);
            // if(!$payment)
            return $payment;
        } else {
            $payment = $this->paymentRepository->find(cols: ['id', 'payment_id', 'status', 'amount', 'user_id', 'payable_type', 'payable_id', 'channel'], conditions: ['payment_id' => $fetch_payment["id"]]);
            if (!$payment)
                return $payment;
            $payment->update(['status' => $fetch_payment["status"]]);
        }

        if ($payment->status == "paid" || ($fetch_payment && $fetch_payment["status"]  == "paid")) {
            $order = $this->merchantOrderRepository->find(['*'], ['id' => $payment?->payable_id]);
            if (($order->status == MerchantOrderStatuses::DRAFT || $order->status == MerchantOrderStatuses::DRAFT->value)) {
                $checkOrder = $this->walletOrderAction->CheckIfUserHaveBlanceOrderLowerThanOrEquleOrderWalletPayment($order);
                if ($checkOrder && !$checkOrder["status"]) {
                    $order->update([
                        'from_wallet' => 0,
                        'from_merchant_balance' => 0,
                        'status' => MerchantOrderStatuses::CANCELLED,
                        'admin_note' => $checkOrder["message"]
                    ]);
                    return ["status" => 'failed', "message" => $checkOrder["message"], "data" => null];
                }
                $this->walletOrderAction->withdraw($order, "Wallet Order Pay");
            }
            if (($order->status == MerchantOrderStatuses::DRAFT || $order->status == MerchantOrderStatuses::DRAFT->value))
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
        } else if ($payment->status == "failed" || ($fetch_payment && $fetch_payment["status"]  == "failed")) {

            $this->paymentRepository->update(id: $payment->id, data: ["status" => $fetch_payment["status"]]);
            $order = $this->merchantOrderRepository->find(['*'], ['id' => $payment?->payable_id]);
            if (($order->status != MerchantOrderStatuses::DRAFT && $order->status != MerchantOrderStatuses::DRAFT->value))
                $order->update(['status' => MerchantOrderStatuses::DRAFT]);
        } else {
            $this->paymentRepository->update(id: $payment->id, data: ["status" => $fetch_payment["status"]]);
            $order = $this->merchantOrderRepository->find(['*'], ['id' => $payment?->payable_id]);
            if (($order->status != MerchantOrderStatuses::DRAFT && $order->status != MerchantOrderStatuses::DRAFT->value))
                $order->update(['status' => MerchantOrderStatuses::DRAFT]);
            Log::channel('slack')->error("payment_check Moyasar payment Status: {" . $fetch_payment["status"] . "}, Current Paypent : {" . $payment->status . "}");
        }

        return $payment;
    }

    public function checkDepositoLD(array $data)
    {
        $fetch_payment = Payment::paymentStatus($data);
        // if (!$fetch_payment ) return false;
        if (!$fetch_payment &&  $data["id"] && $data["status"]) {
            $payment =
                $this->paymentRepository->find(cols: ['id', 'payment_id', 'status', 'amount', 'user_id', 'payable_type', 'payable_id', 'channel'], conditions: ['payment_id' => $data["id"]]);
            if (!$payment)
                return $payment;
            $payment->update(['status' => $data["status"]]);
        } else {
            $payment = $this->paymentRepository->find(cols: ['id', 'payment_id', 'status', 'amount', 'user_id', 'payable_type', 'payable_id', 'channel'], conditions: ['payment_id' => $fetch_payment["id"]]);
            if (!$payment)
                return $payment;
            $payment->update(['status' => $fetch_payment["status"]]);
        }
        // $payment =(!$fetch_payment )?
        //     :$this->paymentRepository->find(cols: ['id', 'payment_id', 'status', 'amount', 'user_id', 'payable_type', 'payable_id', 'channel'], conditions: ['payment_id' => $fetch_payment["id"]]);

        if (!$payment)
            return $payment;
        else if ($payment->status == "paid") {
            $walletDeposit = $this->walletTransactionRepository->find(['*'], ['id' => $payment?->payable_id]);
            $walletDeposit->update(["confirmed" => 1, "status" => "confirmed"]);
        } else if ($payment->status == "failed") {
            $this->paymentRepository->update(id: $payment->id, data: ["status" => $fetch_payment["status"]]);
            $walletDeposit = $this->walletTransactionRepository->find(['*'], ['id' => $payment?->payable_id]);
            $walletDeposit->update(["confirmed" => 0, "status" => "failed"]);
        } else {
            $this->paymentRepository->update(id: $payment->id, data: ["status" => $fetch_payment["status"]]);
        }

        return $payment;
    }
    public function checkDeposit(array $data)
    {
        $fetch_payment = Payment::paymentStatus($data);
        if (!$fetch_payment) return false;

        $payment = $this->paymentRepository->find(cols: ['id', 'payment_id', 'status', 'amount', 'user_id', 'payable_type', 'payable_id', 'channel'], conditions: ['payment_id' => $fetch_payment["id"]]);

        if (!$payment) return false;

        $walletDeposit = $this->walletTransactionRepository->find(['*'], ['id' => $payment?->payable_id]);
        if ($payment->status == "paid") {
            $walletDeposit->update(["confirmed" => 1, "status" => "confirmed"]);
        } else {
            $walletDeposit->update(["confirmed" => 0, "status" => "failed"]);
        }

        if ($payment->status == "initiated" || $payment->status == "paid" || $payment->status == "failed")
            $this->paymentRepository->update(id: $payment->id, data: ["status" => $fetch_payment["status"]]);

        return $payment;
    }
}
