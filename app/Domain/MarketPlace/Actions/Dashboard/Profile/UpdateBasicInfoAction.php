<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Profile;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Services\ImagesServices\ImageService;
use Domain\MarketPlace\Models\Merchant;
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
        if (isset($data['main_category_ids']))
            $data['main_categories'] = implode(",", $data['main_category_ids']);


        $merchant = $this->merchantRepository->findByCols(['id'=>$merchantId]);
        $merchant->update($data);
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
