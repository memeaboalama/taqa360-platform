<?php

namespace App\Domain\User\resources;

use App\Domain\AppBase\Resources\CityResource;
use App\Domain\AppBase\Resources\DistractResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            'id'            =>  $this->id,
            'title'         =>  $this->title,
            'address'       =>  $this->address,
            'city'          => new CityResource($this->whenLoaded('city')) ,
            'distract'      => new DistractResource($this->whenLoaded('distract')) ,
            'latitude'       =>  $this->latitude,
            'longitude'       =>  $this->longitude,
            'is_default'       =>  $this->is_default,
        ];
    }
}
