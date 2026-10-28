<?php

namespace App\Domain\MarketPlace\resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartServiceResource extends JsonResource
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
            'service_id' => $this->merchant_service_id,
            'title'=>$this->name,
            'working_minutes'=>$this->workingMinutes,
            'price'=>$this->price,
            'addon'=>CartServiceAddonResource::collection($this->addons),
        ];
    }
}
