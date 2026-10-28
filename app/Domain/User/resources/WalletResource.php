<?php

namespace App\Domain\User\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            'balance' => $this->hasWallet('UID_'.$this->id.'_Main')  ? $this->getWallet('UID_'.$this->id.'_Main')->balanceFloatNum:0,
        ];
    }
}
