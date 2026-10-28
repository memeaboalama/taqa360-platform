<?php

namespace App\Domain\MarketPlace\Actions\Api\Wallet;


use App\Domain\AppBase\Models\WalletTransaction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Wallet\WalletTransactionRepository;
use App\Domain\User\Models\User;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Domain\AppBase\Models\CashBack;

class WalletOrderAction
{

    public function CheckIfUserHaveBlanceOrderLowerThanOrEquleOrderWalletPayment(MerchantOrder $order)
    {
        if ($order->from_wallet > 0) {
            $userMainwallet = $order->user->getWallet('UID_' . $order->user_id . '_Main');
            $mainWalletavailableBalance = $userMainwallet->balanceFloatNum;
            if ($order->from_wallet > $mainWalletavailableBalance)
                return ["status" => false, "message" => "Main Wallet dose net have balance!", "data" => null];
        }
        if ($order->from_merchant_balance > 0) {

            $userMerchantWallet =  $order->user->getWallet($order->user_id . "_" . $order->merchant_id . "_" . $order->merchant->phone);
            $merchantWalletavailableBalance =  $userMerchantWallet->balanceFloatNum;
            if ($order->from_merchant_balance > $merchantWalletavailableBalance)
                return ["status" => false, "message" => "User Merchant Wallet dose net have balance!", "data" => null];
        }
        return  ["status" => true, "message" => "User Have wallet blance", "data" => null];
    }
    public function withdraw(MerchantOrder $order, string $type, bool $confirmed = true)
    {

        $checkOrder = $this->CheckIfUserHaveBlanceOrderLowerThanOrEquleOrderWalletPayment($order);
        if ($checkOrder && !$checkOrder['status'])
            return $checkOrder;
        if ($order->from_wallet > 0) {
            $userMainwallet = $order->user->getWallet('UID_' . $order->user_id . '_Main');
            $mainWalletavailableBalance = $userMainwallet->balanceFloatNum;
            // if ($order->from_wallet > $mainWalletavailableBalance) 
            // return ["status" => false, "message" => "Main Wallet dose net have balance!", "data" => null];
            if ($mainWalletavailableBalance < $order->from_wallet)
                throw new \Exception("Main Wallet dose net have balance", 409);
            // $wallet = $order->user->getWallet('UID_' . $order->user_id . '_Main');
            $mainWallet = $userMainwallet->withdrawFloat($order->from_wallet, [
                'type' => $type,
                'source' => [
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ],
            ], confirmed: $confirmed);

            WalletTransaction::create([
                'user_id' => $order->user->id,
                'type' => 'withdraw',
                'amount' => $order->from_wallet,
                'confirmed' => $confirmed ? 1 : 0,
                'status' => $confirmed ? 'confirmed' : 'created',
                'uuid' => $mainWallet?->uuid ?? Str::uuid(),
                'meta' => [
                    'wallet' => $userMainwallet->id,
                    'wallet_slug' => $userMainwallet->slug,
                    'type' => $type,
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                    'transaction' => $mainWallet->id,
                ]
            ]);
        }

        if ($order->from_merchant_balance > 0) {

            $userMerchantWallet =  $order->user->getWallet($order->user_id . "_" . $order->merchant_id . "_" . $order->merchant->phone);
            $merchantWalletavailableBalance =  $userMerchantWallet->balanceFloatNum;
            // if ($order->from_merchant_balance > $merchantWalletavailableBalance) 
            // return ["status" => false, "message" => "User Merchant Wallet dose net have balance!", "data" => null];
            if ($merchantWalletavailableBalance < $order->from_merchant_balance)
                throw new \Exception("User Merchant Wallet dose net have balance!", 409);

            $merchantWallet = $userMerchantWallet->withdrawFloat($order->from_merchant_balance, [
                'type' => $type,
                'source' => [
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ],
            ], confirmed: $confirmed);
            WalletTransaction::create([
                'user_id' => $order->user->id,
                'type' => 'withdraw',
                'amount' => $order->from_merchant_balance,
                'confirmed' => $confirmed ? 1 : 0,
                'status' => $confirmed ? 'confirmed' : 'created',
                'uuid' => $merchantWallet?->uuid ?? Str::uuid(),
                'meta' => [
                    'wallet' => $userMerchantWallet->id,
                    'wallet_slug' => $userMerchantWallet->slug,
                    'type' => $type,
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                    'transaction' => $merchantWallet->id,
                ]
            ]);
            MerchantUserBalance::where([
                'user_id' => $order->user_id,
                'merchant_id' => $order->merchant_id,
            ])->update([
                'balance' => DB::raw('balance - ' . $order->from_merchant_balance),
            ]);
        }
        return  ["status" => true, "message" => "Error:Branchisnotfoundpleaseemptycart!", "data" => null];
    }

