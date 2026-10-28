<?php

namespace App\Domain\MarketPlace\Actions\Api\Wallet;

use App\Domain\AppBase\Models\WalletCoupon;
use App\Domain\AppBase\Models\WalletCouponMerchants;
use App\Domain\AppBase\Models\WalletCouponsRecharges;
use App\Domain\User\Models\User;
use Carbon\Carbon;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class ApplyWalletCouponAction
{
    protected $balance;

    public function apply(array $data)
    {
        $user = request()->user();
        $walletCoupon = $data["walletCoupon"];

        $check = WalletCoupon::query()
        ->where('status', '=', 'active')
        ->where('usages_left', '>', 0)
        ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
        ->where('code', '=', $walletCoupon)->first();

        if (!$check) {
            return ["status" => false, "message" => "Error:".__('admin::home.PromoCodeIsNotValid')];
        }
        $useingCount= WalletCouponsRecharges::where(['wallet_coupon_id' => $check->id])->count()??0;
        if ($check->usages_left <= $useingCount || $check->expired_at < Carbon::now()->format('Y-m-d H:i:s')) {
            return ["status" => false, "message" => "Error:".__('admin::home.Thiscodehasbeenusedbefore')];
        }

        if (WalletCouponsRecharges::where(['user_id' => $user->id, 'wallet_coupon_id' => $check->id])->exists()) {
            return ["status" => false, "message" => "Error:".__('admin::home.Thiscodehasbeenusedbefore')];
        }

        $amount = $check->amount_value;
        $wallet = null;
        if (!$check->is_custom) {
            $userWallet = 'UID_'.$user->id.'_Main';
            if (!$user->hasWallet($userWallet)) {
                $wallet = $user->createWallet([
                    'name' => str_replace('_', ":", $userWallet),
                    'description' => "User Main Wallet",
                    'slug' => $userWallet,
                ]);
                $wallet->depositFloat($amount ,[
                    'type' => "MainCoupon",
                    'source' => [
                        'coupon' => $check->code,
                    ]
                ]);
                $this->balance = $wallet->balanceFloatNum;
            }else{
                $wallet = $user->getWallet('UID_'.$user->id.'_Main');
                $wallet->depositFloat($amount ,[
                    'type' => "MainCoupon",
                    'source' => [
                        'coupon' => $check->code,
                    ]
                ]);
                $this->balance = $wallet->balanceFloatNum;
            }

                WalletCouponsRecharges::create([
                    'user_id' => $user->id,
                    'wallet_coupon_id' => $check->id,
                    "wallet_type" => "Main",
                    "wallet_id" => $wallet->id,
                ]);

            return [
                "status" => true,
                "message" => __('admin::home.Thecodehassuccessfully'),
                "walletCoupon" => $walletCoupon,
                "wallet_type" => "Main",
                "wallet_id" => $wallet->id,
                "balance" => $this->balance,
            ];
        }

        $data = [];
        $merchantsOfcode = WalletCouponMerchants::where('wallet_coupon_id',$check->id)->get();

        DB::beginTransaction();
        foreach($merchantsOfcode as $merchantCoupon) {
            $merchant = $merchantCoupon->merchant;
            $merchantId = $merchant->id;
            $merchantWalletName = $user->id."_".$merchantId."_".$merchant->phone;
            $balance = 0;

            if ($user->hasWallet($merchantWalletName)) {
                $wallet = $user->getWallet($merchantWalletName);
                $wallet->depositFloat($amount ,[
                    'type' => "MerchantCoupon",
                    'source' => [
                        'coupon' => $check->code,
                        'merchant_id' => $merchantId,
                        'merchant_name' => $merchant->company_name,
                    ]
                ]);
                $balance = $wallet->balanceFloatNum;
                MerchantUserBalance::where(['user_id' => $user->id, "merchant_id" => $merchantId])->update([
                    'balance' => $wallet->balanceFloatNum
                ]);
            } else {
                try {
                    $wallet = $user->createWallet([
                        'name' => str_replace('_', ":", $merchantWalletName),
                        'slug' => $merchantWalletName,
                        'description' => "User Merchant Wallet",
                        'meta' => [
                            'merchant_id' => $merchantId,
                            'name' => $merchant->company_name,
                        ]
                    ]);
                    $wallet->depositFloat($amount ,[
                        'type' => "MerchantCoupon",
                        'source' => [
                            'coupon' => $check->code,
                            'merchant_id' => $merchantId,
                            'merchant_name' => $merchant->company_name,
                        ]
                    ]);
                    $balance = $wallet->balanceFloatNum;
                    MerchantUserBalance::create([
                        'user_id' => $user->id,
                        'merchant_id' => $merchantId,
                        'balance' => $amount
                    ]);
                } catch (Exception $exception) {
                    log::error('Error creating wallet: ' . $exception->getMessage());
                }
            }
            $data[] = [
                'merchant_name' => $merchant->company_name,
                'balance' => $balance
            ];
        }

        DB::commit();
         WalletCouponsRecharges::create([
            'user_id' => $user->id,
            'wallet_coupon_id' => $check->id,
            "wallet_type" => "custom",
            "wallet_id" => $wallet->id,
        ]);
        return [
            "status" => true,
            "message" => __('admin::home.Thecodehassuccessfully'),
            "walletCoupon" => $walletCoupon,
            "balance" => $data
        ];
    }
}
