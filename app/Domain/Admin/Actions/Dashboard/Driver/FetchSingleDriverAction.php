<?php

namespace App\Domain\Admin\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;

class FetchSingleDriverAction
{
    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->driverRepository->findByCols(['id' => $id]);
    }
}
