<?php

namespace App\Domain\MarketPlace\resources\Order;

use App\Domain\User\resources\UserAddressResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderServiceResource extends JsonResource
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
            'service_name' => $this->service_name,
            'service_total' => $this->service_total,
            'working_minutes' => $this->service?->working_minutes,
            'addons' => implode(',', $this->addons->pluck('addon_name')->toArray())
        ];
    }
}
