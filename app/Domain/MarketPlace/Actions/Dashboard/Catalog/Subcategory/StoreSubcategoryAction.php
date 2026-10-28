<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory;

use App\Domain\MarketPlace\Repositories\Catalog\SubcategoryRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Log;

class StoreSubcategoryAction
{
    protected SubcategoryRepository $subcategoryRepository;
    protected ImageService $imageService;

    public function __construct(SubcategoryRepository $subcategoryRepository, ImageService $imageService)
    {
        $this->subcategoryRepository = $subcategoryRepository;
        $this->imageService = $imageService;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/subcategories', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];
            }

            $subcategory = $this->subcategoryRepository->store($data);
            return $subcategory;

        } catch (\Exception $e) {
            Log::error('Error storing subcategory: ' . $e->getMessage());
            return null;
        }
    }
}
