<?php

namespace App\Domain\Ecommerce\Actions\Cart;

use App\Domain\Ecommerce\Repositories\Cart\WebCartRepository;
use Illuminate\Support\Facades\DB;

class DeleteItemCartAction
{
    public function __construct(protected WebCartRepository $webCartRepository){}

    public function remove(array $data)
    {
        $userId = auth()->id() ?? auth()->guard('web')->id() ?? auth()->guard('user-api')->id();
        if (!$userId) {
            return null;
        }
        DB::beginTransaction();
        ($cart = $this->webCartRepository
            ->findByCols(['user_id' => $userId]));
        if ($cart) {
            $cart->cartChoices()->where(['cart_item_id' => $data['cart_item_id']])->delete();
            $cart->cartItems()->where(['id' => $data['cart_item_id']])->delete();

            if ($cart->cartItems->count() == 0) {
                $cart->delete();
            }
        }

        DB::commit();
        return $cart;
    }

    public function removeV2(array $data)
    {
        $userId = auth()->id() ?? auth()->guard('web')->id() ?? auth()->guard('user-api')->id();
        if (!$userId) {
            return null;
        }
        DB::beginTransaction();
        ($cart = $this->webCartRepository
            ->findByCols(['user_id' => $userId]));
        if ($cart) {
            $cart->cartChoices()->where(['item_id' => $data['item_id']])->delete();
            $cart->cartItems()->where(['item_id' => $data['item_id']])->delete();

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
            $user_id = auth()->id() ?? auth()->guard('web')->id() ?? auth()->guard('user-api')->id();
        if (!$user_id) {
            return null;
        }
        DB::beginTransaction();
        $cart = $this->webCartRepository
            ->findByCols(['user_id' => $user_id]);
        if ($cart) {
            $cart->cartChoices()->delete();
            $cart->cartItems()->delete();
            $cart->delete();
            DB::commit();
            return $cart;
        }
        DB::rollBack();
        return null;
    }
    public function clearV2($user_id = null)
    {
        if (!$user_id)
            $user_id = auth()->id() ?? auth()->guard('web')->id() ?? auth()->guard('user-api')->id();
        if (!$user_id) {
            return null;
        }
        // DB::beginTransaction();
        $cart = $this->webCartRepository
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
