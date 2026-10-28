<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'file_name' => $this->file_name,
            'original_name' => $this->original_name,
            'mime_type' => $this->mime_type,
            'collection_name' => $this->collection_name,
            'url' => $this->url,
            'path' => $this->path,
            'size' => (float) $this->size,
            'uploader_type' => $this->uploader_type,
            'uploader_id' => $this->uploader_id,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
