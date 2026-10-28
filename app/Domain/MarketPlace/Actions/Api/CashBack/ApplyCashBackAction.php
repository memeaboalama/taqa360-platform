<?php

namespace App\Domain\MarketPlace\Actions\Api\CashBack;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\CashBack;
use App\Domain\AppBase\Models\CashBackMerchantsChoice;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Cart\CartRepository;
use App\Domain\MarketPlace\Models\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;

class ApplyCashBackAction
{
    protected MerchantOrderRepository $orderRepository;
    protected CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $this->cartRepository = $cartRepository;
    }

    public function apply(array $data)
    {

        DB::beginTransaction();

        // check cart
        $cart = request()->user()->cart;

        if (!$cart) return ["status" => false, "message" => "Error:NoCertDataFound"];

        // check validaty
        $check = CashBack::query()
            ->where('status', '=', 'active')
            ->where('usages_left', '>', 0)
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            ->where('name', '=', $data["cash_back_name"])->first();

        if (!$check) return ["status" => false, "message" => "Error:CashBackIsNotValid"];

        // Check CashBack usage limit
        $totalUsageCount = MerchantOrder::where('cash_back_id', $check->id)
            ->whereNotIn(
                'status',
                [
                    MerchantOrderStatuses::CANCELLED->value,
                    MerchantOrderStatuses::DRAFT->value
                ]
            )
            ->count();

        $userUsageCount = MerchantOrder::where('cash_back_id', $check->id)
            ->whereNotIn(
                'status',

                [
                    MerchantOrderStatuses::CANCELLED->value,
                    MerchantOrderStatuses::DRAFT->value

                ]
            )
            // ->where('status', '!=', MerchantOrderStatuses::CANCELLED)
            ->where('user_id', $cart->user_id)
            ->count();
        $totalUsageCartCount = Cart::where('cash_back_id', $check->id)->count() + $totalUsageCount;
        $userUsageCartCount = Cart::where('cash_back_id', $check->id)
            ->where('user_id', $cart->user_id)
            ->count() + $userUsageCount;


        if ($check->usages_left <= $totalUsageCount || $userUsageCount >= $check->limit_per_user) {
            return [
                'status' => false,
                'message' => $check->usages_left <= $totalUsageCount ? 'Error:' . __('admin::home.Numberofuseshasexpired') : 'Error:' . __('admin::home.Youhavereachedthemaximumcodeusagelimit')
            ];
        } else if ($check->usages_left == 1 && $check->limit_per_user == 1 && Cart::where('cash_back_id', $check->id)->count() != 0) {
            return [
                'status' => false,
                'message' => 'Error:' . __('admin::home.Numberofuseshasexpired')
            ];
        } else if ($check->usages_left <= $totalUsageCartCount || $userUsageCartCount >= $check->limit_per_user) {
            return [
                'status' => false,
                'message' => $check->usages_left <= $totalUsageCartCount ? 'Error:' . __('admin::home.Numberofuseshasexpired') : 'Error:' . __('admin::home.Youhavereachedthemaximumcodeusagelimit')
            ];
        }
        if ($cart->subtotal < $check->min_order_value)   return ["status" => false, "message" => "Error:minordervalueis", "data" => $check->min_order_value];

        if ($check->is_custom && !CashBackMerchantsChoice::where(['cashback_id' => $check->id, 'merchant_id' => $cart->merchant_id])->exists()) {
            return [
                'status' => false,
                'message' => 'Error:thecashbackisnotforthisrestaurant',
            ];
        }

        // update cart with cash_back
        $cart->update([
            "cash_back" => $check->name,
            "cash_back_id" => $check->id,
        ]);



        DB::commit();

        return ["status" => true, "data" => request()->user()->cart];
    }
}
