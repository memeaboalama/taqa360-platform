<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory;

use App\Domain\MarketPlace\Repositories\Catalog\SubcategoryRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Log;

class UpdateSubcategoryAction
{
    protected SubcategoryRepository $subcategoryRepository;
    protected ImageService $imageService;

    public function __construct(SubcategoryRepository $subcategoryRepository, ImageService $imageService)
    {
        $this->subcategoryRepository = $subcategoryRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $subcategory_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                $image = $this->imageService->storeImage($file, 'upload/catalogs/subcategories', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];

                $subcategory = $this->subcategoryRepository->findByCols(['id' => $subcategory_id]);
                if ($subcategory->image) {
                    $this->imageService->destroyImg($subcategory->image);
                }
            } else {
                unset($data['image']);
            }

            return $this->subcategoryRepository->update($subcategory_id, $data);

        } catch (\Exception $e) {
            Log::error('Error updating subcategory: ' . $e->getMessage());
            return null;
        }
    }
}
