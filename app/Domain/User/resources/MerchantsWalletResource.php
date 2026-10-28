<?php

namespace App\Domain\User\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantsWalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'merchant_name' => $this->merchant_name,
            'balance'   =>  $this->balance
        ];
    }
}
