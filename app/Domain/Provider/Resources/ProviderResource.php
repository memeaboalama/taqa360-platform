<?php

namespace App\Domain\Provider\Resources;

use App\Domain\User\resources\UserSettingResource;
use App\Domain\User\resources\WalletResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
//            'profile_image'         => new ProfileImageResource($this->whenLoaded('profileImage')),
            'email'                 => $this->email,
            'phone'                 => $this->phone,
            'dialcode'              => $this->dialcode,
            'country_name'          => $this->country_name,
            'country_code'          => $this->country_code,
            'location'              => [
                'latitude'           =>  $this->location->latitude,
                'longitude'          =>  $this->location->longitude,
            ],
            'wallet'                =>  new WalletResource($this->whenLoaded('wallet')),
        ];
    }
}
