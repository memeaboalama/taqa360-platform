<?php

namespace App\Domain\Notification\Actions;


use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;

class SendIDeviceTokenToDB
{


    public function handle($user, array $data)
    {
        return $user->device_tokens()->updateOrCreate(
            ['token' => $data["device_token"]],
            ['token' => $data["device_token"], 'platform' => $data["platform"] ?? 'ios']
        );
    }
}
