<?php

namespace App\Domain\Admin\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchDriverAction
{
    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function handle(): Collection
    {
        return $this->driverRepository->all();
    }
}
