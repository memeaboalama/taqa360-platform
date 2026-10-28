<?php

namespace App\Domain\MarketPlace\resources\TypeService;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeServiceResource extends JsonResource
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
            'name' => __('admin::home.' . $this->name),
            'id' => $this->id,
        ];
    }
}
