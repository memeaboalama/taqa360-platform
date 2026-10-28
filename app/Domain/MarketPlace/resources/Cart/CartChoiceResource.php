<?php

namespace App\Domain\MarketPlace\resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartChoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->choice?->title,
            'price'=>$this->price ,
            'qty'=>$this->qty,
            'subtotal'=>$this->subtotal

        ];
    }
}
