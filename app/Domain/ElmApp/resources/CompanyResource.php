<?php

namespace App\Domain\ElmApp\resources;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\User\resources\UserAddressResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
            'phone' => $this->phone,
            'type' => $this->type,
            'dialcode' => $this->dialcode,
            'country_name' => $this->country_name,
            'region_id' => $this->region_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];

        return $data;
    }





}
