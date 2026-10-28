<?php

namespace App\Domain\MarketPlace\Actions\Api\Cart;

use App\Domain\MarketPlace\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\DB;

class DeleteItemCartAction
{
    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function remove(array $data)
    {
        DB::beginTransaction();
        ($cart = $this->cartRepository
            ->findByCols(['user_id' => auth()->guard()->user()->id]));
        $cart->cartChoices()->where(['cart_item_id' => $data['cart_item_id']])->delete();
        $cart->cartItems()->where(['id' => $data['cart_item_id']])->delete();

        if ($cart->cartItems->count() == 0) {
            $cart->delete();
        }

        DB::commit();
        return $cart;
    }

    public function removeV2(array $data)
    {
        DB::beginTransaction();
        ($cart = $this->cartRepository
            ->findByCols(['user_id' => auth()->guard()->user()->id]));
        if ($cart) {
            $cart->cartChoices()->where(['cart_item_id' => $data['cart_item_id']])->delete();
            $cart->cartItems()->where(['id' => $data['cart_item_id']])->delete();

            if ($cart->cartItems->count() == 0) {
                $cart->delete();
                DB::commit();
                return null;
            }
        }

        DB::commit();
        return $cart;
    }

    public function clear($user_id = null)
    {
        if (!$user_id)
            $user_id = auth()->guard('user-api')->user()->id;
        DB::beginTransaction();
        $cart = $this->cartRepository
            ->findByCols(['user_id' => $user_id]);
        if ($cart) {
            $cart->cartChoices()->delete();
            $cart->cartItems()->delete();
            $cart->delete();
            DB::commit();
            return $cart;
        }
    }
    public function clearV2($user_id = null)
    {
        if (!$user_id)
            $user_id = auth()->guard('user-api')->user()->id;
        // DB::beginTransaction();
        $cart = $this->cartRepository
            ->findByCols(['user_id' => $user_id]);
        if ($cart && $cart->exists()) {
            DB::beginTransaction();
            if ($cart->cartChoices() && $cart->cartChoices()->exists())
                $cart->cartChoices()->delete();
            if ($cart->cartItems() && $cart->cartItems()->exists())
                $cart->cartItems()->delete();
            $cart->delete();
            DB::commit();
            // return $cart;
        }
        return null;
    }
}
