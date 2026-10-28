<?php

namespace App\Domain\Admin\Actions\Dashboard\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Illuminate\Support\Facades\Hash;

class ResetMerchantPasswordAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    public function handle(int $id, array $data)
    {
        return $this->merchantRepository->update($id, ['password' => Hash::make($data['password'])]);
    }
}
