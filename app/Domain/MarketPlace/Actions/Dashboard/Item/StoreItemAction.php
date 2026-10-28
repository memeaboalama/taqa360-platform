<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Item;

use App\Support\Services\ImagesServices\ImageService;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use Illuminate\Support\Facades\Log;

class StoreItemAction
{

    public function __construct(protected ItemRepository $modelR, private ImageService $imageService)
    {
        $this->modelR = $modelR;
        $this->imageService = $imageService;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        Log::info("STORE ITEM ACTION", [$data]);

        try {
            $data['merchant_id'] = auth('merchant')->id();

            $file = $data['image'];
            if (isset($file->disk)) {
                $image = $this->imageService->storeImage($file, 'upload/products', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['image'] = $image['path'];
            }

            $model = $this->modelR->store($data);

            if (isset($data['addition_group_ids'])) {
                $model->additionGroups()->sync($data['addition_group_ids']);
            }

            $itemPrices = collect($data['item_prices'])->where('status', 'active');
            foreach ($itemPrices as $option) {
                $model->itemPrices()->create([
                    'type_service_id' => $option['type_service_id'],
                    'price' => $option['price'],
                    'status' => $option['status'],
                ]);
            }

            $this->handleAdditionalImages($model, $data);
            $this->handleSpecifications($model, $data);

            return $model;
        } catch (\Exception $e) {
            Log::error('Error storing item: ' . $e->getMessage());
            return null;
        }
    }

    private function handleAdditionalImages($model, $data): void
    {
        if (!isset($data['additional_images']) || empty($data['additional_images'])) {
            return;
        }

        foreach ($data['additional_images'] as $index => $image) {
            try {
                Log::info("Processing additional image {$index}: " . $image->getClientOriginalName());
                $uploadedImage = $this->imageService->storeImage($image, 'upload/products', \App\Support\Enum\FileTypesEnum::LOGO);

                $fileRecord = $model->files()->create([
                    'file_name' => $uploadedImage['file_name'],
                    'original_name' => $uploadedImage['original_name'],
                    'collection_name' => "item_additional_image",
                    'mime_type' => $uploadedImage['mime_type'],
                    'url' => $uploadedImage['url'],
                    'path' => $uploadedImage['path'],
                    'size' => $uploadedImage['size'],
                    'storage' => $uploadedImage['storage'],
                ]);

                Log::info("Additional image {$index} stored successfully with ID: " . $fileRecord->id);

            } catch (\Exception $e) {
                Log::error("Error storing additional image {$index}: " . $e->getMessage());
                continue;
            }
        }
    }

    private function handleSpecifications($model, $data): void
    {
        if (!isset($data['specificationsItems']) || !isset($data['specificationsPropertiesItems'])) {
            return;
        }

        $specificationItems = collect($data['specificationsItems']);
        $specificationsPropertiesItems = collect($data['specificationsPropertiesItems']);

        foreach ($specificationItems as $option) {
            $specification = $model->specificationsItems()->create([
                'specification_id' => $option['id'],
                'name' => $option['name'],
                'desc' => $option['desc'],
                'status' => 'active',
            ]);

            foreach ($specificationsPropertiesItems->where('specification_id', $option['id']) as $optionPro) {
                $model->specificationsPropertiesItems()->create([
                    'specification_id' => $optionPro['specification_id'],
                    'name' => $optionPro['name'],
                    'desc' => $optionPro['desc'],
                    'status' => 'active',
                ]);
            }
        }
    }
}