    public function add_wallet_refund_payment(MerchantOrder $order, string $type, $amount, bool $confirmed = true): void
    {
        $userWallet = 'UID_' . $order->user_id . '_Main';
        $user = User::where('id', $order->user_id)->first();
        if (!$user->hasWallet($userWallet)) {
            $wallet = $user->createWallet([
                'name' => str_replace('_', ":", $userWallet),
                'description' => "User Main Wallet",
                'slug' => $userWallet,
            ]);
        } else {
            $wallet = $user->getWallet('UID_' . $order->user_id . '_Main');
        }
        $mainWallet = $wallet->depositFloat($amount, [
            'type' => $type,
            'source' => [
                'order_code' => $order->code,
                'merchant_id' => $order->merchant_id,
                'merchant_name' => $order->merchant->company_name,
            ],
        ], confirmed: $confirmed);
        WalletTransaction::create([
            'user_id' => $order->user_id,
            'type' => 'deposit',
            'amount' => $amount,
            'confirmed' => $confirmed ? 1 : 0,
            'status' => $confirmed ? 'confirmed' : 'created',
            'uuid' => $mainWallet?->uuid ?? Str::uuid(),
            'meta' => [
                'wallet' => $wallet->id,
                'wallet_slug' => $wallet->slug,
                'type' => $type,
                'order_code' => $order->code,
                'merchant_id' => $order->merchant_id,
                'merchant_name' => $order->merchant->company_name,
                'transaction' => $mainWallet->id,
            ]
        ]);
    }

    public function add_cash_back(MerchantOrder $order, string $type, bool $confirmed = true): void
    {
        $check = $this->validCashBack($order);
        if (!$check)
            return;
        $user = User::where('id', $order->user_id)->first();
        $userWalletId = 'UID_' . $order->user_id . '_Main';
        if ($check->merchant_id) {
            $userWalletId = $order->user_id . "_" . $order->merchant_id . "_" . $order->merchant->phone;
            $userWallet = $user->wallets()
                ->where('name', '==', $userWalletId)
                // ->where('description', 'User Merchant Wallet')
                // ->where('balance', '>', 0)
                ->get();
        } else {
            $userWallet = $user->wallets()
                ->where('name', '==', $userWalletId)
                // ->where('description', 'User Merchant Wallet')
                // ->where('balance', '>', 0)
                ->get();
        }
        // else
        // $userWallet = 'UID_'.$order->user_id.'_Main';

        if (!$user->hasWallet($userWalletId)) {
            if (!$check->merchant_id)
                $wallet = $user->createWallet([
                    'name' => str_replace('_', ":", $userWalletId),
                    'description' => "User Main Wallet",
                    'slug' => $userWalletId,
                ]);
            else
                $wallet = $user->createWallet([
                    'name' => str_replace('_', ":", $userWalletId),
                    'slug' => $userWalletId,
                    'description' => "User Merchant Wallet",
                    'meta' => [
                        'merchant_id' => $check->merchant_id,
                        'name' => $check->merchant->company_name,
                    ]
                ]);
        } else {
            $wallet = $user->getWallet($userWalletId);
        }

        $amount = $this->calculationCashBack($order, $check);
        $mainWallet = $wallet->depositFloat($amount, [

            'type' => $type,
            'source' => [
                'order_code' => $order->code,
                'merchant_id' => $order->merchant_id,
                'merchant_name' => $order->merchant->company_name,
            ],
        ], confirmed: $confirmed);
        WalletTransaction::create([
            'user_id' => $order->user_id,
            'type' => 'deposit',
            'amount' => $amount,
            'confirmed' => $confirmed ? 1 : 0,
            'status' => $confirmed ? 'confirmed' : 'created',
            'uuid' => $mainWallet?->uuid ?? Str::uuid(),
            'meta' => [
                'wallet' => $wallet->id,
                'wallet_slug' => $wallet->slug,
                'type' => $type,
                'order_code' => $order->code,
                'merchant_id' => $order->merchant_id,
                'merchant_name' => $order->merchant->company_name,
            ]
        ]);
    }
    protected function calculationCashBack(MerchantOrder $order, $check): float
    {
        if (!$order->cash_back_id || !$check) {
            return  0;
        }

        if ($check->type === 'flat') {
            return $this->calculateFlatCash($check->discount_value, $order);
        } elseif ($check->type === 'percent') {
            return $this->calculatePercentageCash($check->discount_value, $check->max_discount, $order);
        }

        return  0;
    }
    public function validCashBack(MerchantOrder $order)
    {
        $check = CashBack::query()
            ->where('status', '=', 'active')
            ->where('usages_left', '>', 0)
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            ->where('id', '=', $order->cash_back_id)->first();

        if (!$check) return false;

        if ($order->subtotal < $check->min_order_value) return false;

        return $check;
    }
    private function calculateFlatCash(float $discountValue = 0, MerchantOrder $order): float
    {
        return  min($discountValue, (($order->subtotal + $order->delivery_fee) - $order->from_wallet - $order->from_merchant_balance));
    }

