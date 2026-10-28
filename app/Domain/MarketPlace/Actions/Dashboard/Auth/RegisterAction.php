<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Auth;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    protected MerchantRepository $merchantRepository;


    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $data['country_id'] = 1;
        $merchant = $this->merchantRepository->store($data);
        Auth::guard('merchant')->login($merchant);
        return $merchant;
    }

}
