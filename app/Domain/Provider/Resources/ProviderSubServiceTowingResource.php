<?php

namespace App\Domain\Provider\Resources;

use App\Domain\AppBase\Resources\MainCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderSubServiceTowingResource extends JsonResource
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
            'title'                     => $this->title,
            'description'               => $this->description,
            'towing_domain'             => $this->towing_domain,
            'towing_open_price'         => $this->towing_open_price,
            'towing_price_per_km'       => $this->towing_price_per_km,
            'towing_price_per_minute'   => $this->towing_price_per_minute,
            'towing_type'               =>  new TowingTypeResource($this->whenLoaded('towing_type')),
            'provider_main_service'     =>  new ProviderCategoryResource($this->whenLoaded('provider_category')),
            'provider_category'         =>  new ProviderCategoryResource($this->whenLoaded('provider_category')),
            'main_category'             =>  new MainCategoryResource($this->whenLoaded('main_category')),
        ];
    }
}
