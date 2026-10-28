<?php

namespace App\Domain\Admin\Actions\Dashboard\Banner;

use App\Domain\AppBase\Repositories\BannerRepository;
use App\Support\Services\ImagesServices\ImageService;

class StoreBannerAction
{
    public function __construct(protected BannerRepository $bannerRepository, protected ImageService $imageService){}

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $file = $data['image'];
        if (isset($file->disk)) {
            $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
            $data['image'] = $image['path'];
        }
        return $this->bannerRepository->store($data);
    }
}
