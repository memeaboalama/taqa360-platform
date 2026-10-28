<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category;

use App\Domain\MarketPlace\Repositories\Catalog\CategoryRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Log;

class UpdateCategoryAction
{
    protected CategoryRepository $categoryRepository;
    protected ImageService $imageService;

    public function __construct(CategoryRepository $categoryRepository, ImageService $imageService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $category_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/categories', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];

                $category = $this->categoryRepository->findByCols(['id' => $category_id]);
                if ($category->image) {
                    $this->imageService->destroyImg($category->image);
                }
            } else {
                unset($data['image']);
            }

            return $this->categoryRepository->update($category_id, $data);

        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return null;
        }
    }
}
