<?php

namespace App\Domain\Ecommerce\Actions\Web\Coupon;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\Promocode;
use App\Domain\Ecommerce\Models\WebCart;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplyWebPromocodeAction
{
    public function apply(array $data, WebCart $cart)
    {
        DB::beginTransaction();

        try {
            $check = Promocode::query()
                ->where('status', '=', 'active')
                ->where('usages_left', '>', 0)
                ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
                ->where('code', '=', $data["promocode"])
                ->first();

            if (!$check) {
                DB::rollBack();
                return ["status" => false, "message" => __('ecommerce::ecommerce.promo_code_invalid')];
            }

            $totalUsageCount = MerchantOrder::where('promocode_id', $check->id)
                ->whereNotIn('status', [
                    MerchantOrderStatuses::CANCELLED->value,
                    MerchantOrderStatuses::DRAFT->value
                ])
                ->count();

            $userUsageCount = MerchantOrder::where('promocode_id', $check->id)
                ->whereNotIn('status', [
                    MerchantOrderStatuses::CANCELLED->value,
                    MerchantOrderStatuses::DRAFT->value
                ])
                ->where('user_id', $cart->user_id)
                ->count();

            $totalUsageCartCount = WebCart::where('promocode_id', $check->id)->count() + $totalUsageCount;
            $userUsageCartCount = WebCart::where('promocode_id', $check->id)
                ->where('user_id', $cart->user_id)
                ->count() + $userUsageCount;

            if ($check->usages_left <= $totalUsageCount || $userUsageCount >= $check->limit_per_user) {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => $check->usages_left <= $totalUsageCount
                        ? __('ecommerce::ecommerce.promo_code_expired')
                        : __('ecommerce::ecommerce.promo_code_limit_reached')
                ];
            } else if ($check->usages_left == 1 && $check->limit_per_user == 1 && WebCart::where('promocode_id', $check->id)->count() != 0) {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => __('ecommerce::ecommerce.promo_code_expired')
                ];
            } else if ($check->usages_left <= $totalUsageCartCount || $userUsageCartCount >= $check->limit_per_user) {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => $check->usages_left <= $totalUsageCartCount
                        ? __('ecommerce::ecommerce.promo_code_expired')
                        : __('ecommerce::ecommerce.promo_code_limit_reached')
                ];
            }

            if ($cart->subtotal < $check->min_order_value) {
                DB::rollBack();
                return [
                    "status" => false,
                    "message" => __('ecommerce::ecommerce.min_order_value', ['value' => $check->min_order_value])
                ];
            }

            if ($check->is_custom) {
                $merchantIds = $cart->cartItems()
                    ->with('item:id,merchant_id')
                    ->get()
                    ->pluck('item.merchant_id')
                    ->filter()
                    ->unique()
                    ->values();

                if ($merchantIds->count() !== 1 || !$check->appliesToMerchant($merchantIds->first())) {
                    DB::rollBack();
                    return [
                        'status' => false,
                        'message' => __('ecommerce::ecommerce.promo_code_not_for_merchant'),
                    ];
                }
            }

            $cart->update([
                "promocode" => $check->code,
                "promocode_id" => $check->id,
            ]);

            DB::commit();

            return ["status" => true, "data" => $cart->fresh()];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Apply web promocode error: ' . $e->getMessage());
            return ["status" => false, "message" => __('ecommerce::ecommerce.promo_code_error')];
        }
    }
}
