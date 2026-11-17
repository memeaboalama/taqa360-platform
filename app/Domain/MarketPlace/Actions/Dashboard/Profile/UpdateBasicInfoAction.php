<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Profile;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Services\ImagesServices\ImageService;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class UpdateBasicInfoAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository, ImageService $imageService)
    {
        $this->merchantRepository = $merchantRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $merchantId ,array $data = [] , array $cropImageInfo = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['main_categories'] = empty($data['main_category_ids'] ?? [])
            ? null
            : implode(',', $data['main_category_ids']);
        unset($data['main_category_ids']);


        $merchant = $this->merchantRepository->findByCols(['id'=>$merchantId]);
        $previousBrandId = $merchant->brand_id;
        $merchant->update($data);
        if ((string) $previousBrandId !== (string) $merchant->brand_id) {
            foreach ($merchant->items()->pluck('id') as $itemId) {
                Cache::forget("storefront_product_details_{$itemId}_v4");
            }
        }
        $merchant->categories()->detach();
        $merchant->categories()->attach($data['marketplace_category_id']);

        // $file = $data['app_logo'];
        // if ($data['app_logo'] != null && isset($file->disk)) {
        //     $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
        //     $fileSaved = array_merge([
        //         'collection_name' => "app_logo",
        //         'fileable_type' => Merchant::class,
        //         'fileable_id' => $merchantId,
        //     ], $cropImageInfo);

        //     $merchant->files()->updateOrCreate(['fileable_id' => $merchantId, 'collection_name' => "app_logo"], $fileSaved);
        // }


        if ($cropImageInfo != null) {
            $fileSaved = array_merge([
                'collection_name' => "app_logo",
                'fileable_type' => Merchant::class,
                'fileable_id' => $merchantId,
            ], $cropImageInfo);

            $merchant->files()->updateOrCreate(['fileable_id' => $merchantId, 'collection_name' => "app_logo"], $fileSaved);
        }
        return $merchant;
    }
}
