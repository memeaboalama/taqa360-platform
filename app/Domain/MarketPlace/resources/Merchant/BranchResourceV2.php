<?php

namespace App\Domain\MarketPlace\resources\Merchant;

use App\Domain\MarketPlace\Models\Favorite;
// use App\Domain\MarketPlace\resources\Menu\MenuResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\MarketPlace\resources\TypeService\TypeServiceResource;
// use Illuminate\Support\Facades\Log;

class BranchResourceV2 extends JsonResource
{
    public function toArray($request): array
    {
        $fav = null;
        $servicesTypes=[];
        if (auth('sanctum')->check() && $this['branch']) {
            $userId = auth('sanctum')->id();
            $merchant_id = $this['branch']?->merchant_id;
            $merchant_branch_id = $this['branch']?->id;
            if ($userId && $merchant_id && $merchant_branch_id) { // $fav = (Favorite::query()->where("merchant_id", $this['branch']?->merchant_id)
                $count = (Favorite::query()->where("merchant_id", $merchant_id)
                    ->where("merchant_branch_id", $merchant_branch_id)
                    ->where("user_id", $userId)?->count() ?? 0 == 0);
                if ($count && $count > 0)
                    $fav = Favorite::query()->where("merchant_id", $merchant_id)
                        ->where("merchant_branch_id", $merchant_branch_id)
                        ->where("user_id", $userId)?->get()?->first() ?? null;
            }
        if( $this['branch']&&$this['branch']?->typeServices)
            $servicesTypes=TypeServiceResource::collection($this['branch']?->typeServices);
        }
        return [
            'branch' => [
                'id' => $this['branch']?->id,
                'address' => $this['branch']?->address_name,
                'description' => $this['branch']?->description,
                'distance' => number_format($this['branch']?->distance / 1000, 2) . " km",
                'status' => ($this['branch']?->status) ? "open" : "closed",
                'to' => $this['branch']?->to,
                'price' => "12 SAR",
                'new_price' => "2 SAR",
                'delivery_fees' => $this['branch']?->deliveryFee,
                // 'servicesTypes' => BranchServiceTypeResource::collection($this['branch']?->merchant_branch_type_services),
                // 'services_types' => BranchServiceTypeResource::collection($this['branch']?->typeServices),
                // 'services_types' => $this['branch']&&$this['branch']?->typeServices?TypeServiceResource::collection($this['branch']?->typeServices):[],
                'services_types' => $servicesTypes
            ],
            'merchant' => [
                'id' => $this['id'],
                'user_name' => $this['user_name'],
                'logo' => $this['logo'],
                'header_image' => $this['branch']?->cover,
                'company_name' => $this['company_name'] . '_' . $this['branch']?->title,
                'description' => $this['description'],
                'fav' => $fav ? true : false,
                'fav_id' => $fav ? $fav?->id : null,
                'userBalance' => $this['userBalance'],
            ],
            'menu' => [
                'title' => $this['branch']?->menu?->title
            ],
            'discount_percentage' => "50%",
            'rating' => $this['rate'],
            'avgPrice' => $this['avgPrice'],
            'deliveryTime' => $this['branch']?->deliveryTime,
        ];
    }
}
