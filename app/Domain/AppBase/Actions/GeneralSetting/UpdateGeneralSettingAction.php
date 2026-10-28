<?php

namespace App\Domain\AppBase\Actions\GeneralSetting;

use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Repositories\GeneralSettingRepository;
use App\Support\Services\ImagesServices\ImageService;

class UpdateGeneralSettingAction
{
    private GeneralSettingRepository $generalSettingRepository;

    public function __construct(GeneralSettingRepository $generalSettingRepository, ImageService $imageService)
    {
        $this->generalSettingRepository = $generalSettingRepository;
        $this->imageService = $imageService;
    }

    public function handle(array $data): \Illuminate\Database\Eloquent\Model|null
    {
        if (isset($data['auth_page_image']) && $data['auth_page_image'] != "")
        {
            $file = $data['auth_page_image'];
            if ($data['auth_page_image'] != null && isset($file->disk)) {
                $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['auth_page_image'] = $image['path'];
            }
        }else{
            unset($data['auth_page_image']);
        }
        return $this->generalSettingRepository->update(1, $data);
    }
}
