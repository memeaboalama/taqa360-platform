<?php

namespace App\Domain\SalesAgent\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesAgentBankResource extends JsonResource
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
            'name_on_card' => $this->name_on_card,
            'bank_name' => $this->bank_name,
            'card_number' => $this->card_number,
            'cvv' => $this->cvv,
            'expire_month' => $this->expire_month,
            'expire_year' => $this->expire_year,
            "bank"=>[
                'id'=> $this->bank_id,
                'name'=> $this->bank?->title,
            ]


        ];
    }
}
