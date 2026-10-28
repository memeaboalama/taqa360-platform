<?php

namespace App\Domain\Catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            // 'item_id' => $this->item_id,
            'item_id' => $this->when(isset($this->item_id) && is_int($this->item_id) && $this->item_id != 0,$this->item_id),
            'meta' => $this->when(isset($this->meta) && is_array($this->meta) && !empty($this->meta),$this->meta),
            'properties' => SpecificationPropertyResource::collection($this->whenLoaded('properties')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

