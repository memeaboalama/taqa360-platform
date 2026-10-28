<?php

namespace App\Domain\MarketPlace\Observers;

use App\Domain\MarketPlace\Models\Cart;

class CartObserver
{
    public function created(Cart $cart)
    {

        $this->save_item($cart);

    }


    public function updated(Cart $cart)
    {

        if(isset(request()->item_id)){
            $cartItem = $cart->cartItems()->where('item_id', request()->item_id)->get();

            $data = null;
            if ($cartItem->isNotEmpty()) {
                if (isset(request()->additional) && !empty(array_filter(request()->additional))) {
                    foreach (request()->additional as $group) {
                        if (isset($group['choices']) && !empty(array_filter($group['choices']))) {
                            foreach ($group['choices'] as $choice) {
                                $data[] = request()->item_id . "-" . $choice["choice_id"] . "-" . $group["group_id"];


                            }
                        }
                    }
                } else {
                    $data = (string)request()->item_id;

                }

                $unique=[];
                if (gettype($data) == 'string' || gettype($data) == 'array') {


                    foreach ($cartItem as $item) {
                        $unique[]=$item->uniqueId;
//                        if ($item->uniqueId == $data) {
//                            $item->increment('qty', request()->qty);
//                        } else {
//                            $this->save_item($cart);
//                        }
                    }

                }
                 if(in_array($data,$unique)){
                     foreach ($cartItem as $item) {
                         if ($item->uniqueId == $data) {
                             $item->increment('qty', request()->qty);
                         }
                     }

                 }else{

                     $this->save_item($cart);
                 }




//                if (gettype($data) == 'array') {
//                    foreach ($cartItem as $item) {
//                        if ($item->uniqueId == $data) {
//                            $item->increment('qty', request()->qty);
//                        } else {
//                            $this->save_item($cart);
//                        }
//                    }
//
//                }

            } else {
                $this->save_item($cart);
            }
        }



    }


    public function save_item($cart): void
    {
        $cart_item = $cart->cartItems()->create([
            'item_id' => request()->item_id,
            'qty' => request()->qty,
//            'notes' => request()->notes,
        ]);
        if (isset(request()->additional) && !empty(array_filter(request()->additional))) {
            foreach (request()->additional as $group) {

                if (isset($group['choices']) && !empty(array_filter($group['choices']))) {
                    foreach ($group['choices'] as $choice) {
                        $cart->cartChoices()->create([
                            'qty' => 1,
                            'item_id' => request()->item_id,
                            'addition_group_id' => $group["group_id"],
                            'choice_id' => $choice["choice_id"],
                            'cart_item_id' => $cart_item->id,

                        ]);
                    }
                }

            }



        }
    }
}
