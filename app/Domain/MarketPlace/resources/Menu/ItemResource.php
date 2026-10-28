<?php

namespace App\Domain\MarketPlace\resources\Menu;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $list = [
            'id' => $this->id,
            'title' => \Illuminate\Support\Str::of($this->title)->limit(20),
            'description' => $this->description,
            'default_price' => $this->default_price,
            'calories' => $this->calories,
            'preparing_time' => $this->preparing_time,
            'image_path' => $this->image_path,
            'status' => $this->status->value,
            // 'item_prices' => ItemPriceResource::collection($this->itemPrices),
            'item_prices' => ItemPriceResource::collection($this->activeItemPrices),
            'additionGroups' => AdditionGroupResource::collection($this->additionGroups),
        ];
        $user = auth()->guard('user-api')->user();
        if ($user) {
            $list = array_merge($list, ['cart_qty' => $this->checkCartQty($this->id, $user)]);
        }
        return $list;
    }

    private function checkCartQty(int $item_id, $user): int
    {

        if ($user) {
            $cart = $user->cart()->first();
            if ($cart) {
                $cart_item = $cart->cartItems()->where('item_id', $item_id)->first();
                return (!is_null($cart_item)) ? $cart_item->qty : 0;
            }
        }
        return 0;
    }
}
