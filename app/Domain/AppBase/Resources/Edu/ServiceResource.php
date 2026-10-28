<?php

namespace App\Domain\AppBase\Resources\Edu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'status' => $this->status,
            'name' => $this->name,
            'description' => $this?->description
        ];
    }
}
