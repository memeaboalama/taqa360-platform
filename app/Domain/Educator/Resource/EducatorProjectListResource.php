<?php

namespace App\Domain\Educator\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class EducatorProjectListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'budget'=> $this->budget ?? null,
            'tags' => $this->tags ?? [],
        ];
    }
}
