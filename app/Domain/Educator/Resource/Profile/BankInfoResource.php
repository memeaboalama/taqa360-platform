<?php

namespace App\Domain\Educator\Resource\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class BankInfoResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->uuid,
            'iban' => $this->iban,
            'name' => $this->name,
            'address' => $this->address,
            'bankCountry' => $this->bank_country,
            'bankCode' => $this->bank_code,
            'bankName' => $this->bank_name,
            'isApproved' => (bool)$this->is_approved,
        ];

        if ($this->is_approved === false && $this->rejection_reason) {
            $data['rejectionReason'] = $this->rejection_reason ?? null;
        }

        return $data;
    }
}
