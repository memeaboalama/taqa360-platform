<?php

namespace App\Domain\Provider\Resources\App;

use App\Domain\AppBase\Resources\MainCategoryResource;
use App\Domain\Provider\Resources\ProviderCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderSubServiceResource extends JsonResource
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
            'towing_type'               => $this->towing_type?->slug,

        ];
    }
}
