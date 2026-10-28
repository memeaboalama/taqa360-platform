<?php

namespace App\Domain\Admin\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use Illuminate\Support\Facades\Hash;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StoreDriverAction
{
    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
//        $data['location'] = new Point($data['latitude'], $data['longitude'], 4326);
        $data['password'] = Hash::make($data['password']);
        return $this->driverRepository->store($data);
    }
}
