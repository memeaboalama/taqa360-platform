<?php

namespace Application\Marketplace\Livewire\Branch\Modal;

use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Actions\Location\GetDistrictsAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\StoreBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchServiceAction;
use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Domain\Admin\Actions\Dashboard\Package\FetchPackageAction;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    use LivewireAlert;

    public mixed $counties = [], $regions = [], $districts = [], $cities = [], $packages = [],$employees=[];
    public $priority = 1, $country_id,$region_id, $city_id, $distract_id, $manager_id,$package_id;
    public string $status = 'active', $type = 'fixed';
    public array $title = ['en' => null, 'ar' => null];
    public $latitude = '', $longitude = '';

    protected $listeners = ['updateCountry','updateRegion', 'updateCity', 'updateDistrict', 'set:latitude-longitude' => 'setLatitudeLongitude'];
    protected GetDistrictsAction $getDistrictsAction;
    public $merchant_service_id;
    public $menus, $address = "";
    public $merchantId;
    public $cover_image;
    public  $type_services;
    public  $package_type_services=[];
    public function mount(FetchCountriesAction $fetchCountriesAction,FetchPackageAction $fetchPackageAction, FetchEmployessAction $fetchEmployessAction, FetchServiceAction $fetchServiceAction): void
    {
        $this->merchantId = resolve('_InitialMerchant');
        $this->counties = $fetchCountriesAction->handle();
        $this->employees = $fetchEmployessAction->handle();
        $this->menus = $fetchServiceAction->getByMerchant($this->merchantId);
        $this->packages = $fetchPackageAction->active();
       
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
    public function setLatitudeLongitude($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    protected function rules(): array
    {
        return [
            'manager_id' => 'nullable|exists:merchant_employees,id',
            'merchant_service_id' => 'nullable|exists:merchant_services,id',
            'title' => 'required|array',
            'title.en' => 'required|string|min:2|max:120',
            'title.ar' => 'required|string|min:2|max:120',
            'priority' => 'required|int|min:1',
            'type' => 'nullable|string|in:fixed,mobile',
            'country_id' => 'required|int|min:1',
            'region_id' => 'required|int|min:1',
            'city_id' => 'required|int|min:1',
            'distract_id' => 'nullable',
            'address' => 'required',
            'package_id' => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'latitude' => ['required'],
            'longitude' => ['required'],
            'package_type_services' => 'array|required',
            'package_type_services.*.type_service_id' => 'required',
            'package_type_services.*.package_id' => 'required|numeric',
            'package_type_services.*.commission' => 'required|numeric',
            'package_type_services.*.discount_rate' => 'required|numeric',
            'package_type_services.*.desc' => '',
            'package_type_services.*.status' => 'required',
        ];
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
    public function updateRegion($region): void
    {
        if($region && is_numeric($region)) {
            // $country = json_decode($country);
            $this->reset('region_id', 'cities');
            $this->region_id = (int)$region;
            $this->cities = City::where('region_id', $region)->get();
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
    }

    public function updatedCityId($city_id): void
    {

        $this->reset('distract_id');
        $this->city_id = $city_id;
        $this->districts = Distract::where('city_id', $city_id)->get();
        $this->fullPackageTypeService();

    }
    public function fullPackageTypeService(): void
    {
        $this->package_type_services = [];
        $type_services = \App\Domain\AppBase\Models\CityTypeServicePackages::
            with('type_service')->where(['status' =>  'active'])
            ->where(['city_id'=>$this->city_id]);
        if ($this->package_id) {
            $type_services = $type_services->where(['package_id'=>$this->package_id]);
        }
        $type_services = $type_services->get();
        if ($type_services->isEmpty()) {
            $type_services = \App\Domain\AppBase\Models\PackageTypeServices::
                with('package','type_service')->where(['status' =>  'active'])->get();
        }
        if ($type_services->isEmpty()) {
            $type_services = \App\Domain\AppBase\Models\TypeService::where(['status' =>  'active'])->get();
        }
        foreach($type_services as $type_servicesL)
        {            
            $this->package_type_services[]=[
                "type_service_id"=>$type_servicesL->type_service_id ?? $type_servicesL->id,
                "package_id"=>$this->package_id ?? $type_servicesL->package_id,
                "commission"=>$type_servicesL->commission ?? 0,
                "discount_rate"=>$type_servicesL->discount_rate ?? 0,
                "type_service"=>$type_servicesL->type_service ?? $type_servicesL,
                "desc"=>'',
                "status"=>'inactive'
            ];
        }
    }

    public function updateDistrict($distract_id): void
    {
        $this->distract_id = (int)$distract_id;
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('marketplace::home.Branch Name').' (AR)',
            'title.en' => trans('marketplace::home.Branch Name').' (EN)',
            'merchant_service_id' => trans('marketplace::home.Menu Name'),
            'country_id' => trans('marketplace::home.Country'),
            'city_id' => trans('marketplace::home.City'),
            'distract_id' => trans('marketplace::home.District'),
            'cover_image' => trans('marketplace::home.cover_image'),
        ];
    }

    public function store(StoreBranchAction $storeBranchAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::beginTransaction();
        try {
            $storeBranchAction->handle($data);
            DB::commit();
            $this->emit('refreshDatatable');
            $this->emit('closeModal', '#createBranch');
            $this->alert('success', __('marketplace::home.data_saved_successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Log::error('Create branch failed: ' . $e->getMessage());
            $this->alert('error', __('marketplace::home.please_fix_the_errors'));
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->reset('title', 'priority', 'type', 'region_id','city_id', 'distract_id', 'country_id', 'manager_id', 'merchant_service_id', 'cover_image');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.branch.modal.create');
    }
}
