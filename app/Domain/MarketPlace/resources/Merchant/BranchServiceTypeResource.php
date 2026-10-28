<?php

namespace App\Domain\MarketPlace\resources\Merchant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchServiceTypeResource extends JsonResource
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
            'type_service_id' => $this->type_service_id,
            'type_service_name' =>__('admin::home.'. $this->type_service_name),
        ];
    }
}
