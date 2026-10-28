<?php

namespace App\Domain\MarketPlace\resources\Branch;

use App\Domain\AppBase\Models\TypeService;
use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\resources\Menu\MenuResource;
use App\Domain\MarketPlace\resources\Merchant\BranchServiceTypeResource;
use App\Domain\MarketPlace\resources\TypeService\TypeServiceResource;
// use Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class BranchResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title??$this->merchant?->company_name,
            'description' => $this->description,
            'distance' => number_format($this->distance / 1000, 2) . " km",
            'status' => ($this->status) ? "open" : "closed",
            'to' => date_create_from_format('H:i:s', $this?->to)?->format('H:i') . ' ' . $this?->meridiem,
            'price' => "12 SAR",
            'city' => $this->city?->title,
            'district' => $this->distract?->title,
            'menu' => new MenuResource($this->menu),
            'merchant' => [
                'id' => $this->merchant_id,
                'user_name' => $this->merchant?->user_name,
                'logo' => $this->merchant?->logo,
                'header_image' => $this?->cover,
                'company_name' => $this->merchant?->company_name . '(' . $this->distract?->title . ')',
                'description' => $this->merchant?->description,
                'fav' => $fav ? true : false,
                'fav_id' => $fav ? $fav->id : null,
                'userBalance' => auth('sanctum')->check() ?
                    (
                        auth('sanctum')->user()->hasWallet(auth('sanctum')->id() . '_' . $this->merchant_id . '_' . $this->merchant?->phone) ?
                        auth('sanctum')->user()->getWallet(auth('sanctum')->id() . '_' . $this->merchant_id . '_' . $this->merchant?->phone)->balanceFloatNum
                        : 0
                    )
                    : 0
            ],
            "workDays" => BranchWorkDayResource::collection($this->workDays),
            // "workDaysHour"=>[
            //     "from"=>$this->workDaysHour?->from,
            //      "to"=>$this->workDaysHour?->to, 
            //      "status"=>$this->workDaysHour?->status,
            //      "merchant_branch_id"=>$this->workDaysHour?->merchant_branch_id,
            //      "work_day_id"=>$this->workDaysHour?->work_day_id
            // ],
            // 'services_types' => new BranchServiceTypeResource($this->merchant_branch_type_services),
            // 'services_types' => new TypeServiceResource($this->typeServices),
            'services_types' => TypeServiceResource::collection($this->typeServices),

            'rating' => $this->merchant?->rate,
            'delivery_fees' => $this?->deliveryFee,
            'avgPrice' => $this->merchant?->avgPrice,
            'deliveryTime' => $this?->deliveryTime,
        ];
    }
}
