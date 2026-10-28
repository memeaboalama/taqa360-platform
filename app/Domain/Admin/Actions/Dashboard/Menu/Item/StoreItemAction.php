<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Log;

class StoreItemAction
{
    public function __construct(protected ItemRepository $modelR, private ImageService $imageService)
    {
        $this->modelR = $modelR;
        $this->imageService = $imageService;
    }

    public function handle(array $data = [], $merchantId): ?\Illuminate\Database\Eloquent\Model
    {
        Log::info("Admin STORE ITEM ACTION", [$data]);

        try {
            $data['merchant_id'] = $merchantId;
            $file = $data['image'];
            if (isset($file->disk)) {
                $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
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

            $this->handleSpecifications($model, $data);

            return $model;
        } catch (\Exception $e) {
            Log::error('Error storing item: ' . $e->getMessage());
            return null;
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
