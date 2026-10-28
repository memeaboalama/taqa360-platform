<?php

namespace App\Domain\ElmApp\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CitiesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'elm_id' => $this->elm_id,
            'key' => $this->key,
        ];
    }
}
