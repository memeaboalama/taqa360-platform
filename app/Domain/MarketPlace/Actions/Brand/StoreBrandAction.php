<?php

namespace App\Domain\MarketPlace\Actions\Brand;

use App\Domain\MarketPlace\Repositories\BrandRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreBrandAction
{
    protected BrandRepository $brandRepository;
    protected ImageService $imageService;

    public function __construct(BrandRepository $brandRepository, ImageService $imageService)
    {
        $this->brandRepository = $brandRepository;
        $this->imageService = $imageService;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        DB::beginTransaction();
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/brands', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];
            }

            $brand = $this->brandRepository->store($data);

            DB::commit();
            return $brand;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing brand: ' . $e->getMessage());
            return null;
        }
    }
}
