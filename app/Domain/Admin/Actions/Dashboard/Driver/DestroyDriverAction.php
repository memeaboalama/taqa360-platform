<?php

namespace App\Domain\Admin\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Enum\StatusEnum;

class DestroyDriverAction
{
    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }


    public function handle(int $id): bool
    {
        return $this->driverRepository->findByCols(['id' => $id])->delete();
    }
}
