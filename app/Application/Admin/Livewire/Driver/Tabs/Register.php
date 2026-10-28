<?php

namespace Application\Admin\Livewire\Driver\Tabs;


use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Domain\AppBase\Actions\Car\FetchCarTypesAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\Admin\Actions\Dashboard\Driver\FetchSingleDriverRegisterAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use Illuminate\Support\Facades\DB;
use App\Domain\Provider\Actions\Dashboard\Auth\RegisterDriverAction;
use App\Providers\RouteServiceProvider;
use App\Domain\Admin\Actions\Dashboard\Driver\StoreDriverAction;
use Illuminate\Support\Facades\Log;
use App\Domain\Admin\Actions\Dashboard\Company\FetchCompanyAction;

class Register extends Component
{
    use LivewireAlert;


    public $idReg, $name, $email, $password, $phone, $password_confirmation, $status = 'inactive';
    public $Id_expire_date = null, $Id_number = null, $iban = null;
    public  $country_id = 1,$region_id, $city_id, $district_id;
    public mixed $counties = [],$regions=[], $districts = [], $cities = [] ,$cars_types =[];
    public $IdentityTypeId,$dateOfBirth,$car_type_id,$carNumber,$vehicleSequenceNumber;
    public $company_id = null;
    public $companies;

    protected $listeners = ['updateCountry','updateCity', 'updateDistrict'];
    protected RegisterDriverAction $driversRegistersAction;
    protected StoreDriverAction $storeDriverAction;

    public function mount(int $id,FetchSingleDriverRegisterAction $fetchSingleDriverAction ,FetchCountriesAction $fetchCountriesAction ,FetchCarTypesAction $fetchCarTypesAction,FetchCompanyAction $fetchCompanyAction)
    {
        $this->driver = $fetchSingleDriverAction->handle($id);
        if ($this->driver) {
            $this->idReg=$this->driver->id;
            $this->name = $this->driver->name;
            $this->email = $this->driver->email;
            $this->status = 'active';
            $this->phone = $this->driver->phone;
            $this->password = $this->driver->password;
            // $this->taken_commission = $this->driver->taken_commission;
            // $this->company_id = 1;
            $this->country_id = 1;
            $this->country_name = $this->driver->country_name;
            $this->countries = $fetchCountriesAction->handle();
            $this->regions = Region::where('id', $this->driver->region_id)->first();
            $this->region = $this->regions?->title;
            $this->cities = City::where('id', $this->driver->city_id)->first();
            $this->city = $this->cities?->title;
            $this->cars_types = $fetchCarTypesAction->handle(activeOnly: true);
            $this->city_id = $this->driver?->city_id;
            $this->region_id = $this->driver?->region_id;
            $this->IdentityTypeId = $this->driver?->IdentityTypeId;
            $this->dateOfBirth = $this->driver?->dateOfBirth ? \Carbon\Carbon::parse($this->driver->dateOfBirth)->format('Y-m-d') : null;
            $this->car_type_id = $this->driver?->car_type_id;
            $this->carNumber = $this->driver?->carNumber;
            $this->vehicleSequenceNumber = $this->driver?->vehicleSequenceNumber;
            $this->districts = Distract::where('city_id', $this->driver->city_id)->get();
            $this->district_id = $this->driver?->district_id;
            $this->Id_expire_date = $this->driver?->Id_expire_date;
            $this->Id_number = $this->driver?->Id_number;
            $this->iban = $this->driver?->iban;
            $this->emit('renderSelect');

        }else{
            abort(404);
        }
        $this->companies = $fetchCompanyAction->handle();
    }
    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|string|min:2|max:200|unique:admins,email',
            'phone' => 'required|string|min:2|max:200|unique:drivers,phone',
            'password' => 'required|string|min:6',
            'company_id' => 'required',
            'status' => 'required|string|in:active,inactive',
            'country_id' => 'nullable',
            'city_id' => 'nullable',
            'district_id' => 'nullable',
            'IdentityTypeId' => 'nullable',
            'region_id' => 'nullable',
            'dateOfBirth' => 'nullable',
            'car_type_id' => 'nullable',
            'carNumber' => 'nullable',
            'vehicleSequenceNumber' => 'nullable',
            'Id_expire_date' => 'nullable',
            'Id_number' => 'nullable',
            'iban' => 'nullable',
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
    

    public function store(StoreDriverAction $storeDriverAction)
    {
        Log::debug("on fir");

        $data = $this->validate();
        // A driver approved from a registration request becomes a completed (approved)
        // driver, same as drivers created via Modal\Create which sets is_complete = 1.
        // Without this, approved registers fall back into the pending (is_complete = 0) list.
        $data['is_complete'] = 1;
        Log::debug($data);
        DB::beginTransaction();
        try {
        $res=$storeDriverAction->handle($data);
        $typeDoc='Domain\Driver\Models\Driver';
        $dataToInsert = DB::table('driver_register_files')
    ->select('file_name', 'original_name', 'mime_type', 'collection_name', 'type',
     'storage', 'url', 'path', 'order_column', 'size','fileable_type','fileable_id')
    ->where('fileable_id', $this->idReg)
    ->where('fileable_type', 'App\Domain\Provider\Models\DriversRegisters')
    ->get();

if ($dataToInsert->isNotEmpty()) {
      $insertData = $dataToInsert->map(function($item) {
        return (array) $item; 
    })->toArray();
    DB::table('driver_files')->insert($insertData);
}

        DB::table('driver_files')
        ->where('fileable_id',$this->idReg)
        ->where('fileable_type','App\Domain\Provider\Models\DriversRegisters')
        ->update(['fileable_id'=>$res->id,'fileable_type'=>$typeDoc]);
        DB::table('driver_register_files')->where('fileable_id',$this->idReg)->delete();
        DB::table('drivers_registers')->where('id',$this->idReg)->delete();
        $this->emit('refreshDatatable');
        $this->alert('success', 'Driver Created Successfully');
        DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Approve driver register failed: '.$e->getMessage(), ['register_id' => $this->idReg]);
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }
        return redirect(RouteServiceProvider::ADMIN_DRIVERS_REGISTER);
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.tabs.register');
    }
}
