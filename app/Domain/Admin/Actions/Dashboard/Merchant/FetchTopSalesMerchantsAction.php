<?php

namespace App\Domain\Admin\Actions\Dashboard\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use Illuminate\Support\Collection;

class FetchTopSalesMerchantsAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    public function getTop(int $count): Collection
    {
        return $this->merchantRepository->fetchTopSales($count);
    }
}
