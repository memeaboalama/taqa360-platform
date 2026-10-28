<?php

namespace App\Domain\MarketPlace\resources\Merchant;

use App\Domain\AppBase\Resources\DistractResource;
use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\resources\TypeService\TypeServiceResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;
use JsonSerializable;


class MerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $fav = null;
        if (auth('sanctum')->check()) {
            $fav = Favorite::query()->where("merchant_id", $this['branch']?->merchant_id)
                ->where("merchant_branch_id", $this['branch']?->id)
                ->where("user_id", auth('sanctum')->id())?->first();
        }
        return [
            'branch' => [
                'id' => $this['branch']?->id,
                'title' => $this['branch']?->title??$this['company_name'],
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
                'services_types' => TypeServiceResource::collection($this['branch']?->typeServices),
            ],
            'merchant' => [
                'id' => $this['id'],
                'user_name' => $this['user_name'],
                'logo' => $this['logo'],
                'header_image' => $this['branch']?->cover,
                'company_name' => (isset($this['branch']?->title)&&$this['branch']?->title)?$this['company_name'].'_'.$this['branch']?->title:$this['company_name'],
                'description' => $this['description'],
                'fav' => $fav ? true : false,
                'fav_id' => $fav ? $fav->id : null,
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
