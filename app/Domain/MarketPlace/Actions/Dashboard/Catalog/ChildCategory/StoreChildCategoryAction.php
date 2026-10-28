<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\ChildCategory;

use App\Domain\MarketPlace\Repositories\Catalog\ChildCategoryRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Log;

class StoreChildCategoryAction
{
    protected ChildCategoryRepository $childcategoryRepository;
    protected ImageService $imageService;

    public function __construct(ChildCategoryRepository $childcategoryRepository, ImageService $imageService)
    {
        $this->childcategoryRepository = $childcategoryRepository;
        $this->imageService = $imageService;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/childcategories', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];
            }

            $category = $this->childcategoryRepository->store($data);
            return $category;

        } catch (\Exception $e) {
            Log::error('Error storing child category: ' . $e->getMessage());
            return null;
        }
    }
}
