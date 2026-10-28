<?php

namespace App\Domain\Provider\Resources;

use App\Domain\AppBase\Resources\MainCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderMainServiceResource extends JsonResource
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
            'title'                     =>  $this->title,
            'description'               =>  $this->description,
            'starter_price'             =>  $this->starter_price ?? 0,
            'provider_category'         =>  new ProviderCategoryResource($this->whenLoaded('provider_category')),
            'main_category'             =>  new MainCategoryResource($this->whenLoaded('main_category')),

        ];
    }
}
