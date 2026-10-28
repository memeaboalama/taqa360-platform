<?php

namespace Application\Company\Livewire\Provider\Modal;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Car\Model;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Domain\Provider\Actions\Dashboard\Provider\FetchSingleProviderAction;
use App\Domain\Provider\Actions\Dashboard\Provider\UpdateProviderAction;
use App\Domain\Provider\Actions\Dashboard\Vehicles\FetchVehiclesAction;
use App\Domain\Provider\Models\CompanyProviderVehicle;
use App\Domain\Provider\Models\Provider;
use App\Domain\Towing\Models\TowingType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $status = 'active';
    public Collection $brands, $models;
    public bool $showVehicle = false;
    public $brand_id, $model_id;
    public int $company_id, $country_id;
    public
        $name,
        $email,
        $phone,
        $dialcode,
        $country_name,
        $password,
        $country_code,
        $provider_id_num,
        $provider_id_file_path,
        $nationality_id,
        $plate_number,
        $year,
        $vehicles,
        $vehicle_id,
        $towing_types,
        $towing_type_id,
        $password_confirmation;

    public int|null $provider_id;
    public Provider $provider;

    public $listeners = ['updateProvider', 'updateVehicle'];

    public function mount(FetchVehiclesAction $fetchVehiclesAction)
    {
        $this->nationalities = Nationality::all();
        $this->countries = Country::all();
        $this->towing_types = TowingType::where('status', 'active')->get();
        $this->brands = Brand::all();
        $this->models = collect();
        $this->vehicles = $fetchVehiclesAction->handle();
    }

    public function updateProvider($id, FetchSingleProviderAction $fetchSingleProviderAction): void
    {
        $provider = $fetchSingleProviderAction
            ->handle($id, auth('company')->user()->id);

        if ($provider) {
            $this->name = $provider->name;
            $this->email = $provider->email;
            $this->phone = $provider->phone;
            $this->dialcode = $provider->dialcode;
            $this->provider_id_num = $provider->provider_id_num;
            $this->country_name = $provider->country_name;
            $this->country_code = $provider->country_code;
            $this->company_id = $provider->company_id;
            $this->nationality_id = $provider->nationality_id ?? "";
            $this->country_id = $provider->country_id;
            $this->provider_id = $provider->id;
            $this->vehicle_id = $this->getVehicleId($provider);
            $this->emit('openModal', '#updateProvider');
        }
    }


    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateProviderAction $updateProviderAction): void
    {
        $data = $this->validate([
            'vehicle_id' => 'required',
            'nationality_id' => 'required|numeric|exists:nationalities,id',
            'brand_id' => 'required_if:vehicle_id,==,other',
            'model_id' => 'required_if:vehicle_id,==,other',
            'plate_number' => 'required_if:vehicle_id,==,other',
            'year' => 'required_if:vehicle_id,==,other',
            'towing_type_id' => 'required_if:vehicle_id,==,other',
            'country_id' => 'required|numeric|exists:countries,id',
            'provider_id_num' => 'required|numeric',
            'name' => 'required|string|min:3|max:50',
            'password' => 'sometimes|confirmed',
            'phone' => 'required|unique:providers,phone,'. $this->provider_id,
            'email' => 'required|email|unique:providers,email,'. $this->provider_id,
            'country_name' => 'required',
            'country_code' => 'required',
            'status' => 'required|string|in:active,inactive',
        ]);
        DB::beginTransaction();
        $provider = $updateProviderAction->handle($this->provider_id, $data);
        $this->emit('closeModal', '#updateProvider');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Provider Updated Successfully');
        DB::commit();
    }

    public function updateVehicle(): void
    {
        if ($this->vehicle_id == "other")
            $this->showVehicle = true;
        else
            $this->showVehicle = false;
    }

    public function updateBrand(): void
    {
        if ($this->brand_id != "")
        {
            $this->models = Model::where('brand_id', $this->brand_id)->select(['id', 'title'])->get();
            $this->model_id = $this->models->first()->id ?? null;
        }else{
            $this->model_id = null;
        }
    }

    protected function getVehicleId($provider)
    {
        if (isset($provider->vehicles->first()->id))
            return $provider->vehicles->first()->id;
        $vehicleAssigned = CompanyProviderVehicle::where([
            'provider_id' => $provider->id
            , 'company_id' => auth()->guard('company')->user()->id
            , 'drafted_at' => null
        ])->latest('id')->first();
        return $vehicleAssigned?->vehicle_id ?? null;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.provider.modal.update');
    }
}
