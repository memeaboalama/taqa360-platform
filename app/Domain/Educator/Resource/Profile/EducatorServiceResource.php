<?php

namespace App\Domain\Educator\Resource\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class EducatorServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'name' => $this->approvedService?->name ?? $this->custom_name,
            'description' => $this->custom_description,
            'isCustom' => (bool)$this->is_custom,
            'serviceId' => $this?->approvedService?->uuid,
        ];
    }
}
