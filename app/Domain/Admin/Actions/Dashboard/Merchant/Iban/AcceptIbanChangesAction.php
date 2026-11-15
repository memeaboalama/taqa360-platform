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
        $merchant->iban()->updateOrCreate(['is_temp' => 0], [
            'iban' => $data['iban'],
            'bank_name' => $data['bank_name'],
            'beneficiary_full_name' => $data['beneficiary_full_name'],
            'iban_file' => $oldIban?->iban_file,
        ]);
        $oldIban?->delete();
        return $merchant;
    }

    public function update(int $merchantId , array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $merchant = $this->merchantRepository->findByCols(['id'=>$merchantId]);
        $currentData = [
            'iban' => $data['current_iban'],
            'bank_name' => $data['current_bank_name'],
            'beneficiary_full_name' => $data['current_beneficiary_full_name'],
        ];

        if (isset($data['iban_file']) && $data['iban_file'] != "")
        {
            $file = $data['iban_file'];
            if ($data['iban_file'] != null && isset($file->disk)) {
                $image = $this->imageService->storeImage($file, 'upload/file', \App\Support\Enum\FileTypesEnum::LOGO);
                $data['iban_file'] = $image['path'];
                $currentData['iban_file'] = $data['iban_file'];
            }
        }

        $merchant->iban()->updateOrCreate(['is_temp' => 0], $currentData);

        return $merchant;
    }
}
