<?php

namespace App\Domain\MarketPlace\resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class ChoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->when($this->id, $this->id),
            'title' => $this->when($this->title, $this->title),
            'price' => $this->pivot->price,
        ];
    }
}
