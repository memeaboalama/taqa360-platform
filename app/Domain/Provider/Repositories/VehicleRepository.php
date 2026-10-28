<?php

namespace App\Domain\Provider\Repositories;

use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Models\Vehicle;
use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Database\Eloquent\Model;

class VehicleRepository extends EloquentMainRepository
{
    public function __construct(Vehicle $model)
    {
        parent::__construct($model);
    }

    public function create(array $data, $provider) : ?Model
    {
        $data = [
            'brand_id' => $data['brand_id'],
            'model_id' => $data['model_id'],
            'vehicleable_type' => Company::class,
            'vehicleable_id' => auth()->guard('company')->user()->id,
            'plate_number' => $data['plate_number'],
            'year' => $data['year'],
            'is_towing' => (auth()->guard('company')->user()?->type == "towing") ? "active" : "inactive",
            'towing_type_id' => $data['towing_type_id'],
            'status' => "active",
        ];
        $vehicle = $this->store($data);
        $vehicle->companyVehicle()->create([
            'company_id' => auth()->guard('company')->user()->id,
            'provider_id' => $provider->id,
            'vehicle_id' => $vehicle->id,
        ]);
        return $vehicle;
    }
}
