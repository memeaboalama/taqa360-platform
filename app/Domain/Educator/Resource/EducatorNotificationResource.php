<?php

namespace App\Domain\Educator\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducatorNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isRead = (bool) ($this->is_read ?? false);
        $createdAt = $this->created_at;
        $timestamp = $createdAt ? $createdAt->format('Y-m-d H:i:s') : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'message' => $this->body,
            'channel' => $this->channel,
            'device_type' => $this->device_type,
            'is_read' => $isRead,
            'isRead' => $isRead,
            'timestamp' => $timestamp,
            'created_at' => $timestamp,
            'admin_name' => $this->whenLoaded('admin', fn () => $this->admin?->name),
        ];
    }
}
