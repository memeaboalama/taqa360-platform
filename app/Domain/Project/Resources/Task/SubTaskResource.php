<?php

namespace Domain\Project\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class SubTaskResource extends JsonResource
{
    public function toArray($request): array
    {
        // subtasks created by the admin console (or imported) have no creatable
        // morph, and reading ->uuid on null made the whole task detail response
        // fail with a 500
        $creator = $this->creatable;

        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'completed' => $this->completed,
            'completedAt' => $this->completed_at ? $this->completed_at->format('Y-m-d H:i:s') : null,
            'createdBy' => $creator ? [
                'id' => $creator->uuid ?? (string) ($creator->id ?? ''),
                'name' => $creator->name,
                'type' => class_basename($creator->getMorphClass()),
            ] : [
                'id' => null,
                'name' => 'admin',
                'type' => 'Admin',
            ],

            'attachments' => TaskFileResource::collection($this->whenLoaded('attachments')),
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'modifiedAt' => $this->modified_at ? $this->modified_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
