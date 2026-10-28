<?php

namespace App\Domain\Driver\Actions\Auth;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Driver\Models\Driver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MatanYadaev\EloquentSpatial\Objects\Point;

class RegisterDriverAction
{
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository, ImageService $imageService)
    {
        $this->driverRepository = $driverRepository;
        $this->imageService = $imageService;
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function handle(array $data): ?Model
    {
        $data['location'] = new Point($data['latitude'], $data['longitude'], 4326);
        $data['password'] = Hash::make($data['password']);
        $data['country_id'] = 1;
        $driver = $this->driverRepository->store($data);
        if ($data['files'] > 0) {
            foreach ($data['files'] as $key => $file) {
                $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
                $fileSaved = array_merge([
                    'collection_name' => $key,
                    'fileable_type' => Driver::class,
                    'fileable_id' => $driver->id,
                ], $image);

                $driver->files()
                    ->updateOrCreate(['fileable_id' => $driver->id, 'collection_name' => $key], $fileSaved);
            }
        }
        return $driver;
    }
}
