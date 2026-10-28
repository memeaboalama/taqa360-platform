<?php

namespace App\Domain\Towing\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TowingCityLineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                        =>  $this->id,
            'title'                     =>  new TowingCityResource($this->whenLoaded('to_city')),
            'price'                     =>  $this->price,
        ];
    }
}
