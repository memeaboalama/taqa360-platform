<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\ChildCategory;

use App\Domain\MarketPlace\Repositories\Catalog\ChildCategoryRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Log;

class UpdateChildCategoryAction
{
    protected ChildCategoryRepository $childcategoryRepository;
    protected ImageService $imageService;

    public function __construct(ChildCategoryRepository $childcategoryRepository, ImageService $imageService)
    {
        $this->childcategoryRepository = $childcategoryRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $category_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/childcategories', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];

                $childCategory = $this->childcategoryRepository->findByCols(['id' => $category_id]);
                if ($childCategory->image) {
                    $this->imageService->destroyImg($childCategory->image);
                }
            } else {
                unset($data['image']);
            }

            return $this->childcategoryRepository->update($category_id, $data);

        } catch (\Exception $e) {
            Log::error('Error updating child category: ' . $e->getMessage());
            return null;
        }
    }
}
