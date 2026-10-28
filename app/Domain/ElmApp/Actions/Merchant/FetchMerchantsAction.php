<?php

namespace App\Domain\ElmApp\Actions\Merchant;

use App\Domain\MarketPlace\Filters\MerchantFilter;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;


class FetchMerchantsAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }
    public function handle(array $data)
    {
        $paginate = $data["per_page"] ?? 10;
        return $this->merchantRepository->filter($data,['*'],['branches'])->filter(new MerchantFilter($data))->get()->paginate($paginate);
    }
}
