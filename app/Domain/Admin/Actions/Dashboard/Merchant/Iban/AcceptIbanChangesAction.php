<?php

namespace App\Domain\Admin\Actions\Dashboard\Merchant\Iban;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Services\ImagesServices\ImageService;

class AcceptIbanChangesAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository, ImageService $imageService)
    {
        $this->merchantRepository = $merchantRepository;
        $this->imageService = $imageService;
    }

    public function handle(int $merchantId , array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $merchant = $this->merchantRepository->findByCols(['id'=>$merchantId]);
        $oldIban = $merchant->iban()->where('is_temp', 1)->first();
//        $merchant->iban()->where('is_temp', 1)->delete();
        $data['iban_file'] = $oldIban?->iban_file;
        $merchant->iban()->where('is_temp', 0)->updateOrCreate(['is_temp' => 0], $data);
        return $merchant;
    }

    public function update(int $merchantId , array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $merchant = $this->merchantRepository->findByCols(['id'=>$merchantId]);
        $merchant->iban()->where('is_temp', 0)->update([
            'iban' => $data['current_iban'],
            'bank_name' => $data['current_bank_name'],
            'beneficiary_full_name' => $data['current_beneficiary_full_name'],
        ]);

        $merchant->iban()->where('is_temp', 1)->update([
            'iban' => $data['current_iban'],
            'bank_name' => $data['current_bank_name'],
            'beneficiary_full_name' => $data['current_beneficiary_full_name'],
        ]);

        if (isset($data['iban_file']) && $data['iban_file'] != "")
        {
            $file = $data['iban_file'];
            if ($data['iban_file'] != null && isset($file->disk)) {
                $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['iban_file'] = $image['path'];
                $merchant->iban()->where('is_temp', 0)->update([
                    'iban_file' => $data['iban_file']
                ]);

                $merchant->iban()->where('is_temp', 1)->update([
                    'iban_file' => $data['iban_file']
                ]);
            }
        }else{
            unset($data['iban_file']);
        }
        return $merchant;
    }
}
