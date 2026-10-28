<?php

namespace Application\Company\Livewire\Driver\Tabs;

use App\Domain\AppBase\Actions\Car\FetchCarTypesAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\Company\Actions\Dashboard\Driver\FetchSingleDriverAction;
use App\Domain\Company\Actions\Dashboard\Driver\UpdateDriverAction;
use App\Support\Traits\Alerts\WithLocalizedAlerts;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Info extends Component
{
    use LivewireAlert;
    use WithLocalizedAlerts;

    public mixed $countries = [], $districts = [], $regions=[], $cities = [] ,$cars_types =[];
    public $name, $email, $phone, $status = 'active';
    public $driver, $taken_commission ;
    public $driverId;
    public $country_id = 1,$region_id=1, $city_id = 1, $district_id = null;
    public mixed $company_id;
    public $Id_expire_date = null, $Id_number = null, $iban = null;
    public $IdentityTypeId,$dateOfBirth,$car_type_id,$carNumber,$vehicleSequenceNumber;

    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];
//    public $latitude = "", $longitude = "";
    protected UpdateDriverAction $updateDriverAction;

//    protected $listeners = [
//        'set:latitude-longitude' => 'setLatitudeLongitude'
//    ];
//
//    public function setLatitudeLongitude($latitude, $longitude)
//    {
//        $this->latitude = $latitude;
//        $this->longitude = $longitude;
//    }

    public function mount(int $id, FetchSingleDriverAction $fetchSingleDriverAction, FetchCountriesAction $fetchCountriesAction ,FetchCarTypesAction $fetchCarTypesAction): void
    {
        $this->driverId = $id;
        $this->driver = $fetchSingleDriverAction->handle($id);
        if ($this->driver) {
            $this->name = $this->driver->name;
            $this->email = $this->driver->email;
            $this->status = $this->driver->status->value;
            $this->phone = $this->driver->phone;
            $this->taken_commission = $this->driver->taken_commission??0;
            $this->company_id = $this->driver->company_id;
            $this->country_id = 1;
            $this->countries = $fetchCountriesAction->handle();
            $this->regions = Region::where('country_id', $this->driver->country_id)->get();
            $this->cities = City::where('region_id', $this->driver->region_id)->get();
            $this->cars_types = $fetchCarTypesAction->handle(activeOnly: true);
            $this->city_id = $this->driver?->city_id;
            $this->region_id = $this->driver?->region_id;
            $this->IdentityTypeId = $this->driver?->IdentityTypeId;
            $this->dateOfBirth = $this->driver?->dateOfBirth;
            $this->car_type_id = $this->driver?->car_type_id;
            $this->carNumber = $this->driver?->carNumber;
            $this->vehicleSequenceNumber = $this->driver?->vehicleSequenceNumber;
            $this->districts = Distract::where('city_id', $this->driver->city_id)->get();
            $this->district_id = $this->driver?->district_id;
            $this->Id_expire_date = $this->driver?->Id_expire_date;
            $this->Id_number = $this->driver?->Id_number;
            $this->iban = $this->driver?->iban;
            $this->emit('renderSelect');
//            $this->latitude = $this->driver->location->latitude;
//            $this->longitude = $this->driver->location->longitude;
        }else{
            abort(404);
        }
    }
    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|string|min:2|max:200|unique:drivers,email,' . $this->driverId,
            'phone' => 'required|string|min:2|max:200|unique:drivers,phone,'. $this->driverId,
            'status' => 'required|string|in:active,inactive',
            'taken_commission' => 'nullable',
            'country_id' => 'nullable|int|min:1',
            'city_id' => 'nullable|int|min:1',
            'district_id' => 'nullable',
            'Id_expire_date' => 'nullable',
            'Id_number' => 'nullable',
            'iban' => 'nullable',
            'IdentityTypeId' => 'nullable',
            'region_id' => 'nullable|int|min:1',
            'dateOfBirth' => 'nullable',
            'car_type_id' => 'nullable|int|min:1',
            'carNumber' => 'required',
            'vehicleSequenceNumber' => 'nullable',
//            'latitude' => 'nullable',
//            'longitude' => 'nullable',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function updateCountry($country): void
    {
        if($country != "") {
            $country = json_decode($country);
            $this->reset('country_id', 'regions');
            $this->country_id = $country->id;
            $this->regions = Region::where('country_id', $country->id)->get();
        }else{
            $this->regions = [];
            $this->cities = [];
            $this->districts = [];
        }
    }    
    public function updatedRegionId($region_id): void
    {
        $this->reset('city_id');
        $this->region_id = $region_id;
        $this->cities = City::where('region_id', $region_id)->get();
    }

    public function updatedCityId($city_id): void
    {
        $this->reset('district_id');
        $this->city_id = $city_id;
        $this->districts = Distract::where('city_id', $city_id)->get();

    }

    public function updateDistrict($district_id): void
    {
        $this->district_id = $district_id;
    }

    public function store(UpdateDriverAction $updateDriverAction)
    {
        $data = $this->validate();
        DB::beginTransaction();
        try {
            $updateDriverAction->handle($this->driverId, $data);
            DB::commit();
            $this->localizedAlert('success', __('company::home.data_updated_successfully'), ['timer' => 3000]);
            $this->dispatchBrowserEvent('redirect-after-alert', ['url' => url('company/drivers')]);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->localizedAlert('error', __('admin::home.something_went_wrong'));
        }
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.driver.tabs.info');
    }
}
