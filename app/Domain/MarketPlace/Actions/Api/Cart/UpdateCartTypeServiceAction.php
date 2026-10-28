<?php

namespace App\Domain\MarketPlace\Actions\Api\Cart;

use App\Domain\MarketPlace\Repositories\Cart\CartRepository;

class UpdateCartTypeServiceAction
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
        
        $cart->update([
            'service_type_id' => $data['service_type_id'],
        ]);
if($data['service_type_id']==2)
{
    $cart->update([
        'delivery_fee' => 0,
        'delivery_fee_discount' => 0,
    ]);
}
        return $cart;
    }
}
