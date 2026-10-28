<?php

namespace App\Domain\MarketPlace\Actions\Api\Order;


use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\Promocode;
use App\Domain\AppBase\Models\CashBack;
use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Models\PromocodeMerchantsChoice;
use App\Domain\AppBase\Models\CashBackMerchantsChoice;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Cart\CartRepository;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\User\Models\User;
use App\Models\TempOrder;
use App\Support\Services\Branch\BranchStatusService;
use App\Support\Services\Order\OrderHandleServiceV2;
use Carbon\Carbon;
use Domain\MarketPlace\Models\ElmOrder;
use Domain\MarketPlace\Models\MerchantAddon;
use Domain\MarketPlace\Models\MerchantBranch;
use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\Translation\t;


class OrderActionV2
{
    private CartRepository $cartRepository;
    private BranchRepository $merchantBranch;
    private MerchantOrderRepository $merchantOrderRepository;

    private OrderHandleServiceV2 $orderHandleService;
    private LogOrderHistoryAction $logOrderHistoryAction;


    public function __construct(CartRepository $cartRepository, BranchRepository $merchantBranch, MerchantOrderRepository $merchantOrderRepository, OrderHandleServiceV2 $orderHandleService, LogOrderHistoryAction $logOrderHistoryAction)
    {
        $this->cartRepository = $cartRepository;
        $this->merchantBranch = $merchantBranch;
        $this->merchantOrderRepository = $merchantOrderRepository;
        $this->orderHandleService = $orderHandleService;
        $this->logOrderHistoryAction = $logOrderHistoryAction;
    }

    public function handle(array $data, User $user)
    {
        $cart = $user->cart;
        $date = $data["date"] ?? Carbon::now()->format('Y-m-d');
        $time = isset($data["time"]) && $data["time"] != "" ? \DateTime::createFromFormat('g:i a', $data["time"])->format('H:i:s') : Carbon::now()->format('h:i A');

        if (!$this->hasCart($cart)) return ["status" => false, "message" => "Error:NoCartData", "data" => null];
        $branch = $this->merchantBranch->cartBranch($cart, ['id', 'merchant_id'], ['workDays']);
        if (!$branch) return ["status" => false, "message" => "Error:Branchisnotfoundpleaseemptycart!", "data" => null];
        if (!$cart->branch->status) return ["status" => false, "message" => "Error:Restaurantisclosedatthistime!", "data" => null];



        if (isset($data["promocode"]) || $cart->promocode) {
            $check = Promocode::query()
                ->where('status', '=', 'active')
                ->where('usages_left', '>', 0)
                ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))
                ->where('code', '=', $data["promocode"] ?? $cart->promocode)->first();

            if (!$check) return ["status" => false, "message" => "Promo Code Is Not Valid"];
            // Check promocode usage limit
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

            if ($check->is_custom && !PromocodeMerchantsChoice::where(['promocode_id' => $check->id, 'merchant_id' => $cart->merchant_id])->exists()) {
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
            // Check cash_back usage limit
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
            // update cart with promo
            if ($cart->subtotal < $check->min_order_value) return ["status" => false, "message" => "Error:minordervalueis", "data" => $check->min_order_value];
            $cart->update([
                "cash_back" => $check->name,
                "cash_back_id" => $check->id,
            ]);
        }

        $generalSetting = GeneralSetting::first();
        if ($cart->subtotal < $generalSetting?->min_order_price) return ["status" => false, "message" => 'Error:' . __('admin::home.theorderislessthantheminimumprice'), "data" => $generalSetting->min_order_price];
        if ($cart?->deliveryDistance > $generalSetting?->restaurant_search_capacity) return ["status" => false, "message" => 'Error:'.__('admin::home.The distance between you and the store is greater than the permitted distance, which is:').$generalSetting?->restaurant_search_capacity, "data" => $generalSetting?->restaurant_search_capacity];

        foreach ($cart->cartItems as $cartItem) {
            if ($cartItem->item && !is_null($cartItem->item->stock) && $cartItem->item->stock < $cartItem->qty) {
                $itemName = $cartItem->item->getTranslation('title', app()->getLocale());
                return ["status" => false, "message" => "Error:Out of Stock ({$itemName})", "data" => null];
            }
        }

        $payload = $this->orderHandleService->handle($user->cart, $branch, $user, $date, $time, $data);
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
                        // 'price' => $choice->subtotal,
                        'price' => $choice->price,
                    ]);
                    //$choice->delete();
                }
            }


            DB::commit();
            return ["status" => true, "message" => "", "data" => $order];
        } catch (\Exception $exception) {
            dd($exception);
            Log::error($exception);
            DB::rollBack();
            return ["status" => false, "message" => "Error:On Store Order in Order Action", "data" => $exception->getMessage()];
        }
    }

    public function generateUniqueCode(): int
    {
        // $order = $this->merchantOrderRepository->builder()->count() == 0 ? 0 : $this->merchantOrderRepository->builder()->count();
        // $start = 10000;
        // do {
        //     $referal_code = ($start + $order);
        // } while ($this->merchantOrderRepository->find(cols: ["*"], conditions: ["code" => $referal_code]));

        // return $referal_code;


        $maxCode = $this->merchantOrderRepository->builder()
            ->where('code', 'regexp', '^[0-9]+$')
            ->max('code');

        $code = $maxCode === null ? 10000 : ((int) $maxCode) + 1;

        while ($this->merchantOrderRepository->builder()->where('code', (string) $code)->exists()) {
            $code++;
        }

        return $code;
    }

    public function all(array $data): \Illuminate\Contracts\Pagination\CursorPaginator
    {
        $data['user_id'] = auth()->guard('user-api')->user()->id;
        return $this->merchantOrderRepository
            ->filterForUser($data, ["orderItems", "user", "address", "branch", "merchant", "payment"])
            ->orderBy("created_at", "DESC")
            ->cursorPaginate();
    }

    public function hasCart($cart): bool
    {
        if (!$cart) return false;
        return true;
    }

    public function isBranchOpen($branch, $data)
    {
        $date = $data["date"] ?? Carbon::now()->format('Y-m-d');
        $time = $data["time"] ?? Carbon::now()->format('h:i A');
        $payload = [
            'branch' => $branch->id,
            'date' => $date,
            'merchant' => $branch->merchant_id,
        ];
        $slots = (new BranchStatusService())->slots($branch, $payload);

        foreach ($slots as $slot) {

            if ($slot["available"] && $slot["time"] == $time) return true;
        }
        return false;
    }


    public function inDistanceRange($branch, $merchant, $address): bool
    {
        if ($branch->type == "mobile") {
            $check = $this->merchantBranch->checkDistance($merchant, $branch->id, $address);

            return $status = is_null($check) ? false : true;
        }
        return true;
    }

    public function show(int $id): ?Model
    {
        return $this->merchantOrderRepository->find(cols: ['*'], conditions: ['user_id' => auth()->guard('user-api')->user()->id, 'id' => $id]);
    }
}
