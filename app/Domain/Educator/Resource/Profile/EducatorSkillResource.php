<?php

namespace App\Domain\Educator\Resource\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class EducatorSkillResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'name' => $this->approvedSkill?->name ?? $this->custom_name,
            'level' => $this->level,
            'isCustom' => (bool)$this->is_custom,
            'skillId' => $this?->approvedSkill?->uuid,
        ];
    }
}
