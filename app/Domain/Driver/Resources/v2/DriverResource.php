<?php

namespace App\Domain\Driver\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'elm_id'=> $this?->elm_id,
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
            'elm_upload_status' => $this->elm_upload_status,
            'IdentityTypeId'=> $this?->IdentityTypeId,
            'dateOfBirth'=> $this?->dateOfBirth,
            'region_name'=> $this?->region?->title,
            'city_id' => $this?->city?->elm_id,
            'region_id'=> $this?->region?->elm_id,
            'car_type_name'=> $this?->carType?->title,
            'car_type_id'=> $this?->carType?->elm_id,
            'carNumber'=> $this?->carNumber,
            'vehicleSequenceNumber'=> $this?->vehicleSequenceNumber,
            'registrationDate' => $this->created_at,
            'elm_errors' => $this?->elm_errors,

            'company' => [
                'company_id' => $this?->company_id,
                'company_name' => $this?->company?->company_name,    
            ],
            
            'city'=>[
                'id' => $this?->city?->id,
                'elm_id' => $this?->city?->elm_id,
            ],
            'region'=>[
                'id' => $this?->region?->id,
                'elm_id' => $this?->region?->elm_id,
            ],
            'car_type'=>[
                'id' => $this?->carType?->id,
                'elm_id' => $this?->carType?->elm_id,
            ],
           
        ];
    }
}
