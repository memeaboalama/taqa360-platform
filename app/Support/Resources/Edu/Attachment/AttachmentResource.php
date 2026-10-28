<?php

namespace App\Support\Resources\Edu\Attachment;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
                'id' => $this->fileable?->uuid,
                'name' => $this->fileable?->name,
                'type' => class_basename($this->fileable?->getMorphClass()),
            ],
            'uploadedAt' => $this->uploaded_at,
        ];
    }
}
