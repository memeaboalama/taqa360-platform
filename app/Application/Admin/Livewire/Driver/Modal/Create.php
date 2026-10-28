<?php

namespace Application\Admin\Livewire\Driver\Modal;

use App\Domain\Admin\Actions\Dashboard\Driver\StoreDriverAction;
use App\Domain\AppBase\Actions\Car\FetchCarTypesAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;


    public $name, $email, $password, $phone, $password_confirmation, $status = 'active', $taken_commission = 0;
    public $Id_expire_date = null, $Id_number = null, $iban = null;
    public  $country_id = 1,$region_id, $city_id, $district_id;
    public mixed $counties = [],$regions=[], $districts = [], $cities = [] ,$cars_types =[];
    public $IdentityTypeId,$dateOfBirth,$car_type_id,$carNumber,$vehicleSequenceNumber;
    protected $listeners = ['updateCountry','updateCity', 'updateDistrict'];
//    public $latitude = "24.774265", $longitude = "46.738586";
    protected StoreDriverAction $storeDriverAction;

//    protected $listeners = [
//        'set:latitude-longitude' => 'setLatitudeLongitude'
//    ];

//    public function setLatitudeLongitude($latitude, $longitude)
//    {
//        $this->latitude = $latitude;
//        $this->longitude = $longitude;
//    }

    public function mount(FetchCountriesAction $fetchCountriesAction ,FetchCarTypesAction $fetchCarTypesAction)
    {
        $this->counties = $fetchCountriesAction->handle();
        $this->cars_types = $fetchCarTypesAction->handle(activeOnly: true);
        $this->regions = Region::where('country_id', $this->country_id)->get();
    }
    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|string|min:2|max:200|unique:admins,email',
            'phone' => 'required|string|min:2|max:200|unique:drivers,phone',
            'password' => 'required|string|min:6|max:50|confirmed',
            'status' => 'required|string|in:active,inactive',
            'country_id' => 'required|int|min:1',
            'city_id' => 'required|int|min:1',
            'district_id' => '',
            'IdentityTypeId' => 'required',
            'region_id' => 'required',
            'dateOfBirth' => 'required',
            'car_type_id' => 'required',
            'carNumber' => 'required',
            'vehicleSequenceNumber' => 'required',
            'Id_expire_date' => 'nullable',
            'Id_number' => 'nullable',
            'iban' => 'nullable',
            'taken_commission' => 'nullable|numeric|min:0|max:100',
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

    public function updatedCountryId($country_id): void
    {
        $this->reset('region_id', 'city_id', 'district_id', 'regions', 'cities', 'districts');
        $this->country_id = $country_id;
        $this->regions = Region::where('country_id', $country_id)->get();
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
    

    public function store(StoreDriverAction $storeDriverAction): void
    {
        $data = $this->validate();
        $data['is_complete'] = 1;
        DB::beginTransaction();
        $storeDriverAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createDriver');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->reset('name',
            'email',
            'taken_commission',
            'phone',
            'password',
            'status',
            'password_confirmation',
            'country_id',
            'city_id',
            'district_id',
            'Id_expire_date',
            'iban',
            'IdentityTypeId',
            'region_id',
            'dateOfBirth' ,
            'car_type_id' ,
            'carNumber',
            'vehicleSequenceNumber'
        );
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modal.create');
    }
}
