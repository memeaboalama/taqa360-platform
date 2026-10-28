<?php

namespace App\Domain\Provider\Actions\Dashboard\Vehicles;

use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Models\CompanyProviderVehicle;
use App\Domain\Provider\Repositories\VehicleRepository;
use Illuminate\Support\Facades\DB;

class FetchVehiclesAction
{
    protected VehicleRepository $vehicleRepository;


    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function handle(): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->vehicleRepository->all(cols: ['id', 'plate_number', 'year']
            ,condition: ['vehicleable_id' => auth()->guard('company')->user()->id, 'vehicleable_type' => Company::class]);
    }
}
