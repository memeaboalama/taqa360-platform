<?php

namespace App\Domain\MarketPlace\resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => \Illuminate\Support\Str::of($this->title)->limit(20),
            'default_price' => $this->default_price,
            'categories' => MerchantCategoryResource::collection($this->categories)
        ];
    }
}
