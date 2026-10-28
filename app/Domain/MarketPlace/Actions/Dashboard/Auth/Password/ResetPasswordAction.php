<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Auth\Password;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    protected MerchantRepository $merchantRepository;


    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $merchant = $this->merchantRepository->findByCols(['phone'=>$data['phone']]);
        $merchant->update([
           'password' => $data['password']
        ]);
        Auth::guard('merchant')->login($merchant);
        return $merchant;
    }
}
