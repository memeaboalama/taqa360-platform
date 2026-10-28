<?php

namespace App\Domain\MarketPlace\resources\Favourite;

use App\Domain\MarketPlace\resources\Menu\ItemResource;
use App\Domain\MarketPlace\resources\Merchant\BranchResource;
use App\Domain\MarketPlace\resources\Merchant\MerchantResource;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'fav_id' => $this->id,
            'merchant'=> new BranchResource(MerchantBranch::find(request()->merchant_branch_id?? $this->merchant_branch_id)),
        ];
    }
}
