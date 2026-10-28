<?php

namespace App\Domain\MarketPlace\resources\Merchant;

use App\Domain\AppBase\Resources\DistractResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class MerchantDetailsResource extends JsonResource
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
                'id' => $this->id,
                'merchant_id' => $this->merchant_id,
                'name' => $this->name,
                'user_name' => $this->user_name,
                'logo' => $this->imageUrl,
                'logo_url' => $this->logo,
                'company_name' => $this->company_name,
                'is_active' => $this->merchantActive(),
                'description' => $this->description,
                'phone_number' => $this->phone_number,
                'main_categories' => $this->main_categories,
                'rating' => $this->rate,
                'avgPrice' => $this->avgPrice,
                'branches' => BranchDetailsResource::collection($this->whenLoaded('branches')),


        ];
    }
}
