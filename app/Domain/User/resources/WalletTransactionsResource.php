<?php

namespace App\Domain\User\resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'amount' => $this->amountFloat,
            'type' => $this->type,
            'confirmed' => (bool)$this->confirmed,
            'name' => $this->wallet->name,
            'meta' =>  $this->wallet->meta,
            'created_at' => $this->created_at,
        ];

        return $data;
    }
}
