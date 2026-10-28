<?php

namespace App\Domain\MarketPlace\resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'item_id'=> $this->item_id,
            'additional'=> CartChoiceResource::collection($this->cartChoices),
            'additional_total'=> $this->totalChoices,
            'item_price'=> $this->price,
            'new_price'=> $this->price - $this->price * 0.0,
            'qty'=> $this->qty,
            'total'=> $this->subtotal,
            'title'=> $this->item?->title,
            'description'=> $this->item?->description,
            'image'=> $this->item?->image_path,
        ];
    }
}
