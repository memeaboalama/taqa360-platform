<?php

namespace App\Support\Services\Order;


use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\WalletTransaction;
use Carbon\Carbon;
use Domain\MarketPlace\Models\MerchantAddon;
use Domain\MarketPlace\Models\MerchantService;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Domain\AppBase\Models\MerchantBranchTypeServices;
class OrderHandleServiceV2
{

    public function fromWallet($user, $total) {
        $withdrawnAmount = 0;
        $walletName = 'UID_'.$user->id.'_Main';
        if($user->hasWallet($walletName)){
            $wallet = $user->getWallet($walletName);
            if ($wallet->balanceFloatNum != 0) {
                $withdrawnAmount = min($wallet->balanceFloatNum, $total);
            }
    }
        return $withdrawnAmount;
    }
    
    public function fromUserMerchantBalance($user, $merchant, $total) {
        $withdrawnAmount = 0;
        $merchantWalletName = $user->id."_".$merchant?->id."_".$merchant?->phone;
        
        if($user->hasWallet($merchantWalletName)){
            $wallet = $user->getWallet($merchantWalletName);
            if ($wallet->balanceFloatNum != 0) {
                $withdrawnAmount = min($wallet->balanceFloatNum, $total);
            }
        }

        return $withdrawnAmount;
    }
    
   

    public function handle($cart, $branch, $user, $date, $time, $data, ?float $totalOverride = null): array
    {
        $total = $totalOverride ?? $cart->total;
        $from_wallet = 0;
        $from_merchant_balance = 0;
        // $package=1;
        
        $merchant = $branch->merchant ?? $cart->merchant;
        $package = $merchant?->packages()->first();
        
        // Fallback for package_id if package is null (though it unlikely should be if merchant exists)
        $package_id = $cart->typeServices?->package_id ?? $package?->id ?? 0;
        
        $discount_rate_type_service=$cart->typeServices?->discount_rate??0;
        $commission_type_service=$cart->typeServices?->commission ?? $package?->commission ?? 0;
        $commission_rate= ($commission_type_service-($discount_rate_type_service * $commission_type_service) / 100) ?? $merchant?->commissionPercentage ?? 0;
        $commission_amount =  number_format(($commission_rate * ($cart->subtotal - $cart->discount)) / 100, 2)??0;
        if ($data['merchantBalance'] == 1) {
            $from_merchant_balance = $this->fromUserMerchantBalance($user, $merchant, $total);
            $total -= $from_merchant_balance;
        }
    
        if ($data['wallet'] == 1 && $total > 0) {
            $from_wallet = $this->fromWallet($user, $total);
            $total -= $from_wallet;
        }


        $list = [
            'merchant_id' => $cart->merchant_id,
            'user_id' => $user->id,
            'merchant_branch_id' => $branch->id,
            'promocode' => $cart?->promocode,
            'promocode_id' => $cart?->promocode_id,
            'cash_back' => $cart?->cash_back,
            'cash_back_id' => $cart?->cash_back_id,
            'service_type_id' => $cart?->service_type_id,
            'order_type' => $branch->type,
            'date' => $date,
            'from' => $time,
            'schedulable' => $data["schedulable"],
            'note' => isset($data["note"]) ? $data["note"] : "",
            'dialcode' => isset($data["dialcode"]) ? $data["dialcode"] : $user->dialcode,
            'client_phone' => isset($data["client_phone"]) && $data["client_phone"] != "" ? $data["client_phone"] : $user->phone,
            'client_name' => isset($data["client_name"]) ? $data["client_name"] : $user->name,
            'payment_type' => 'prepaid',
            'from_wallet' => $from_wallet,
            'from_merchant_balance' => $from_merchant_balance, 
            'channel' => $data["channel"],
            'status' => MerchantOrderStatuses::DRAFT,
            'payment_method' => $data["payment_method"],
            'merchant_name' => $cart->merchant?->company_name,
            'subtotal' => $cart->subtotal,
            'vat' => $cart->tax,
            'discount' => $cart->discount,
            'total_after_vat' => $total,
            'total_after_discount' => $total ,
            'delivery_fee' => $cart?->service_type_id==2?0: (number_format($cart->delivery_fee??0,2) - $cart->deliveryFeeDiscount??0),
            'delivery_fee_discount' => $cart?->service_type_id==2?0: $cart->deliveryFeeDiscount??0,
            'delivery_distance' => $cart?->deliveryDistance??0,
            'package_id' => $package_id,
            'commission_rate' => $commission_rate,
            'commission_amount' =>  $commission_amount,
        ];
        if ($data['gift'])
        {
            if ($data['ask_receipt'])
            {
                $list = array_merge($list, [
                    'gift' => 1,
                    'gift_confidential' => $data['gift_confidential'],
                    'ask_receipt' => 1,
                ]);
            }else{
                $list = array_merge($list, [
                    'gift' => 1,
                    'gift_confidential' => $data['gift_confidential'],
                    'ask_receipt' => 0,
                    'client_long' => $data['client_long'],
                    'client_lat' => $data['client_lat'],
                ]);
            }
        }else{
            // $address = $user->addresses()->find($data["address"]);
            if($cart?->service_type_id!=2)
            {
                $address = $data["address"];
                $addressString = json_encode($address);
                $list = array_merge($list, [
                    'address_id' => $address->id ?? $address['id'] ?? null,
                    'addressInOrder' => $addressString,
                    'client_long' => $address->longitude ?? $address['longitude'] ?? null,
                    'client_lat' => $address->latitude ?? $address['latitude'] ?? null,
                    'gift' => 0,
                    'ask_receipt' => 0,
                ]);
            
            }
        }
        return $list;
    }


}
