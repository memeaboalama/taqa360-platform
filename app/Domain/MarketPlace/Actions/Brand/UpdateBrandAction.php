<?php

namespace App\Domain\MarketPlace\Actions\Brand;

use App\Domain\MarketPlace\Repositories\BrandRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Log;

class UpdateBrandAction
{
    protected BrandRepository $brandRepository;
    protected ImageService $imageService;

    public function __construct(BrandRepository $brandRepository, ImageService $imageService)
    {
        $this->brandRepository = $brandRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $brand_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/brands', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];

                $brand = $this->brandRepository->findByCols(['id' => $brand_id]);
                if ($brand->image) {
                    $this->imageService->destroyImg($brand->image);
                }
            } else {
                unset($data['image']);
            }

            return $this->brandRepository->update($brand_id, $data);

        } catch (\Exception $e) {
            Log::error('Error updating brand: ' . $e->getMessage());
            return null;
        }
    }
}
