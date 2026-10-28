<?php

namespace App\Domain\MarketPlace\resources\Merchant;

use App\Domain\MarketPlace\Models\Favorite;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    public function toArray($request): array
    {
        $fav=null;
        if(auth('sanctum')->check()){
            $fav=Favorite::query()->where("merchant_id",$this->merchant_id)
                ->where("merchant_branch_id",$this->id)
                ->where("user_id",auth('sanctum')->id())?->first();

        }
        return [
            'branch' => [
                'id' => $this?->id,
                'address' => $this?->address_name,
                'description' => $this?->description,
                'distance' => number_format($this?->distance / 1000, 2) . " km",
                'status' => ($this?->status) ? "open" : "closed",
                'to' => $this?->to,
                'price' => "12 SAR",
                'new_price' => "2 SAR",
                'delivery_fees' => $this?->deliveryFee,
            ],
            'merchant' => [
                'id' => $this->merchant?->id,
                'user_name' => $this->merchant?->user_name,
                'logo' => $this->merchant?->logo,
                'header_image' => $this?->cover,
                'company_name' => $this->merchant?->company_name . '(' . $this->title . ')',
                'description' => $this?->description,
                'fav' => $fav?true:false,
                'fav_id' => $fav?$fav->id:null,
            ],
            'menu' => [
                'title' => $this?->menu?->title
            ],
            'discount_percentage' => "50%",
            'rating' => $this?->rate,
        ];
    }
}
