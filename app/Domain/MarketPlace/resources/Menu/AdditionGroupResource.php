<?php

namespace App\Domain\MarketPlace\resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionGroupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'multi' => $this->multi,
            'min_customer_choices' => $this->min_value,
            'max_customer_choices' => $this->max_value,
            'choices' => ChoiceResource::collection($this->choices),
        ];
    }
}
