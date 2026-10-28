<?php

namespace App\Domain\Ecommerce\Actions\Web\Order;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\Promocode;
use App\Domain\AppBase\Models\CashBack;
use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Models\CashBackMerchantsChoice;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\User\Models\User;
use App\Domain\User\Models\UserAddress;
use App\Support\Services\Branch\BranchStatusService;
use App\Support\Services\Order\OrderHandleServiceV2;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateWebOrderAction
{
    private BranchRepository $merchantBranch;
    private MerchantOrderRepository $merchantOrderRepository;
    private OrderHandleServiceV2 $orderHandleService;

    public function __construct(
        BranchRepository $merchantBranch,
        MerchantOrderRepository $merchantOrderRepository,
        OrderHandleServiceV2 $orderHandleService
    ) {
        $this->merchantBranch = $merchantBranch;
        $this->merchantOrderRepository = $merchantOrderRepository;
        $this->orderHandleService = $orderHandleService;
    }

    public function handle(array $data, User $user)
    {
        $cart = $user->webCart;
        $date = $data["date"] ?? Carbon::now()->format('Y-m-d');
        $time = isset($data["time"]) && $data["time"] != "" ? \DateTime::createFromFormat('g:i a', $data["time"])->format('H:i:s') : Carbon::now()->format('h:i A');

        if (!$cart) return ["status" => false, "message" => "Error:NoCartData", "data" => null];

        $firstItem = $cart->cartItems()->first();
        if (!$firstItem) return ["status" => false, "message" => "Error:CartIsEmpty", "data" => null];

        $merchantId = $firstItem->item->merchant_id;

        $cart->merchant_id = $merchantId;

        $branch = $firstItem->item->merchant->branches()->first();

        if (!$branch) {
            return ["status" => false, "message" => "Error:Branchisnotfoundpleaseemptycart!", "data" => null];
        }

        $cart->merchant_branch_id = $branch->id;
        $cart->setAttribute('branch', $branch);

        if (!$branch->status) return ["status" => false, "message" => "Error:Restaurantisclosedatthistime!", "data" => null];

        if (isset($data["promocode"]) || $cart->promocode) {
            $check = Promocode::query()
                ->where('status', '=', 'active')
                ->where('usages_left', '>', 0)
                ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
                ->where('code', '=', $data["promocode"] ?? $cart->promocode)->first();

            if (!$check) return ["status" => false, "message" => "Promo Code Is Not Valid"];

            $totalUsageCount = MerchantOrder::where('promocode_id', $check->id)
                ->whereNotIn(
                    'status',
                    [
                        MerchantOrderStatuses::CANCELLED->value,
                        MerchantOrderStatuses::DRAFT->value
                    ]
                )
                ->count();

            $userUsageCount = MerchantOrder::where('promocode_id', $check->id)
                ->whereNotIn(
                    'status',
                    [
                        MerchantOrderStatuses::CANCELLED->value,
                        MerchantOrderStatuses::DRAFT->value
                    ]
                )
                ->where('user_id', $cart->user_id)
                ->count();


            if ($check->usages_left <= $totalUsageCount || $userUsageCount >= $check->limit_per_user) {
                return [
                    'status' => false,
                    'message' => $check->usages_left < $totalUsageCount ? 'Error:' . __('admin::home.Numberofuseshasexpired') : 'Error:' . __('admin::home.Youhavereachedthemaximumcodeusagelimit')
                ];
            }
            if ($cart->subtotal < $check->min_order_value)   return ["status" => false, "message" => "Error:minordervalueis", "data" => $check->min_order_value];

            if (!$check->appliesToMerchant($merchantId)) {
                return [
                    'status' => false,
                    'message' => 'Error:thepromocodeisnotforthisrestaurant',
                ];
            }
        } else if (isset($data["cash_back_name"]) || $cart->cash_back) {
            $check = CashBack::query()
                ->where('status', '=', 'active')
                ->where('usages_left', '>', 0)
                ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
                ->where('name', '=', $data["cash_back_name"] ?? $cart->cash_back)->first();

            if (!$check) return ["status" => false, "message" => "Promo Code Is Not Valid"];

            $totalUsageCount = MerchantOrder::where('cash_back_id', $check->id)
                ->whereNotIn(
                    'status',
                    [
                        MerchantOrderStatuses::CANCELLED->value,
                        MerchantOrderStatuses::DRAFT->value
                    ]
                )->count();

            $userUsageCount = MerchantOrder::where('cash_back_id', $check->id)
                ->whereNotIn(
                    'status',
                    [
                        MerchantOrderStatuses::CANCELLED->value,
                        MerchantOrderStatuses::DRAFT->value
                    ]
                )
                ->where('user_id', $cart->user_id)
                ->count();


            if ($check->usages_left <= $totalUsageCount || $userUsageCount >= $check->limit_per_user) {
                return [
                    'status' => false,
                    'message' => $check->usages_left < $totalUsageCount ? 'Error:' . __('admin::home.Numberofuseshasexpired') : 'Error:' . __('admin::home.Youhavereachedthemaximumcodeusagelimit')
                ];
            }
            if ($cart->subtotal < $check->min_order_value)   return ["status" => false, "message" => "Error:minordervalueis", "data" => $check->min_order_value];

            if ($check->is_custom && !CashBackMerchantsChoice::where(['cash_back_id' => $check->id, 'merchant_id' => $cart->merchant_id])->exists()) {
                return [
                    'status' => false,
                    'message' => 'Error:thecashbackisnotforthisrestaurant',
                ];
            }

            if ($cart->subtotal < $check->min_order_value) return ["status" => false, "message" => "Error:minordervalueis", "data" => $check->min_order_value];
            $cart->update([
                "cash_back" => $check->name,
                "cash_back_id" => $check->id,
            ]);
        }

        $generalSetting = GeneralSetting::first();
        if ($cart->subtotal < $generalSetting?->min_order_price) return ["status" => false, "message" => 'Error:' . __('admin::home.theorderislessthantheminimumprice'), "data" => $generalSetting->min_order_price];

        foreach ($cart->cartItems as $cartItem) {
            if ($cartItem->item && !is_null($cartItem->item->stock) && $cartItem->item->stock < $cartItem->qty) {
                $itemName = $cartItem->item->getTranslation('title', app()->getLocale());
                return ["status" => false, "message" => "Error:" . __('ecommerce::ecommerce.out_of_stock') . " ({$itemName})", "data" => null];
            }
        }

        $payload = $this->orderHandleService->handle(
            $cart,
            $branch,
            $user,
            $date,
            $time,
            $data,
            (float) $cart->total_after_vat
        );
        $payload["code"] = $this->generateUniqueCode();
        $payload["order_type"] = "fixed";

        try {
            DB::beginTransaction();
            $order = $this->merchantOrderRepository->store($payload);
            foreach ($cart->cartItems as $cartItem) {
                $orderItem = $order->orderItems()->create([
                    'item_name' => $cartItem->item?->getTranslations('title'),
                    'item_total' => $cartItem?->subtotal,
                    'price' => $cartItem->item_price,
                    'qty' => $cartItem->qty,
                    'merchant_id' => $order->merchant_id,
                    'user_id' => $order->user_id,
                    'merchant_branch_id' => $order->merchant_branch_id,
                    'item_id' => $cartItem->item_id,
                ]);

                if ($cartItem->item && !is_null($cartItem->item->stock)) {
                    $cartItem->item->decrement('stock', $cartItem->qty);
                }

                foreach ($cartItem->cartChoices as $choice) {
                    $orderItem->orderChoices()->create([
                        'qty' => $choice->qty,
                        'order_id' => $order->id,
                        'item_id' => $choice->item_id,
                        'addition_group_id' => $choice->addition_group_id,
                        'choice_id' => $choice->choice_id,
                        'price' => $choice->price,
                    ]);
                }
            }

            if (isset($data['address_id']) && $data['address_id'] === 'new') {
                $newAddress = UserAddress::create([
                    'user_id' => $user->id,
                    'title' => $data['new_address_title'],
                    'phone' => $data['new_address_phone'],
                    'city_id' => $data['new_address_city_id'],
                    'district_id' => $data['new_address_district_id'] ?? null,
                    'address' => $data['new_address_address'],
                ]);

                $order->update(['address_id' => $newAddress->id]);
            }

            DB::commit();
            return ["status" => true, "message" => "", "data" => $order];
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
            return ["status" => false, "message" => "Error:On Store Order in Order Action", "data" => $exception->getMessage()];
        }
    }

    public function generateUniqueCode(): int
    {
        $maxCode = $this->merchantOrderRepository->builder()
            ->where('code', 'regexp', '^[0-9]+$')
            ->max('code');

        $code = $maxCode === null ? 10000 : ((int) $maxCode) + 1;

        while ($this->merchantOrderRepository->builder()->where('code', (string) $code)->exists()) {
            $code++;
        }

        return $code;
    }
}
