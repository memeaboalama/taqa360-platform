<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Profile;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        if (auth()->guard('merchant')->check()) {
            $merchant = $this->merchantRepository->findByCols(['id'=> auth()->guard('merchant')->user()->id]);
            $merchant->update([
                'password' => Hash::make($data['new_password'])
            ]);
            return $merchant;
        }

        if (auth()->guard('merchant_employee')->check()) {
            $employee = auth()->guard('merchant_employee')->user();
            $employee->update([
                'password' => Hash::make($data['new_password'])
            ]);
            return $employee;
        }

        return null;
    }
}