    private function calculatePercentageCash(float $discountValue = 0, float $maxDiscount = 0, MerchantOrder $order): float
    {
        $discountAmount = (($order->subtotal + $order->delivery_fee) - $order->from_wallet - $order->from_merchant_balance) * ($discountValue / 100);
        return  min($discountAmount, $maxDiscount);
    }
    public function deposit(MerchantOrder $order, string $type, bool $confirmed = true): void
    {
        if ($order->from_wallet > 0) {
            $wallet = $order->user->getWallet('UID_' . $order->user_id . '_Main');
            $mainWallet = $wallet->depositFloat($order->from_wallet, [
                'type' => $type,
                'source' => [
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ],
            ], confirmed: $confirmed);

            WalletTransaction::create([
                'user_id' => $order->user->id,
                'type' => 'deposit',
                'amount' => $order->from_wallet,
                'confirmed' => $confirmed ? 1 : 0,
                'status' => $confirmed ? 'confirmed' : 'created',
                'uuid' => $mainWallet?->uuid ?? Str::uuid(),
                'meta' => [
                    'wallet' => $wallet->id,
                    'wallet_slug' => $wallet->slug,
                    'type' => $type,
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ]
            ]);
        }

        if ($order->from_merchant_balance > 0) {
            $wallet = $order->user->getWallet($order->user_id . "_" . $order->merchant_id . "_" . $order->merchant->phone);
            $merchantWallet = $wallet->depositFloat($order->from_merchant_balance, [
                'type' => $type,
                'source' => [
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ],
            ], confirmed: $confirmed);

            WalletTransaction::create([
                'user_id' => $order->user->id,
                'type' => 'deposit',
                'amount' => $order->from_merchant_balance,
                'confirmed' => $confirmed ? 1 : 0,
                'status' => $confirmed ? 'confirmed' : 'created',
                'uuid' => $merchantWallet?->uuid ?? Str::uuid(),
                'meta' => [
                    'wallet' => $wallet->id,
                    'wallet_slug' => $wallet->slug,
                    'type' => $type,
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ]
            ]);
            MerchantUserBalance::where([
                'user_id' => $order->user_id,
                'merchant_id' => $order->merchant_id,
            ])->update([
                'balance' => DB::raw('balance + ' . $order->from_merchant_balance),
            ]);
        }
    }
    public function depositSuccessPayment(MerchantOrder $order,string $transaction_id, string $type, bool $confirmed = true): void
    {
        if ($order->total_after_vat > 0) {
            $wallet = $order->user->getWallet('UID_' . $order->user_id . '_Main');
            $mainWallet = $wallet->depositFloat($order->total_after_vat, [
                'type' => $type,
                'source' => [
                    'transaction_id' => $transaction_id,
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ],
            ], confirmed: $confirmed);

            WalletTransaction::create([
                'user_id' => $order->user->id,
                'type' => 'deposit',
                'amount' => $order->total_after_vat,
                'confirmed' => $confirmed ? 1 : 0,
                'status' => $confirmed ? 'confirmed' : 'created',
                'uuid' => $mainWallet?->uuid ?? Str::uuid(),
                'meta' => [
                    'transaction_id' => $transaction_id,
                    'wallet' => $wallet->id,
                    'wallet_slug' => $wallet->slug,
                    'type' => $type,
                    'order_code' => $order->code,
                    'merchant_id' => $order->merchant_id,
                    'merchant_name' => $order->merchant->company_name,
                ]
            ]);
        }

        
    }
}
