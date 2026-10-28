<?php

namespace App\Domain\User\resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserResource extends JsonResource
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
            'image' => $this->profileUrl,
            //'profile_image'=> new ProfileImageResource($this->whenLoaded('profileImage')),
            'email' => $this->email,
            'is_email_verified' => (bool)$this->email_verified_at,
            'phone' => $this->phone,
            'is_phone_verified' => (bool)$this->phone_verified_at,
            'dialcode' => $this->dialcode,
            'country_name' => $this->country_name,
            'country_code' => $this->country_code,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'last_logged_in_at' => $this->last_logged_in_at->diffForHumans(),
            // 'wallet' => new WalletResource($this->whenLoaded('wallet')),
            'wallet' => $this->hasWallet('UID_'.$this->id.'_Main')  ? $this->getWallet('UID_'.$this->id.'_Main')->balanceFloatNum:0,
            'settings' => new UserSettingResource($this->whenLoaded('userSetting'))
        ];
    }
}
