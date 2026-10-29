<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Branches;

use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use Domain\MarketPlace\Models\Merchant;
use Livewire\Component;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Actions\Location\GetDistrictsAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchServiceAction;
use App\Domain\Admin\Actions\Dashboard\Package\FetchPackageAction;
use Illuminate\Support\Facades\Log;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\UpdateBranchActionInAdmin;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Track extends Component
{
    use LivewireAlert;
    public mixed $counties = [], $regions = [], $cities = [], $packages = [], $districts = [];
    public  $country_id, $region_id, $distract_id, $city_id, $package_id, $branch_id;
    public string $status = 'inactive';
    public string $status1 = 'inactive';
    public string $status2 = 'inactive';
    public array $branches = [];
    public  $branch;
    public $merchant_id;
    public $merchant_service_id;
    public  $type_services;
    public  $package_type_services = [];
    public $type_services_Package = [];
    public  $listBranches = [];

    protected $listeners = ['updateCountryBrn', "updateRegionBrn", 'updatedCityId', 'updateDistrictBrn', 'updateStatusRowValue'];
    public function mount(int $merchant_id, BranchRepository $branchRepository, FetchCountriesAction $fetchCountriesAction, FetchPackageAction $fetchPackageAction, FetchServiceAction $fetchServiceAction): void
    {
        $this->listBranches = $branchRepository
            ->all(cols: ['*'], relations: ['merchant'], condition: ['merchant_id' => $merchant_id]);
        foreach ($this->listBranches as $key => $value) {
            if (isset($value->latitude) && $value->latitude != null) {
                $this->branches[$key]['id'] = $value->id;
                $this->branches[$key]['merchant_id'] = $value->merchant_id;
                $this->branches[$key]['name'] = $value->title;
                $this->branches[$key]['position'] = ['lat' => (float)$value?->latitude, 'lng' => (float)$value?->longitude];
                $this->branches[$key]['restaurant_name'] = $value->merchant?->company_name;
                $this->branches[$key]['draggable'] = false;
            }
        }
        $this->counties = $fetchCountriesAction->handle();
        $this->cities = City::all();
        // $this->menus = $fetchServiceAction->getByMerchant($merchant_id);
        if (!empty($this->listBranches) && isset($this->listBranches[0]?->id))
        //   {  $this->branch_id=$this->listBranches[0]['id'];
        {
            $this->branch_id = $this->listBranches[0]?->id;
            $this->branch = $this->listBranches[0];
            $this->fillData($this->branch);
        }
        $this->packages = $fetchPackageAction->active();
        $this->merchant_id = $merchant_id;
    }
    public function changeBranch()
    {
        $this->branch = $this->listBranches->firstWhere('id', $this->branch_id);
        if ($this->branch) {
            $this->fillData($this->branch);
        }
    }
    protected function rules(): array
    {
        return [
            'merchant_id' => 'required|int|min:1',
            'city_id' => 'required|int|min:1',
            'package_id' => 'required',
            'package_type_services' => 'nullable|array',
            'package_type_services.*.type_service_id' => 'required',
            'package_type_services.*.package_id' => 'required',
            'package_type_services.*.commission' => 'required',
            'package_type_services.*.discount_rate' => 'required',
            'package_type_services.*.desc' => '',
            'package_type_services.*.status' => 'required',
        ];
    }


    protected function fillData($branch): void
    {
        $this->branch_id = $branch->id;
        $this->country_id = $branch->country_id ?? $branch->city?->country_id ?? 1;
        $this->package_id = $branch->package_id;
        $this->regions = Region::where('country_id', $this->country_id)->get();
        $this->region_id = $branch->region_id;
        $this->cities = City::all();
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

    public function updateRegionBrn($region): void
    {
        if ($region != "") {
            $region = json_decode($region);
            $this->reset('city_id');
            $this->region_id = $region->id;
            $this->cities = City::where('region_id', $region->id)->get();
        } else {

            $this->cities = [];
            $this->districts = [];
        }
    }
    public function updateCountryBrn($country): void
    {
        if ($country != "") {
            $country = json_decode($country);
            $this->reset('country_id', 'regions');
            $this->country_id = $country->id;
            $this->regions = Region::where('country_id', $country->id)->get();
        } else {
            $this->regions = [];
            $this->cities = [];
            $this->districts = [];
        }
    }
    public function updateStatusRow($indx): void
    {
        $indx = (int) $indx;
        if (!isset($this->package_type_services[$indx])) {
            return;
        }

        $newStatus = ($this->package_type_services[$indx]['status'] ?? 'inactive') === 'active'
            ? 'inactive'
            : 'active';
        $this->package_type_services[$indx]['status'] = $newStatus;

        $typeServiceId = $this->package_type_services[$indx]['type_service_id'] ?? null;
        if ($typeServiceId == 1) {
            $this->status1 = $newStatus;
        } elseif ($typeServiceId == 2) {
            $this->status2 = $newStatus;
        }
    }
    public function updateStatusRowValue($indx, $x): void
    {
        if ($indx == 1)
            $this->status1 = $x;
        else
            $this->status2 = $x;
        $type_servicesNew = [];
        foreach ($this->package_type_services as $merchantBranchTypeService) {
            if ($merchantBranchTypeService['type_service_id'] == 1) {
                $merchantBranchTypeService['status'] = $this->status1;
            }
            if ($merchantBranchTypeService['type_service_id'] == 2) {
                $merchantBranchTypeService['status'] = $this->status2;
            }
            $type_servicesNew[] = $merchantBranchTypeService;
        }
        $this->package_type_services = $type_servicesNew;
    }
    public function updatedRegionId($region_id): void
    {
        $this->reset('city_id');
        $this->region_id = $region_id;
        $this->cities = City::where('region_id', $region_id)->get();
    }

    public function updatedCityId($city_id): void
    {

        $this->reset('distract_id');
        $city = City::find($city_id);
        if (!$city) {
            $this->cities = City::all();
            return;
        }
        $this->region_id = $city->region_id;
        $this->city_id = $city_id;
        $this->districts = Distract::where('city_id', $city_id)->get();
        // $this->type_services_Package =[];
        $this->fullPackageTypeService();
    }

    public function updatedPackageId()
    {

        // $this->type_services_Package =[];
        $this->fullPackageTypeService();
    }
    public function updatedPackage($package): void
    {
        $this->package_id = (int)$package;
        // $this->type_services_Package =[];
        $this->fullPackageTypeService();
    }
    public function fullPackageTypeService(): void
    {
        // $this->type_services_Package =\App\Domain\AppBase\Models\MerchantBranchTypeServices::with('type_service')->where(['merchant_branch_id' => $this->branch_id])
        // // ->where(['package_id'=>$this->package_id])
        // ->get();
        $this->type_services = \App\Domain\AppBase\Models\CityTypeServicePackages::with('type_service')
            // ->where(['status' =>  'active'])
            ->where(['city_id' => $this->city_id])->where(['package_id' => $this->package_id])->get();
        $type_servicesNew = [];
        // $this->package_type_services=$this->type_services_Package;

        // $this->package_type_services = [];
        foreach ($this->type_services_Package as $merchantBranchTypeService) {
            $y = [
                "type_service_id" => $merchantBranchTypeService->type_service_id,
                // "package_id"=>$merchantBranchTypeService->package_id,
                "package_id" => $this->package_id,
                "merchant_branch_id" => $this->branch_id,
                'commission' => $merchantBranchTypeService->commission,
                'discount_rate' => $merchantBranchTypeService->discount_rate,
                "type_service" => $merchantBranchTypeService->type_service,
                "desc" => '',
                "status" => $merchantBranchTypeService->status
            ];
            $x =
                \Illuminate\Support\Arr::first($this->type_services, fn($model) => $model->type_service_id == $merchantBranchTypeService->type_service_id, null);
            if ($x)
                $y = [
                    "type_service_id" => $x->type_service_id,
                    // "package_id"=>$x->package_id,
                    "package_id" => $this->package_id,
                    "merchant_branch_id" => $this->branch_id,
                    'commission' => $merchantBranchTypeService->commission,
                    'discount_rate' => $x->discount_rate,
                    "type_service" => $x->type_service,
                    "desc" => '',
                    "status" => $merchantBranchTypeService->status
                ];
            $type_servicesNew[] = $y;
        }
        foreach ($this->type_services as $type_servicesL) {
            $y = [
                "type_service_id" => $type_servicesL->type_service_id,
                "package_id" => $this->package_id,
                "merchant_branch_id" => $this->branch_id,
                'commission' => $type_servicesL->commission,
                'discount_rate' => $type_servicesL->discount_rate,
                "type_service" => $type_servicesL->type_service,
                "desc" => '',
                "status" => 'inactive'
            ];
            $x =
                \Illuminate\Support\Arr::first($this->type_services_Package, fn($model) => $model->type_service_id == $type_servicesL->type_service_id, null);
            if ($x)
                continue;
            $type_servicesNew[] = $y;
        }
        // $this->package_type_services = collect($this->type_services_Package)->merge($type_servicesNew)
        // ->unique('type_service_id')
        // ->values() 
        // ->toArray();
        // $this->package_type_services = $this->type_services_Package->union($type_servicesNew)
        $this->package_type_services = $type_servicesNew;
        // ->toArray()
        // ->unique('type_service_id')
        // ->toArray();

    }
    public function updateBranchData(UpdateBranchActionInAdmin $updateBranchAction): void
    {
        if (!$this->branch) {
            $this->alert('error', __('admin::home.No branches available'));
            return;
        }

        $data = $this->validate();
        DB::beginTransaction();
        DB::table('merchant_branch_type_services')->where(['merchant_branch_id' => $this->branch_id])->delete();
        // if(set($data, 'merchant_id'))
        // if(isset($data['merchant_id']))
        // unset($data['merchant_id']);
        if ($this->branch->merchant_id)
            $data['merchant_id'] = $this->branch->merchant_id;
        $branch = $updateBranchAction->handle($this->branch_id, $data);
        $this->fillData($branch);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();
    }
    public function updateDistrictBrn($distract_id): void
    {
        $this->distract_id = (int)$distract_id;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.branches.track');
    }
}
