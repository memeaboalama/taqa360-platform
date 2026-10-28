<?php

namespace App\Domain\MarketPlace\Actions\Api\Cart;

use App\Domain\MarketPlace\Repositories\Cart\CartRepository;

class UpdateQtyAction
{
    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function handle(array $data): ?\Illuminate\Database\Eloquent\Model
    {
        ($cart = $this->cartRepository
            ->findByCols(['user_id' => auth()->guard()->user()->id]));
        if ($cart) {
            $item = $cart->cartItems()->where(['id' => $data['cart_item_id']])->first();
            if ($item && isset($data['qty']))
                $item->update([
                    'qty' => $data['qty'],

                ]);
        }

        return $cart;
    }
}
