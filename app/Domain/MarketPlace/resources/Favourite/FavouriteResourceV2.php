<?php

namespace App\Domain\MarketPlace\resources\Favourite;

use App\Domain\MarketPlace\resources\Merchant\BranchResourceV2;
// use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\MarketPlace\Models\Favorite;
// use Illuminate\Support\Facades\Log;

class FavouriteResourceV2 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        // Log::error(json_encode($request));
        // Log::error(json_encode($this));
        $userId = auth('sanctum')->id();
        return [
            // 'fav_id' => $this->id,
            // 'merchant' => new BranchResourceV2(MerchantBranch::find(request()->merchant_branch_id ?? $this->merchant_branch_id)),
            'fav_id' =>
            // Favorite::query()->where("merchant_id", $this->merchant_id)
            Favorite::query()->where("merchant_id", $this['branch']?->merchant_id)
                        ->where("merchant_branch_id", $this['branch']?->id)
                        ->where("user_id", $userId)?->get()?->first()->id ?? null,
            'merchant' => new BranchResourceV2($this),
        ];
    }
}
