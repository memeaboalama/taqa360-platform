<?php

namespace App\Domain\Ecommerce\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => \Illuminate\Support\Str::of($this->title)->limit(20),
            'original_price' => $this->default_price,
            'discount_percent' => 5,
            'final_price' => $this->default_price,
            'description' => $this->description,
            'images' => [
                [
                    'url' => $this->image_path,
                    'type' => 'main',
                ],
                [
                    'url' => 'https://example.com/images/product_main.jpg',
                    'type' => 'gallery',
                ],
                [
                    'url' => 'https://example.com/images/product_main.jpg',
                    'type' => 'gallery',
                ],
                [
                    'url' => 'https://example.com/images/product_main.jpg',
                    'type' => 'gallery',
                ]
            ],

            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title
            ],

            'details' => [],
            'video' => 'https://www.youtube.com/watch?v=wvtt9WxJSV4',

            'brand' => [
                [
                    'id' => 1,
                    'title' => 'Hammar Drill',
                ],
                [
                    'id' => 2,
                    'title' => 'Orbital Sander',
                ],
                [
                    'id' => 3,
                    'title' => 'Screwdriver',
                ],
                [
                    'id' => 4,
                    'title' => 'Drill Machine',
                ],
                [
                    'id' => 5,
                    'title' => 'accessories',
                ],
            ],

            'features' => [
                [
                    'title' => 'Free Shipping',
                    'description' => 'Free shipping worldwide',
                    'icon' => 'truck',
                ],
                [
                    'title' => '24 X 7 Service',
                    'description' => 'Online service for new customers',
                    'icon' => 'clock',
                ],
                [
                    'title' => 'Festival Offer',
                    'description' => 'New online special festival offer',
                    'icon' => 'gift',
                ],
                [
                    'title' => 'Online Payment',
                    'description' => 'Contrary to popular belief',
                    'icon' => 'credit-card',
                ],
            ]

        ];
    }
}
