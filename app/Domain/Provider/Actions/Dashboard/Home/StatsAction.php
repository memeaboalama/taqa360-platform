<?php

namespace App\Domain\Provider\Actions\Dashboard\Home;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use Domain\Provider\Repositories\ProviderRepository;

class StatsAction
{
    protected DriverRepository $driverRepository;
    protected MerchantOrderRepository $merchantOrderRepository;


    public function __construct(
        DriverRepository $driverRepository
        ,MerchantOrderRepository $merchantOrderRepository)
    {
        $this->driverRepository = $driverRepository;
        $this->merchantOrderRepository = $merchantOrderRepository;
    }

    public function handle(int $company_id) : array
    {
        return [
            'ordersCount' => $this->merchantOrderRepository->getCompanyDriversOrders($company_id)->count(),
            'driversCount' => $this->driverRepository->count(['company_id' => $company_id]),
        ];
    }
}
