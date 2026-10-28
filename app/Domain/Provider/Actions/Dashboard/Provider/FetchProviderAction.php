<?php

namespace App\Domain\Provider\Actions\Dashboard\Provider;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Support\Enum\StatusEnum;
use Domain\Provider\Repositories\ProviderRepository;

class FetchProviderAction
{
    protected ProviderRepository $providerRepository;


    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }


    public function handle(int $provider_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->providerRepository->findByCols(['id'=>$provider_id]);
    }

    public function getTop(int $count): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->providerRepository
            ->all(cols: ['id', 'name', 'phone', 'email']
                , condition: ['status' => StatusEnum::ACTIVE
                    , 'company_id' => auth()->guard('company')->user()->id])->take($count);
    }

}

