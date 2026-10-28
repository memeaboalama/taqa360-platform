<?php

namespace Application\Marketplace\Livewire\Branch\Tabs;

use App\Domain\Admin\Actions\Dashboard\Package\FetchPackageAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Actions\Location\GetDistrictsAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\MarketPlace\Models\Package;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\UpdateBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchServiceAction;
use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class Info extends Component
{
    use LivewireAlert, WithFileUploads;

    public mixed $counties = [],$regions = [], $districts = [], $cities = [], $packages = [],$employees=[];
    public $priority = 1, $country_id = 1,$region_id =1 , $city_id = 1, $distract_id = null, $manager_id,$indexSelect;
    public string $status = 'active', $type = 'fixed';
    public string $status1 = 'inactive';
    public string $status2 = 'inactive';
    public array $title = ['en' => null], $description = ['en' => null], $address_name = ['en' => null];

    // protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict', 'set:latitude-longitude' => 'setLatitudeLongitude'];
    // protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict', 'updatedCityId', 'updateDistrictBrn', 'updateStatusRowValue', 'set:latitude-longitude' => 'setLatitudeLongitude'];
    // protected $listeners = ['updateCountry', 'updateRegion','updateCity', 'updateDistrict', 'updateStatusRowValue', 'set:latitude-longitude' => 'setLatitudeLongitude'];
    protected $listeners = ['updateCountry', 'updateRegion','updateCity', 'updateDistrict', 'updateStatusRow', 'set:latitude-longitude' => 'setLatitudeLongitude', 'enableEditingInfo' => 'enableEditing'];
    protected GetDistrictsAction $getDistrictsAction;
    public $accept_multi_requests_at_same_time = false, $auto_accept = false, $has_scheduled_services = false;
    public $branch_id;
    public $merchant_id;
    public $package_id;
    public $package;
    public $merchant_service_id = null;
    public $menus, $cover_image, $cover_image_path, $branch;
    public $latitude = "", $longitude = "", $address = "";
    public  $type_services;
    public  $package_type_services = [];
    public $type_services_Package = [];

    public bool $editing = false;

    public function enableEditing(): void
    {
        $this->editing = true;
    }
    public function setLatitudeLongitude($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

    }


    public function mount($branch_id, FetchCountriesAction $fetchCountriesAction, FetchEmployessAction $fetchEmployessAction, FetchSingleBranchAction $fetchBranchAction,FetchPackageAction $fetchPackageAction, FetchServiceAction $fetchServiceAction): void
    {
        $this->branch_id=(int)$branch_id;
        $this->merchant_id=(int)resolve('_InitialMerchant');
        $branch = $fetchBranchAction->handle($branch_id, $this->merchant_id);
        if ($branch) {
            $this->package_id=$branch->package_id;
            $this->branch = $branch;
            $this->fillData($branch);
            $this->menus = $fetchServiceAction->getByMerchant( resolve('_InitialMerchant'));
            $this->counties = $fetchCountriesAction->handle();
            $this->employees = $fetchEmployessAction->handle();
        } else {
            abort(404);
        }
        $this->packages = $fetchPackageAction->active();
        $this->emit('renderSelect');
    }
    
    public function fullPackageTypeService(): void
    {
        
        $this->type_services = \App\Domain\AppBase\Models\CityTypeServicePackages::with('type_service')
            ->where(['status' =>  'active'])
            ->where(['city_id' => $this->city_id]);
        if ($this->package_id) {
            $this->type_services = $this->type_services->where(['package_id' => $this->package_id]);
        }
        $this->type_services = $this->type_services->get();
        if ($this->type_services->isEmpty()) {
            $this->type_services = \App\Domain\AppBase\Models\TypeService::where(['status' =>  'active'])->get();
        }
        $type_servicesNew = [];

        
        foreach ($this->type_services as $type_servicesL) {
            $y = [
                "type_service_id" => $type_servicesL->type_service_id ?? $type_servicesL->id,
                "package_id" => $this->package_id,
                "merchant_branch_id" => $this->branch_id,
                'commission' => $type_servicesL->commission ?? 0,
                'discount_rate' => $type_servicesL->discount_rate ?? 0,
                "type_service" => $type_servicesL->type_service ?? $type_servicesL,
                "desc" => '',
                "status" => 'inactive',
                "active" => false
            ];
            $x =
                \Illuminate\Support\Arr::first($this->type_services_Package, fn($model) => $model->type_service_id == $y['type_service_id'], null);
            if ($x)
                {
                    $y['commission']=$x->commission;
                    $y['discount_rate']=$x->discount_rate;
                $y['status']=$x->status;
                $y['active']=($x->status == "active");
            }
            $type_servicesNew[] = $y;
        }
        $this->package_type_services = $type_servicesNew;
        
    }
    public function updateStatusRow($indx): void
    {
        if( $this->package_type_services[$indx]['status']== "active" )
       {
        $this->package_type_services[$indx]['status']="inactive";
       } else
       {
           $this->package_type_services[$indx]['status']="active";
       }
    }
    public function nickNames()
    {
        return [
            'title.ar' => trans('marketplace::home.Title'),
            'title.en' => trans('marketplace::home.Title'),
            'merchant_service_id' => trans('marketplace::home.Menu Name'),
            'manager_id' => trans('marketplace::home.Branch Manager'),
            'cover_image' => trans('marketplace::home.cover_image'),
            'country_id' => trans('marketplace::home.Country'),
            'city_id' => trans('marketplace::home.City'),
            'distract_id' => trans('marketplace::home.District'),
        ];
    }

    protected function rules(): array
    {
        return [
            'manager_id' => 'nullable|exists:merchant_employees,id',
            'title' => 'required|array',
            'merchant_service_id' => 'nullable',
            'title.*' => 'required|string|min:2|max:120',
            'priority' => 'required|int|min:1',
            'type' => 'required|string|in:fixed,mobile',
            'country_id' => 'required|int|min:1',
            'region_id' => 'required|int|min:1',
            'city_id' => 'required|int|min:1',
            'distract_id' => 'nullable',
            'accept_multi_requests_at_same_time' => 'required|boolean',
            'auto_accept' => 'required|boolean',
            'has_scheduled_services' => 'required|boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'latitude'     =>  ['required'],
            'longitude'     =>  ['required'],
            'package_type_services' => 'array|required',
            'package_type_services.*.type_service_id' => 'required',
            'package_type_services.*.package_id' => 'required|numeric',
            'package_type_services.*.commission' => 'required|numeric',
            'package_type_services.*.discount_rate' => 'required|numeric',
            'package_type_services.*.desc' => '',
            'package_type_services.*.status' => 'required',
        ];
    }

    // public function updateCountry($country): void
    // {
    //     if($country != "") {
    //         $country = json_decode($country);
    //         $this->reset('country_id', 'regions');
    //         $this->country_id = $country->id;
    //         $this->regions = Region::where('country_id', $country->id)->get();
    //     }else{
    //         $this->regions = [];
    //         $this->cities = [];
    //         $this->districts = [];
    //     }
    // }    
    public function updateCountry($countryId): void
    {
        // if($countryId) {
            if($countryId && is_numeric($countryId)) {
            // $country = json_decode($country);
            $this->reset('country_id', 'regions');
            $this->country_id = $countryId;
            $this->regions = Region::where('country_id', $countryId)->get();
            // $this->emit('renderRegions', $this->regions);
        }else{
            $this->regions = [];
            $this->cities = [];
            $this->districts = [];
        }
    }    
    public function updateRegion($region): void
    {
        if($region && is_numeric($region)) {
            // $country = json_decode($country);
            $this->reset('region_id', 'cities');
            $this->region_id = (int)$region;
            $this->cities = City::where('region_id', $region)->get();
            // $this->emit('renderCities', $this->cities);
        }else{
            // $this->regions = [];
            $this->cities = [];
            $this->districts = [];
        }
    }    
    public function updatedRegionId($region_id): void
    {
        
        $this->reset('city_id','cities');
        $this->region_id = $region_id;
        $this->cities = City::where('region_id', $region_id)->get();
        // $this->emit('renderCities', $this->cities);
    }

    public function updatedCityId($city_id): void
    {

        $this->reset('distract_id');
        $this->city_id = $city_id;
        $this->districts = Distract::where('city_id', $city_id)->get();
        $this->fullPackageTypeService();

    }
    public function updateCity($city_id): void
    {

        $this->reset('distract_id');
        $this->city_id = $city_id;
        $this->districts = Distract::where('city_id', $city_id)->get();
        $this->fullPackageTypeService();
        $data = $this->validate(attributes: $this->nickNames());
        

    }

    public function updateAccept($val): void
    {
        $this->accept_multi_requests_at_same_time = $val ? 1 : 0;
    }

    public function updateAutoAccept($val): void
    {
        $this->auto_accept = $val ? 1 : 0;
    }

    public function UpdateHasScheduledServices($val): void
    {
        $this->has_scheduled_services = $val ? 1 : 0;
    }

    protected function fillData($branch)
    {
        $this->branch_id = $branch->id;
        $this->merchant_id = $branch->merchant_id;
        $this->city_id = $branch->city_id;
        $city = City::find($this->city_id);
        $this->country_id = $city?->country_id ?? 1;
        $this->regions = Region::where('country_id', $this->country_id)->get();
        $this->region_id = $branch->region_id ?: $city?->region_id;
        $this->cities = City::where('region_id', $this->region_id)->get();
        if($this->city_id)
    {

        $this->region_id =
                \Illuminate\Support\Arr::first($this->cities, fn($model) => $model->id == $this->city_id, null)?->region_id ?? $this->region_id;
        $this->cities = City::where('region_id', $this->region_id)->get();
    }
        $this->manager_id = $branch->manager_id;
        $this->districts = Distract::where('city_id', $branch->city_id)->get();
        $this->distract_id = $branch->distract_id;
        $this->title = $branch->getTranslations( 'title');
        
        $this->auto_accept = $branch->auto_accept;
        $this->type = $branch->type;
        $this->merchant_service_id = $branch->merchant_service_id;
        $this->accept_multi_requests_at_same_time = $branch->accept_multi_requests_at_same_time;
        $this->has_scheduled_services = $branch->has_scheduled_services;
        $this->latitude = $branch?->latitude;
        $this->longitude = $branch?->longitude;
        $this->cover_image_path = $branch->cover;
        // $this->type_services_Package = \App\Domain\AppBase\Models\MerchantBranchTypeServices::with('type_service')->where(['merchant_branch_id' => $branch->id])->get();
        $this->package = Package::find($branch?->package_id);
        
        $this->city_id = $branch->city_id;
        $this->type_services_Package = [];
        $this->type_services_Package = \App\Domain\AppBase\Models\MerchantBranchTypeServices::with('type_service')->where(['merchant_branch_id' => $this->branch_id])->get();
        foreach ($this->type_services_Package as $merchantBranchTypeService) {
            if ($merchantBranchTypeService->type_service_id == 1)
                $this->status1 = $merchantBranchTypeService->status;
            else
                $this->status2 = $merchantBranchTypeService->status;
        }
        $this->fullPackageTypeService();
    }

    public function store(UpdateBranchAction $updateBranchAction)//: void
    {
        foreach ($this->package_type_services as $index => $service) {
            $this->package_type_services[$index]['status'] = !empty($service['active']) ? "active" : "inactive";
        }
        $data = $this->validate(attributes: $this->nickNames());
       if($this->branch->manager_id != null && $data['manager_id'] == "")
       {
           $this->addError('manager_id', __('marketplace::messages.MANAGER_REQUIRED'));
           return;
       }
        DB::beginTransaction();
        DB::table('merchant_branch_type_services')->where(['merchant_branch_id' => $this->branch_id])->delete();
        $branch = $updateBranchAction->handle($this->branch_id, $data);
        $this->fillData($branch);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.branch.tabs.info');
    }
}
