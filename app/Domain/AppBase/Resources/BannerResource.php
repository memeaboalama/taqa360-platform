<?php

namespace App\Domain\AppBase\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'banner' => $this->image_path,
        ];
    }
}
