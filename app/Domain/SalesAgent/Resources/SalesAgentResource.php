<?php

namespace App\Domain\SalesAgent\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesAgentResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'Id_number' => $this->Id_number,
            'Id_expire_date' => $this->Id_expire_date,
            'is_complete' => (bool)$this->is_complete,
            'dialcode' => $this->dialcode,
            'country_name' => $this->country_name,
            'country_code' => $this->country_code,
            'status' => $this->status,
            'network_status' =>(bool) $this->network_status,
            'location' => $this->location,
            'image' => $this?->profileUrl,
            'license_image' => $this?->licenseImageUrl,
            'car_image' => $this?->carImageUrl,
            'id_image' => $this?->idImageUrl,

        ];
    }
}
