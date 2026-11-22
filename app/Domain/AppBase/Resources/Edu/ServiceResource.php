<?php

namespace App\Domain\AppBase\Resources\Edu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'status' => $this->status,
            'name' => $this->name,
            'name_translations' => $this->getTranslations('name'),
            'description' => $this?->description,
            'description_translations' => $this->getTranslations('description'),
            'scope_of_work' => $this?->scope_of_work,
            'scope_of_work_translations' => $this->getTranslations('scope_of_work'),
            'request_config' => $this->request_config,
            'details' => Arr::except($this->details ?? [], ['_seed_key']),
        ];
    }
}
