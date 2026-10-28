<?php

namespace Domain\Project\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskFileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->title,
            'original_name' => $this->original_name,
            'type' => $this->type,
            'collection_name' => $this->collection_name,
            'size' => $this->size,
            'url' => $this->url,
            'uploader' => [
                'id' => $this->uploader?->uuid,
                'name' => $this->uploader?->name,
                'type' => class_basename($this->uploader?->getMorphClass()),
            ],
            'uploadedAt' => $this->uploaded_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}
