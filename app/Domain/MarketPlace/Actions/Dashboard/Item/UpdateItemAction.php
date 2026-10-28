<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Item;

use App\Support\Services\ImagesServices\ImageService;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateItemAction
{
    public function __construct(protected ItemRepository $modelR, private ImageService $imageService)
    {
        $this->modelR = $modelR;
        $this->imageService = $imageService;
    }

    public function handle($id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            $model = $this->modelR->findByCols(['id' => $id]);

            if (!$model) {
                throw new \Exception('Product not found');
            }

            $this->handleExistingFiles($model, $data);

            if (isset($data['image']) && is_object($data['image'])) {
                $file = $data['image'];
                if (isset($file->disk)) {
                    $image = $this->imageService->storeImage($file, 'upload/products', \App\Support\Enum\FileTypesEnum::LOGO);
                    $data['image'] = $image['path'];
                }
            } else {
                unset($data['image']);
                Log::info("No main image to update");
            }

            $model = $this->modelR->update($model->id, $data);

            if (isset($data['addition_group_ids'])) {
                $model->additionGroups()->sync($data['addition_group_ids']);
            } else {
                $model->additionGroups()->detach();
            }

            $this->handleItemPrices($model, $data);

            $this->handleAdditionalImages($model, $data);
            return $model;

        } catch (\Exception $e) {
            Log::error('Error updating item: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    private function handleItemPrices($model, $data): void
    {
        $model->itemPrices()->delete();

        $itemPrices = collect($data['item_prices'])->where('status', 'active');
        foreach ($itemPrices as $option) {
            $model->itemPrices()->create([
                'type_service_id' => $option['type_service_id'],
                'price' => $option['price'],
                'status' => $option['status'],
            ]);
        }
    }

    private function handleAdditionalImages($model, $data): void
    {
        if (!isset($data['additional_images']) || empty($data['additional_images'])) {
            Log::info("No additional images to store");
            return;
        }

        Log::info("Storing " . count($data['additional_images']) . " additional images");

        foreach ($data['additional_images'] as $image) {
            try {
                $uploadedImage = $this->imageService->storeImage($image, 'upload/products/additional', \App\Support\Enum\FileTypesEnum::LOGO);

                $model->files()->create([
                    'file_name' => $uploadedImage['file_name'],
                    'original_name' => $uploadedImage['original_name'],
                    'collection_name' => "item_additional_image",
                    'mime_type' => $uploadedImage['mime_type'],
                    'url' => $uploadedImage['url'],
                    'path' => $uploadedImage['path'],
                    'size' => $uploadedImage['size'],
                    'storage' => $uploadedImage['storage'],
                ]);

            } catch (\Exception $e) {
                Log::error('Error storing additional image: ' . $e->getMessage());
                continue;
            }
        }
    }

    private function handleExistingFiles($model, $data): void
    {
        if (!isset($data['existing_files'])) {
            Log::info("No existing_files array in data");
            return;
        }

        $existingFileIds = collect($data['existing_files'])->pluck('id')->filter()->toArray();
        $filesToDelete = $model->files()->whereNotIn('id', $existingFileIds)->get();

        foreach ($filesToDelete as $file) {
            try {
                if ($file->path && $file->storage) {
                    $deleted = Storage::disk($file->storage)->delete($file->path);
                    Log::info("Storage deletion result: " . ($deleted ? 'Success' : 'Failed'));
                }

                $file->delete();
            } catch (\Exception $e) {
                Log::error('Error deleting file ' . $file->id . ': ' . $e->getMessage());
                Log::error('Deletion error trace: ' . $e->getTraceAsString());
            }
        }
    }
}
