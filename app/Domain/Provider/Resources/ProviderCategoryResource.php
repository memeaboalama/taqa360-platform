<?php

namespace App\Domain\Provider\Resources;

use App\Domain\AppBase\Resources\MainCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderCategoryResource extends JsonResource
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
            'selection'         =>  $this->selection,
            'main_category'         =>  new MainCategoryResource($this->whenLoaded('main_category')),
        ];
    }
}
