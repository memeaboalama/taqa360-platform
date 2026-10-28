<?php

namespace App\Domain\Merchant\resources;

use App\Domain\User\resources\UserSettingResource;
use App\Domain\User\resources\WalletResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class MerchantBranchResource extends JsonResource
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
            'type' => $this->type,


        ];
    }
}
