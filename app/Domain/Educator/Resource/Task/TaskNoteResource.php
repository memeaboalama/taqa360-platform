<?php

namespace App\Domain\Educator\Resource\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'taskId' => $this->task_id,
            'content' => $this->content,
            'authorId' => $this->creatable_id,
            'authorType' => $this->creatable_type,
            'createdAt' => $this->created_at?->format('Y-m-d H:i:s'),
            'modified_at' => $this->modified_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
