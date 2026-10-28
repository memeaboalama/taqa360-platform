<?php

namespace App\Domain\User\resources;

use App\Domain\AppBase\Resources\BrandResource;
use App\Domain\AppBase\Resources\CityResource;
use App\Domain\AppBase\Resources\DistractResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCarResource extends JsonResource
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
            'id'                =>  $this->id,
            'brand'             =>  new BrandResource($this->whenLoaded('brand')),
            'model'             =>  new BrandResource($this->whenLoaded('model')),
            'year'              => $this->year,
            'plate_number'      => $this->plate_number,
            'default'           => $this->default ?? 'inactive',
        ];
    }
}
