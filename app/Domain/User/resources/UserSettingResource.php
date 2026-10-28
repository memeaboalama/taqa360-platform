<?php

namespace App\Domain\User\resources;

use App\Domain\AppBase\Resources\LanguageResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                =>  $this->id,
            'language'          =>  new LanguageResource($this->whenLoaded('language')),
            'notifications'       =>  $this->notifications,
        ];
    }
}
