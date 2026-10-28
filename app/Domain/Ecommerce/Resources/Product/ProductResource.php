<?php

namespace App\Domain\Ecommerce\Resources\Product;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $list = [
            'id' => $this->id,
            'title' => \Illuminate\Support\Str::of($this->title)->limit(20),
            'default_price' => $this->default_price,
            'image_path' => $this->image_path,
            'rate' => $this?->rate ?? 1.5,
        ];

        return $list;
    }
}
