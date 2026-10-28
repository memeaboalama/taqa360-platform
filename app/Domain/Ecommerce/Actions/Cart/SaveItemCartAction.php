<?php

namespace App\Domain\Ecommerce\Actions\Cart;

use App\Domain\Ecommerce\Repositories\Cart\WebCartRepository;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Domain\User\Models\User;
use Domain\MarketPlace\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class SaveItemCartAction
{
    public function __construct(protected WebCartRepository $webCartRepository , protected ItemRepository $itemRepository){}

    public function addItem(array $data)
    {
        // DB::beginTransaction();
        $userId = auth()->id() ?? auth()->guard('web')->id() ?? auth()->guard('user-api')->id();
        if (!$userId) {
            return null;
        }
        $user = User::find($userId);
        if (!$user) {
            return null;
        }
        $cart = [];

        // stock guard: an item with a tracked stock cannot be added beyond what is available
        $item = Item::query()->where('id', $data['item_id'])->first();
        if (!$item) {
            return ['code' => 400, 'message' => __('ecommerce::ecommerce.product_not_available')];
        }

        if (!$item->isInStock()) {
            return ['code' => 400, 'message' => __('ecommerce::ecommerce.product_out_of_stock')];
        }

        $requestedQty = (int) ($data['qty'] ?? 1);
        if ($item->stock !== null) {
            $currentQty = 0;
            $userCart = $user->webCart()->first();
            if ($userCart) {
                $currentQty = (int) ($userCart->cartItems()->where('item_id', $item->id)->value('qty') ?? 0);
            }
            if ($currentQty + $requestedQty > (int) $item->stock) {
                $remaining = max(0, (int) $item->stock - $currentQty);
                return [
                    'code' => 400,
                    'message' => $remaining > 0
                        ? __('ecommerce::ecommerce.remaining_stock', ['count' => $remaining])
                        : __('ecommerce::ecommerce.product_out_of_stock'),
                ];
            }
        }

        // try {

        $cart = $user->webCart()->updateOrCreate(["user_id" => $user->id], ['updated_at' => now()]);

        if($cart) {
            $newItemMerchantId = Item::query()->where('id', $data['item_id'])->value('merchant_id');

            $existingMerchants = $cart->cartItems()
                ->where('item_id', '!=', $data['item_id'])
                ->with('item:id,merchant_id')
                ->get()
                ->pluck('item.merchant_id')
                ->filter()
                ->unique()
                ->values();

            if ($newItemMerchantId && $existingMerchants->isNotEmpty() && !$existingMerchants->contains($newItemMerchantId)) {
                return [
                    'code' => 409,
                    'message' => __('ecommerce::ecommerce.cart.store_conflict'),
                ];
            }

            $existingItem = $cart->cartItems()->where('item_id', $data['item_id'])->first();

            if ($existingItem) {
                Log::info('Existing item found in cart: ' . $existingItem->item_id);
                Log::info('Existing item found in cart: ' ,[$data['qty']]);
                // $existingItem->qty = $existingItem->qty + $data['qty'];
                // $existingItem->save();
                $existingItem->update([
                    'qty'=> $existingItem->qty + $data['qty']
                ]);

                return $cart;
            } else {
                $cart_item = $cart->cartItems()->create([
                    'item_id' => $data['item_id'],
                    'qty' => $data['qty'],
                ]);
            }
        }

        // } catch (\Exception $exception){
        //     DB::rollBack();
        // }
        // DB::commit();

        return  $cart;
    }
}
