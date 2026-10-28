<?php

namespace App\Domain\Provider\Actions\Service;

use App\Domain\Provider\Repositories\ProviderSubServiceRepository;
use App\Support\Enum\ServiceStrategy;
use App\Support\Enum\StatusEnum;

class FetchTowingServiceAction
{


    private ProviderSubServiceRepository $providerSubServiceRepository;

    public function __construct(ProviderSubServiceRepository $providerSubServiceRepository)
    {
        $this->providerSubServiceRepository = $providerSubServiceRepository;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function handle(array $data ): mixed
    {
        $cols = [
            'id',
            'provider_main_service_id',
            'provider_category_id',
            'main_category_id',
            'title',
            'price',
            'strategy',
            'towing_domain',
            'towing_type_id',
            'towing_open_price',
            'towing_price_per_km',
            'towing_price_per_minute',
        ];

        $conditions = ['id' => $data['sub_service_id'],'strategy' => ServiceStrategy::TOWING,'status' => StatusEnum::ACTIVE];

        return $this->providerSubServiceRepository->find(cols: $cols, condition: $conditions);
    }
}
