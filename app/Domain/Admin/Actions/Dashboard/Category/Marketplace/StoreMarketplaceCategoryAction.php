<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Marketplace;

use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;
use App\Support\Services\ImagesServices\ImageService;

class StoreMarketplaceCategoryAction
{
    protected CategoryRepository $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository, ImageService $imageService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->imageService = $imageService;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $file = $data['image'];
        if (isset($file->disk)) {
            $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
            $data['image'] = $image['path'];
        }
        return $this->categoryRepository->store($data);
    }
}
