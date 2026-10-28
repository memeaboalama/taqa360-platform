<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Services\ImagesServices\ImageService;

class UpdateItemAction
{
    public function __construct(protected ItemRepository $modelR, private ImageService $imageService)
    {
        $this->modelR = $modelR;
        $this->imageService = $imageService;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $model = $this->modelR->findByCols(['id' => $id]);
        $file = $data['image'];

        if ($data['image'] != null && isset($file->disk)) {
            $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
            $data['image'] = $image['path'];
        }else{
            unset($data['image']);
        }

        if (isset($data['addition_group_ids'])) {
            $model->additionGroups()->sync($data['addition_group_ids']);
        }
        $model->update($data);
        $itemPrices = collect($data['item_prices'])->where('status', 'active');
        foreach ($itemPrices as $option) 
        {
            $model->itemPrices()->create([
                'type_service_id' => $option['type_service_id'],
                'price' => $option['price'],
                'status' => $option['status'],
            ]);
        }
        return $model;
    }
}
