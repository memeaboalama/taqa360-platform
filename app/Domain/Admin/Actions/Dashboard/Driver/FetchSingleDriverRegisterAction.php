<?php

namespace App\Domain\Admin\Actions\Dashboard\Driver;

use App\Domain\Provider\Repositories\DriversRegisters\DriversRegistersRepository;

class FetchSingleDriverRegisterAction
{
    protected DriversRegistersRepository $driverRepository;


    public function __construct(DriversRegistersRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->driverRepository->findByCols(['id' => $id]);
    }
}
