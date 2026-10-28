<?php

namespace App\Domain\ElmApp\Actions\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use Illuminate\Database\Eloquent\Model;

class FetchDriverAction
{
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }
    public function handle(array $data)
    {
        $paginate = $data["per_page"] ?? 10;
        return $this->driverRepository->getDriversByElmStatus($data, $paginate);
    }

}
