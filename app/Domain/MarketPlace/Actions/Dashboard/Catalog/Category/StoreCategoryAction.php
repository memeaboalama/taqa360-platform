<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category;

use App\Domain\MarketPlace\Repositories\Catalog\CategoryRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreCategoryAction
{
    protected CategoryRepository $categoryRepository;
    protected ImageService $imageService;

    public function __construct(CategoryRepository $categoryRepository, ImageService $imageService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->imageService = $imageService;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        DB::beginTransaction();
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/categories', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];
            }

            $category = $this->categoryRepository->store($data);

            DB::commit();
            return $category;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing category: ' . $e->getMessage());
            return null;
        }
    }
}
