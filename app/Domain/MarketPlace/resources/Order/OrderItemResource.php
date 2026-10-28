<?php

namespace App\Domain\MarketPlace\resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $choices = [];

        if($this?->orderChoices->count()>0) foreach ($this?->orderChoices as $choice) $choices[] = $choice->choice?->title;
        return [
            'order_item_id' => $this->id,
            'item_name' => $this->item_name,
            'qty' => $this->qty,
            'item_total' => $this->item_total,
//            'vat' => $this->vat,
//            'total_after_vat' => $this->total_after_vat,
            'title' => $this->item?->title,
            'description' => $this->item?->description,
            'image' => $this->item?->image_path,
            'additional' => $choices
        ];
    }
}
