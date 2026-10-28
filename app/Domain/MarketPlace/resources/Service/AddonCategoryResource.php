<?php

namespace App\Domain\MarketPlace\resources\Service;

use App\Domain\AppBase\Resources\DistractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AddonCategoryResource extends JsonResource
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
            'option_type' => $this->option_type,
            'min_required' => $this->min_required,
            'addons'=>AddonResource::collection($this->addons)
        ];
    }
}
