<?php

namespace Application\Driver\Livewire\Auth;


use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Domain\AppBase\Actions\Car\FetchCarTypesAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use Illuminate\Support\Facades\DB;
use App\Domain\Provider\Actions\Dashboard\Auth\RegisterDriverAction;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Log;

class Register extends Component
{
    use LivewireAlert;


    public $name, $email, $password, $phone, $password_confirmation, $status = 'inactive', $terms = false;
    public  $country_id = 1,$region_id, $city_id, $district_id;
    public mixed $counties = [],$regions=[], $districts = [], $cities = [] ,$cars_types =[];
    public $IdentityTypeId,$dateOfBirth,$car_type_id,$carNumber,$vehicleSequenceNumber;

    protected $listeners = ['updateCountry','updateCity', 'updateDistrict'];
    protected RegisterDriverAction $driversRegistersAction;

    public function mount(FetchCountriesAction $fetchCountriesAction ,FetchCarTypesAction $fetchCarTypesAction)
    {
        $this->counties = $fetchCountriesAction->handle();
        $this->cars_types = $fetchCarTypesAction->handle();
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
            'terms' => 'accepted',
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
    public function updatedRegionId($region): void
    {
        $this->reset('city_id');
        $this->region_id = $region;
        $this->cities = City::where('region_id', $region)->get();
    }


    public function updatedCityId($city): void
    {
        $this->reset('district_id');
        $this->city_id = $city;
        $this->districts = Distract::where('city_id', $city)->get();

    }

    public function updateDistrict($district_id): void
    {
        $this->district_id = $district_id;
    }
    

    public function register(RegisterDriverAction $driversRegistersAction)
    {
        $data = $this->validate();
        unset($data['terms'], $data['password_confirmation']);
        DB::beginTransaction();
        $res= $driversRegistersAction->handle($data);
        DB::commit();
        if ($res)
         {
            return redirect(RouteServiceProvider::DRIVER_FILE)->with('idRegisterDriver', $res->id);
         }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('driver::livewire.auth.register');
    }
}
