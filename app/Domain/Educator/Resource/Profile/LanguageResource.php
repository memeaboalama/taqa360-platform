<?php

namespace App\Domain\Educator\Resource\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'name' => $this->language?->title ?? null,
            'level' => $this->level,
            'isPrimary' => (bool)$this->is_primary,
        ];
    }
}
