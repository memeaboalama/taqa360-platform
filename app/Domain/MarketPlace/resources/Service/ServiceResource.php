<?php

namespace App\Domain\MarketPlace\resources\Service;

use App\Domain\AppBase\Resources\DistractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ServiceResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'default_price' => (float)$this->default_price,
            'working_minutes' => $this->working_minutes,
            'currency'=>$this->currency?->iso


        ];
    }
}
