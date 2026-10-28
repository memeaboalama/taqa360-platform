<?php

namespace App\Domain\Provider\Actions\Dashboard\Auth;

use App\Domain\Provider\Repositories\DriversRegisters\DriversRegistersRepository;
use App\Support\Services\ImagesServices\ImageService;
use App\Domain\Provider\Models\DriversRegisters;

class UpdateRegisterDriveFilesAction
{
    protected DriversRegistersRepository $driverRepository;
    protected ImageService $imageService;


    public function __construct(DriversRegistersRepository $driverRepository, ImageService $imageService)
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
                        'fileable_type' => DriversRegisters::class,
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
