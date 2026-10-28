<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Profile;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Services\ImagesServices\ImageService;

class UpdateIbanAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository, ImageService $imageService)
    {
        $this->merchantRepository = $merchantRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $merchantId ,array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['is_temp'] = 1;
        $merchant = $this->merchantRepository->findByCols(['id'=>$merchantId]);
        if (isset($data['iban_file']) && $data['iban_file'] != "")
        {
            $file = $data['iban_file'];
            if ($data['iban_file'] != null && isset($file->disk)) {
                $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['iban_file'] = $image['path'];
            }
        }else{
            unset($data['iban_file']);
        }
        $merchant->iban()->where('is_temp', 1)->updateOrCreate(['is_temp' => 1], $data);
        return $merchant;
    }
}
