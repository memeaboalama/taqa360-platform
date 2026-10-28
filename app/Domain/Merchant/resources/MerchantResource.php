<?php

namespace App\Domain\Merchant\resources;

use App\Domain\User\resources\UserSettingResource;
use App\Domain\User\resources\WalletResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_manager' => $this->branchManager?->count() ? true : false,
            'email' => $this->email,
            'phone' => $this->phone,
            'dialcode' => $this->dialcode,
            'country_name' => $this->country_name,
            'country_code' => $this->country_code,
            'last_logged_in_at' => $this->last_logged_in_at->diffForHumans(),
            'branches' => array_unique($this->branch->pluck('type')->toArray())

        ];
    }
}
