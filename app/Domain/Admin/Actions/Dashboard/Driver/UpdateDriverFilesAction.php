<?php

namespace App\Domain\Admin\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Driver\Models\Driver;

class UpdateDriverFilesAction
{
    protected DriverRepository $driverRepository;
    protected ImageService $imageService;


    public function __construct(DriverRepository $driverRepository, ImageService $imageService)
    {
        $this->driverRepository = $driverRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $driverId, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $driver = $this->driverRepository->findByCols(['id' => $driverId]);
        $datataa = [];
        if ($data['files'] > 0) {

            foreach ($data['files'] as $key => $file) {
                if (isset($file->disk)) {
                    $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
                    $fileSaved = array_merge([
                        'collection_name' => $key,
                        'fileable_type' => Driver::class,
                        'fileable_id' => $driverId,
                    ], $image);

                    $driver->files()
                        ->updateOrCreate(['fileable_id' => $driverId, 'collection_name' => $key], $fileSaved);
                }
            }
        }
        return $driver;
    }

}
