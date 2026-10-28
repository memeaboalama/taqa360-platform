<?php

namespace Application\Company\Livewire\Provider\Modal;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Car\Model;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Domain\Provider\Actions\Dashboard\Provider\StoreProviderAction;
use App\Domain\Provider\Actions\Dashboard\Vehicles\FetchVehiclesAction;
use App\Domain\Towing\Models\TowingType;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Collection;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public $company_id = null, $nationality_id = null, $country_id = null;
    public Collection $brands, $models;
    public bool $showVehicle = false;
    public $brand_id = null;
    public $model_id = null;
    public
        $name = null,
        $email = null,
        $phone = null,
        $dialcode = null,
        $country_name = null,
        $password,
        $country_code = null,
        $provider_id_num = null,
        $vehicles,
        $vehicle_id = 'other',
        $towing_type_id = null,
        $towing_types,
        $nationalities,
        $countries,
        $plate_number = null,
        $year = null,
        $password_confirmation = null;

    protected $listeners = ['updateVehicle'];


    public function mount(FetchVehiclesAction $fetchVehiclesAction)
    {
        $this->nationalities = Nationality::all();
        $this->countries = Country::all();
        $this->towing_types = TowingType::where('status', 'active')->get();
        $this->brands = Brand::all();
        $this->models = collect();
        $this->vehicles = $fetchVehiclesAction->handle();
    }


    protected function rules(): array
    {
        return [
            'vehicle_id' => 'required',
            'brand_id' => 'required_if:vehicle_id,==,other|numeric|exists:brands,id',
            'model_id' => 'required_if:vehicle_id,==,other|numeric|exists:models,id',
            'towing_type_id' => 'required_if:vehicle_id,==,other|numeric|exists:towing_types,id',
            'plate_number' => 'required_if:vehicle_id,==,other|string|unique:vehicles,plate_number|min:6',
            'year' => 'required_if:vehicle_id,==,other',
            'nationality_id' => 'required|numeric|exists:nationalities,id',
            'country_id' => 'required|numeric|exists:countries,id',
            'provider_id_num' => 'required|numeric',
            'password' => 'required|string|confirmed|min:6',
            'name' => 'required|string|min:3|max:50',
            'phone' => 'required|unique:providers,phone',
            'email' => 'required|email|unique:providers,email|min:3',
            'dialcode' => 'required',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
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

    public function store(StoreProviderAction $storeProviderAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeProviderAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createProvider');
        $this->alert('success', 'Provider Created Successfully');
        DB::commit();
        $this->reset('vehicle_id', 'brand_id', 'model_id', 'towing_type_id'
            , 'plate_number', 'year', 'nationality_id', 'country_id', 'provider_id_num'
            ,'name','phone','email','dialcode');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.provider.modal.create');
    }
}
