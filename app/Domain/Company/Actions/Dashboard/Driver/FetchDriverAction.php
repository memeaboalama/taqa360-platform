<?php

namespace App\Domain\Company\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchDriverAction
{
    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }


    public function handle(int $company_id): Collection
    {
        return $this->driverRepository->all(condition: ['company_id' => $company_id]);
    }
}
