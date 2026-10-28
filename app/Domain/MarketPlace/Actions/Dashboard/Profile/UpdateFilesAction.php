<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Profile;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Services\ImagesServices\ImageService;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\Storage;

class UpdateFilesAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository, ImageService $imageService)
    {
        $this->merchantRepository = $merchantRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $merchantId, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $merchant = $this->merchantRepository->findByCols(['id' => $merchantId]);
        $datataa = [];
        if ($data['files'] > 0) {

            foreach ($data['files'] as $key => $file) {
                if (isset($file->disk)) {
                    $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);

                    //    $file->storeAs('upload/file', $image['file_name'], 'public');
                    $fileSaved = array_merge([
                        'collection_name' => $key,
                        'fileable_type' => Merchant::class,
                        'fileable_id' => $merchantId,
                    ], $image);

                    $merchant->files()
                        ->updateOrCreate(['fileable_id' => $merchantId, 'collection_name' => $key], $fileSaved);

                    $datataa[] = $fileSaved;
                }
            }
        }
        //		dd($datataa);
        return $merchant;
    }
}
