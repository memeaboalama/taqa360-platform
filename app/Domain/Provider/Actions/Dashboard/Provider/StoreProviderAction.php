<?php

namespace App\Domain\Provider\Actions\Dashboard\Provider;
use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Models\Provider;
use App\Domain\Provider\Repositories\VehicleRepository;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StoreProviderAction
{
    protected ProviderRepository $providerRepository;
    protected VehicleRepository $vehicleRepository;


    public function __construct(ProviderRepository $providerRepository, VehicleRepository $vehicleRepository)
    {
        $this->providerRepository = $providerRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data = $this->reformat($data);
        $provider = $this->providerRepository->store($data);
        if ($data['vehicle_id'] != "other")
        {
            $this->assignVehicle($data, $provider);
        }else{
            $this->vehicleRepository->create($data, $provider);
        }
        return $provider;
    }

    protected function assignVehicle(array $data, $provider)
    {
        $vehicle = $this->vehicleRepository->findByCols(['id'=>$data['vehicle_id']]);
        $companyVehicle = $vehicle->companyVehicle()
            ->where('company_id', auth()->guard('company')->user()->id)
            ->latest('id')
            ->first();
        if ($companyVehicle)
        {
            $companyVehicle->update(['drafted_at'=> now()]);
            $vehicle->companyVehicle()->create([
                'company_id' => $companyVehicle->company_id,
                'provider_id' => $provider->id,
                'vehicle_id' => $companyVehicle->vehicle_id,
            ]);
        }else{
            $vehicle->companyVehicle()->create([
                'company_id' => auth()->guard('company')->user()->id,
                'provider_id' => $provider->id,
                'vehicle_id' => $data['vehicle_id'],
            ]);
        }
    }

    protected function reformat(array $data) : array
    {
        $data['company_id'] = auth()->guard('company')->user()->id;
        $data['location'] = new Point(38.8976763, -77.0365298, Srid::WGS84->value);
        $data['password'] = Hash::make($data['password']);
        $data['type'] = auth()->guard('company')->user()->type;
        return $data;
    }
}

