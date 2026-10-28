<?php

namespace App\Domain\Provider\Resources;

use App\Domain\AppBase\Resources\MainCategoryResource;
use App\Domain\Provider\Models\ProviderSubService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderOrderResource extends JsonResource
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
            'status'                    =>  $this->status,
            'total_after_vat'           =>  $this->total_after_vat,
            'client_location'           =>  $this->client_location->toArray(),
            'client_location_text'         =>  $this->provider_towing_order_detail->pickup_address_title,
            'destination_location'         =>  $this->client_location->toArray(),
            'destination_location_text'         =>  $this->provider_towing_order_detail->dropoff_address_title,
            'main_service'              =>  new ProviderMainServiceResource($this->provider_sub_services?->provider_main_service),
            'sub_service'               =>  new ProviderSubServiceResource($this->provider_sub_services),
        ];
    }
}
