<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Item;

use App\Domain\Catalog\Actions\Specification\AttachMasterSpecificationToItemAction;
use App\Domain\Catalog\Data\Specification\AttachMasterSpecificationData;
use App\Support\Services\ImagesServices\ImageService;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdateItemAction
{
    public function __construct(
        protected ItemRepository $modelR,
        private ImageService $imageService,
        private AttachMasterSpecificationToItemAction $attachMasterSpecification,
    ) {
        $this->modelR = $modelR;
        $this->imageService = $imageService;
    }

    public function handle($id, array $data = [], ?int $merchantId = null): ?\Illuminate\Database\Eloquent\Model
    {
        try {
            $conditions = ['id' => $id];
            if ($merchantId !== null) {
                $conditions['merchant_id'] = $merchantId;
            }
            $model = $this->modelR->findByCols($conditions);

            if (!$model) {
                throw new \Exception('Product not found');
            }

            $masterSpecificationIds = array_values(array_unique(array_map(
                'intval',
                $data['master_specification_ids'] ?? []
            )));
            $specificationValues = $data['specification_values'] ?? [];
            $specificationFile = $data['specification_file'] ?? null;
            $removeSpecificationFile = (bool) ($data['remove_specification_file'] ?? false);
            unset(
                $data['master_specification_ids'],
                $data['specification_values'],
                $data['specification_file'],
                $data['remove_specification_file']
            );

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

            $this->syncMasterSpecifications($model, $masterSpecificationIds, $specificationValues);
            $this->handleSpecificationFile($model, $specificationFile, $removeSpecificationFile);

            if (isset($data['addition_group_ids'])) {
                $model->additionGroups()->sync($data['addition_group_ids']);
            } else {
                $model->additionGroups()->detach();
            }

            $this->handleItemPrices($model, $data);
            Cache::forget("storefront_product_details_{$model->id}_v5");

            $this->handleAdditionalImages($model, $data);
            return $model;

        } catch (\Exception $e) {
            Log::error('Error updating item: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    private function syncMasterSpecifications($model, array $masterSpecificationIds, array $propertyValues): void
    {
        foreach ($model->catalogSpecifications()->get() as $specification) {
            if (!empty($specification->meta['master_specification_id'])) {
                $specification->delete();
            }
        }

        foreach ($masterSpecificationIds as $specificationId) {
            $this->attachMasterSpecification->execute(new AttachMasterSpecificationData(
                item_id: $model->id,
                master_specification_id: $specificationId,
            ), $propertyValues);
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
        $filesToDelete = $model->files()
            ->where('collection_name', 'item_additional_image')
            ->whereNotIn('id', $existingFileIds)
            ->get();

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

    private function handleSpecificationFile($model, $file, bool $removeExistingFile): void
    {
        if (!$file && !$removeExistingFile) {
            return;
        }

        $existingFile = $model->files()
            ->where('collection_name', 'item_specification')
            ->first();

        if ($file) {
            $path = $file->store('upload/products/specifications', 'fileDisk');
            if (!$path) {
                throw new \RuntimeException('Unable to store the product specification file.');
            }

            $disk = Storage::disk('fileDisk');
            $model->files()->create([
                'file_name' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType() ?: 'application/pdf',
                'collection_name' => 'item_specification',
                'type' => 'technical_specification',
                'url' => $disk->url($path),
                'path' => $path,
                'size' => $file->getSize(),
                'storage' => 'fileDisk',
            ]);
        }

        if ($existingFile) {
            if ($existingFile->path && $existingFile->storage) {
                $disk = Storage::disk($existingFile->storage);
                if ($disk->exists($existingFile->path) && !$disk->delete($existingFile->path)) {
                    throw new \RuntimeException('Unable to remove the previous product specification file.');
                }
            }

            $existingFile->delete();
        }
    }
}
