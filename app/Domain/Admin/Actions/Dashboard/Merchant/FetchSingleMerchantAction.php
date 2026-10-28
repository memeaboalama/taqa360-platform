<?php

namespace App\Domain\Admin\Actions\Dashboard\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;

class FetchSingleMerchantAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->merchantRepository->findByCols(['id' => $id]);
    }
}
