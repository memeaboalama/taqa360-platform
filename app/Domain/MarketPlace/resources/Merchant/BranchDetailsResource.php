<?php

namespace App\Domain\MarketPlace\resources\Merchant;

use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\resources\TypeService\TypeServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        $fav = null;
        if (auth('sanctum')->check()) {
            $fav = Favorite::query()->where("merchant_id", $this->merchant_id)
                ->where("merchant_branch_id", $this->id)
                ->where("user_id", auth('sanctum')->id())?->first();
        }
        return [

            'id' => $this?->id,
            'title' => $this?->title,
            'merchant_id' => $this?->merchant_id,
            'address' => $this?->address_name,
            'description' => $this?->description,
            'distance' => number_format($this?->distance / 1000, 2) . " km",
            'header_image' => $this?->cover,
            'status' => ($this?->status) ? "open" : "closed",
            'is_active' => $this?->isActive,
            'to' => $this?->to,
            // 'price' => "12 SAR",
            // 'new_price' => "2 SAR",
            'delivery_fees' => $this?->deliveryFee,
            'type' => $this->type,
            'has_scheduled_services' => $this->has_scheduled_services,
            // 'services_types' => TypeServiceResource::collection($this?->typeServices),
            'services_types' => TypeServiceResource::collection($this->whenLoaded('typeServices')),
            'fav' => $fav ? true : false,
            'fav_id' => $fav ? $fav->id : null,
            
            'menu' => [
                'title' => $this?->menu?->title
            ],
            'discount_percentage' => "50%",
            'rating' => $this?->rate,
            // 'distance' => $this?->distance,
            'longlat'=>[
                $this?->longitude,
                $this?->latitude
            ],
        ];
    }
}
