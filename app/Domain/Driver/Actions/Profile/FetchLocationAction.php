<?php

namespace App\Domain\Driver\Actions\Profile;

use App\Domain\Driver\Repositories\DriverRepository;


class FetchLocationAction
{
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function handle($driver_id)
    {
        return $this->driverRepository->findByCols(['id' => $driver_id],['orders']);
    }
}
