<?php

namespace App\Domain\MarketPlace\Actions\Api\Cart;

use App\Domain\MarketPlace\Repositories\Cart\CartRepository;

class ChangeCartTypeServiceAction
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
        if ($cart && $cart->exists()) {
            if ($cart->cartChoices() && $cart->cartChoices()->exists())
                $cart->cartChoices()->delete();
            if ($cart->cartItems() && $cart->cartItems()->exists())
                $cart->cartItems()->delete();
            $cart->delete();
        }
        $user = auth()->user();
        if ($user && $user instanceof \App\Domain\User\Models\User)
            $user->cart()
                ->updateOrCreate([
                    'service_type_id' => $data['service_type_id'],
                    'merchant_id' => $data['merchant_id'],
                    'merchant_branch_id' => $data["merchant_branch_id"]
                ], ['updated_at' => now()]);

        // if ($data['service_type_id'] == 2) {
        //     $cart->update([
        //         'delivery_fee' => 0,
        //         'delivery_fee_discount' => 0,
        //     ]);
        // }
        return $cart;
    }
}
