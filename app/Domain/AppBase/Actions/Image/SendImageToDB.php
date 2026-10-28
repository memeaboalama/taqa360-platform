<?php

namespace App\Domain\AppBase\Actions\Image;

use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;

class SendImageToDB
{
    private $imageService;


    public function __construct()
    {
        $this->imageService = new ImageService();
    }


    public function handle($user, $image, $path, $type)
    {
        $data = $this->imageService->storeImage($image, $path, $type);
        return $user->files()->create($data);
    }
}
