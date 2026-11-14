<?php

namespace Application\Admin\Livewire\TypeService\Modal;

use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\AppBase\Actions\Location\FetchCountriesAction;
use App\Domain\AppBase\Actions\Location\GetDistrictsAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\AppBase\Models\PackageTypeServices;
use App\Domain\AppBase\Models\TypeService;
use App\Domain\MarketPlace\Models\Package;
use App\Support\Rules\LatitudeRule;
use App\Support\Rules\LongitudeRule;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    use LivewireAlert;

    public mixed $counties = [], $regions = [], $districts = [], $cities = [];
    public $priority = 1, $country_id,$region_id, $city_id;

    protected $listeners = ['updateCountry', 'updateCity', 'updateDistrict'];
    public  $type_services;
    public  $city_type_services=[];
    public  $package_type_services=[];
    public function mount(FetchCountriesAction $fetchCountriesAction): void
    {
        $this->counties = $fetchCountriesAction->handle();
        $this->emit('renderSelect');
    }
    public function getPackageTypeService(): void
    {
        if (!$this->city_id) {
            $this->city_type_services = collect();
            $this->type_services = collect();
            $this->package_type_services = [];
            return;
        }

        $this->ensureServiceTypes();
        $this->city_type_services = \App\Domain\AppBase\Models\CityTypeServicePackages::with('package', 'type_service')
            ->where('city_id', $this->city_id)
            ->get();
        $this->type_services = PackageTypeServices::with('package', 'type_service')
            ->where('status', 'active')
            ->get();
        $this->fullPackageTypeService();
    }

    private function ensureServiceTypes(): void
    {
        DB::transaction(function (): void {
            $types = [
                1 => ['name' => 'with_delivery', 'desc' => 'مع التوصيل'],
                2 => ['name' => 'without_delivery', 'desc' => 'الاستلام من المتجر'],
            ];

            foreach ($types as $id => $attributes) {
                $typeService = TypeService::query()->firstOrNew(['id' => $id]);
                $typeService->name = $attributes['name'];
                $typeService->desc = $attributes['desc'];
                $typeService->status = 'active';
                $typeService->save();
            }

            foreach (Package::query()->where('status', 'active')->get() as $package) {
                foreach ($types as $typeId => $attributes) {
                    $packageTypeService = PackageTypeServices::query()->firstOrNew([
                        'package_id' => $package->id,
                        'type_service_id' => $typeId,
                    ]);

                    if (!$packageTypeService->exists) {
                        $packageTypeService->commission = $package->commission;
                        $packageTypeService->discount_rate = 0;
                        $packageTypeService->desc = '';
                        $packageTypeService->status = 'active';
                        $packageTypeService->save();
                    }
                }
            }
        });
    }

    public function fullPackageTypeService(): void
    {
        $cityServices = $this->city_type_services->keyBy(
            fn ($service) => $service->package_id . ':' . $service->type_service_id
        );
        $this->package_type_services = [];

        foreach ($this->type_services as $typeService) {
            $key = $typeService->package_id . ':' . $typeService->type_service_id;
            $cityService = $cityServices->get($key);
            $this->package_type_services[] = [
                'package_id' => $typeService->package_id,
                'type_service_id' => $typeService->type_service_id,
                'commission' => $cityService?->commission ?? $typeService->commission,
                'discount_rate' => $cityService?->discount_rate ?? $typeService->discount_rate,
                'city_id' => $this->city_id,
                'desc' => $cityService?->desc ?? '',
                'status' => $cityService?->status ?? 'inactive',
                'package' => [
                    'title' => $typeService->package?->getTranslations('title') ?? [],
                ],
                'type_service' => [
                    'name' => $typeService->type_service?->name,
                ],
            ];
        }
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
  

    protected function rules(): array
    {
        return [
            'package_type_services' => 'array|required',
            'package_type_services.*.package_id' => 'required|exists:packages,id',
            'package_type_services.*.type_service_id' => 'required|exists:type_services,id',
            'package_type_services.*.city_id' => 'required|exists:cities,id',
            'package_type_services.*.commission' => 'required|numeric',
            'package_type_services.*.discount_rate' => 'required|numeric',
            'package_type_services.*.status' => 'required|string|in:active,inactive',
            'package_type_services.*.desc' => 'nullable',
        ];
    }

    public function updateCountry($country): void
    {
        if($country != "") {
            $country = json_decode($country);
            $this->reset('country_id', 'regions', 'cities', 'city_id', 'region_id');
            $this->country_id = $country->id;
            $this->regions = Region::where('country_id', $country->id)->get();
            $this->emit('renderRegions', $this->regions->map(fn($region) => ['id' => $region->id, 'name' => $region->title])->values()->toArray());
            $this->emit('renderCities', []);
        }else{
            $this->regions = [];
            $this->cities = [];
            $this->districts = [];
            $this->emit('renderRegions', []);
            $this->emit('renderCities', []);
        }
    }    
    public function updatedCountryId($value): void
    {
        $this->reset('regions', 'cities', 'city_id', 'region_id');
        if($value != "") {
            $this->regions = Region::where('country_id', $value)->get();
            $this->emit('renderRegions', $this->regions->map(fn($region) => ['id' => $region->id, 'name' => $region->title])->values()->toArray());
            $this->emit('renderCities', []);
        }else{
            $this->regions = [];
            $this->cities = [];
            $this->emit('renderRegions', []);
            $this->emit('renderCities', []);
        }
    }
    public function updatedRegionId($region_id): void
    {
        $this->reset('city_id');
        $this->region_id = $region_id;
        $this->cities = City::where('region_id', $region_id)->get();
        $this->emit('renderCities', $this->cities->map(fn($city) => ['id' => $city->id, 'name' => $city->title])->values()->toArray());

    }

    public function updatedCityId($city_id): void
    {
        $this->city_id = $city_id;
        $this->getPackageTypeService();

    }


    public function updateDistrict($distract_id): void
    {
        $this->distract_id = (int)$distract_id;
    }

 
    public function store(): void
    {
        $data = $this->validate();
        $rows = collect($data['package_type_services'])
            ->map(fn (array $service) => [
                'type_service_id' => $service['type_service_id'],
                'city_id' => $service['city_id'],
                'package_id' => $service['package_id'],
                'commission' => $service['commission'],
                'discount_rate' => $service['discount_rate'],
                'desc' => $service['desc'] ?? '',
                'status' => $service['status'],
            ])
            ->all();

        DB::transaction(function () use ($rows): void {
            DB::table('city_type_service_packages')->where('city_id', $this->city_id)->delete();
            DB::table('city_type_service_packages')->insert($rows);
        });
        $this->alert('success', __('marketplace::home.data_saved_successfully'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.typeservice.modal.create');
    }
}
