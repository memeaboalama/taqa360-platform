<?php

namespace App\Domain\Catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Filesystem\FilesystemAdapter;

class AttachmentResource extends JsonResource
{
    public function toArray($request): array
    {
        if($this->type === 'file')
        {
            $filesystem= Storage::disk($this->storage);
            if($filesystem instanceof FilesystemAdapter)
         {
            $fileAdpter =$filesystem;
            // Log::error("File Adapter Type: ". $filesystem::class);
        }
        else
        {
            // $fileAdpter=Storage::disk($this->storage);
        }

        }
       
        return [
            'id' => $this->id,
            'is_image' => Str::startsWith($this->mime_type, 'image/'),
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'collection_name' => $this->collection_name,
            'original_name' => $this->original_name,
            // 'url' => $this->type === 'file' ? Storage::disk($this->storage)->url($this->path) : $this->url,
            // 'webp_url' => $this->when($this->webp_path, fn() => Storage::disk($this->storage)->url($this->webp_path)),
            // 'thumbnail_url' => $this->when($this->thumbnail_path, fn() => Storage::disk($this->storage)->url($this->thumbnail_path)),
            'url' => $this->type === 'file' ?(isset($fileAdpter)?$fileAdpter->url($this->path):$this->path) : $this->url,
            'webp_url' => $this->when($this->webp_path, fn() => isset($fileAdpter)?$fileAdpter->url($this->webp_path):$this->webp_path),
            'thumbnail_url' => $this->when($this->thumbnail_path, fn() => isset($fileAdpter)?$fileAdpter->url($this->thumbnail_path):$this->thumbnail_path),
            'sort_order' => $this->whenPivotLoaded('attachables', fn() => $this->pivot->sort_order),
            'is_primary' => $this->whenPivotLoaded('attachables', fn() => (bool) $this->pivot->is_primary),
            'meta' => $this->when(isset($this->meta) && is_array($this->meta) && !empty($this->meta),$this->meta),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

