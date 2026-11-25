<?php

namespace Application\Marketplace\Livewire\Order\Table;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\Admin\Actions\Dashboard\City\FetchSingleCityAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchSingleDistrictAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchSingleEmployeeAction;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Support\Enum\StatusEnum;

class Filter extends Component
{
    public $code = null;
    public $status = null;
    public $date = null;
    public $to_date = null;
    public bool $to_date_visible = false;
    public $date_condition = null;
    public $branch_name = null;
    public $merchant_company_name = null;
    public $employee_name = null;
    public $city_name = null;
    public $district_name = null;
    public $branch_id = null;
    public $employee_id = null;
    public $merchant_id = null;
    public $city_id = null;
    public string $citySearch = '';
    public int $cityPage = 1;
    public bool $hasMoreCities = false;
    public $district_id = null;
    public string $districtSearch = '';
    public int $districtPage = 1;
    public bool $hasMoreDistricts = false;
    public Collection $statuses;
    public $branches, $employees, $districts, $merchants;
    public array $cities = [];
    public array $filters = [];

    protected $listeners = [
        'removeCodeFilter',
        'removeStatusFilter',
        'removeBranchFilter',
        'removeEmployeeFilter',
        'removeDateFilter',
        'removeCityFilter',
        'removeDistrictFilter',
        'getBetweenDates'];

    public function mount(
        FetchCitiesAction $fetchCitiesAction, FetchBranchAction $fetchBranchAction)
    {
        $this->date_condition = "equal";
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->branches = $fetchBranchAction->handle(resolve('_InitialMerchant'));
        $this->employees = collect();
        $this->cities = [];
        $this->loadCityPage($fetchCitiesAction);
        $this->districts = [];
    }

    private function loadCityPage(FetchCitiesAction $fetchCitiesAction, bool $append = false): void
    {
        $citiesPage = $fetchCitiesAction->handle([
            'paginated' => true,
            'per_page' => 25,
            'page' => $this->cityPage,
            'page_name' => 'city_filter_page',
            'search' => $this->citySearch,
        ]);

        $cities = collect($citiesPage->items())
            ->map(static fn ($city) => [
                'id' => $city->id,
                'title' => $city->title,
            ])
            ->all();
        $this->hasMoreCities = $citiesPage->hasMorePages();
        $this->cities = $append
            ? array_merge($this->cities, $cities)
            : $cities;
    }

    public function updatedCitySearch(): void
    {
        $this->cityPage = 1;
        $this->loadCityPage(resolve(FetchCitiesAction::class));
    }

    public function loadMoreCities(): void
    {
        if (!$this->hasMoreCities) {
            return;
        }

        $this->cityPage++;
        $this->loadCityPage(resolve(FetchCitiesAction::class), true);
    }

    private function loadDistrictPage(bool $append = false): void
    {
        if (!$this->city_id) {
            $this->districts = [];
            $this->hasMoreDistricts = false;
            return;
        }

        $query = Distract::query()
            ->where('city_id', $this->city_id)
            ->where('status', StatusEnum::ACTIVE);

        $search = trim($this->districtSearch);
        if ($search !== '') {
            $locales = array_unique([app()->getLocale(), 'ar', 'en']);
            $query->where(function ($query) use ($locales, $search) {
                foreach ($locales as $locale) {
                    $query->orWhere('title->' . $locale, 'like', '%' . $search . '%');
                }
            });
        }

        $districtsPage = $query->paginate(25, ['id', 'title'], 'district_filter_page', $this->districtPage);
        $districts = collect($districtsPage->items())
            ->map(static fn (Distract $district) => [
                'id' => $district->id,
                'title' => $district->title,
            ])
            ->all();

        $this->hasMoreDistricts = $districtsPage->hasMorePages();
        $this->districts = $append
            ? array_merge($this->districts, $districts)
            : $districts;
    }

    public function updatedDistrictSearch(): void
    {
        $this->districtPage = 1;
        $this->loadDistrictPage();
    }

    public function loadMoreDistricts(): void
    {
        if (!$this->hasMoreDistricts) {
            return;
        }

        $this->districtPage++;
        $this->loadDistrictPage(true);
    }

    public function selectCity($cityId): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->city_id = $cityId ?: null;
        $this->district_id = null;
        $this->district_name = null;
        $this->districtSearch = '';
        $this->districtPage = 1;
        $this->filters['district_id'] = null;
        $this->loadDistrictPage();
    }

    public function resetSearch(): void
    {
        $this->emit('resetSearch');
    }


    public function filterStatus(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->filters['status'] =  $this->status;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->filters['status'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterCode(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->filters['code'] =  $this->code;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeCodeFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->code = null;
        $this->filters['code'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterBranch(FetchSingleBranchAction $fetchSingleBranchAction): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        if ($this->branch_id == "")
        {
            $this->branch_id = null;
        }

        $this->branch_name = $fetchSingleBranchAction
            ->handle($this->branch_id)
            ->title ?? null;
        $this->filters['merchant_branch_id'] =  $this->branch_id;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeBranchFilter(): void
    {
        $this->branch_id = null;
        $this->branch_name = null;
        $this->filters['merchant_branch_id'] = null;
        $this->emit('refreshOrders', $this->filters);
    }


    public function filterCity(FetchSingleCityAction $fetchSingleCityAction): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        if ($this->city_id == "")
        {
            $this->city_id = null;
        }

        $this->city_name = $fetchSingleCityAction
            ->handle($this->city_id)
            ?->title ?? null;

        $this->filters['city_id'] =  $this->city_id;
        $this->emit('refreshOrders', $this->filters);
        $this->dispatchBrowserEvent('close-city-filter');
    }

    public function removeCityFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->city_id = null;
        $this->city_name = null;
        $this->citySearch = '';
        $this->cityPage = 1;
        $this->loadCityPage(resolve(FetchCitiesAction::class));
        $this->district_id = null;
        $this->district_name = null;
        $this->districtSearch = '';
        $this->districtPage = 1;
        $this->districts = [];
        $this->hasMoreDistricts = false;
        $this->filters['city_id'] = null;
        $this->filters['district_id'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterDistrict(FetchSingleDistrictAction $fetchSingleDistrictAction): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        if ($this->district_id == "")
        {
            $this->district_id = null;
        }

        $this->district_name = $fetchSingleDistrictAction
            ->handle($this->district_id)
            ?->title ?? null;

        $this->filters['city_id'] = $this->city_id;
        $this->filters['district_id'] =  $this->district_id;
        $this->emit('refreshOrders', $this->filters);
        $this->dispatchBrowserEvent('close-district-filter');
    }

    public function removeDistrictFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->district_id = null;
        $this->district_name = null;
        $this->districtSearch = '';
        $this->districtPage = 1;
        $this->loadDistrictPage();
        $this->filters['district_id'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterDate(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeDateFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->date_condition = "equal";
        $this->date = null;
        $this->to_date = null;
        $this->filters['date_condition'] = null;
        $this->filters['date'] = null;
        $this->filters['to_date'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function getBetweenDates(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        if ($this->date_condition == "between")
        {
            $this->to_date_visible = true;
        }else{
            $this->to_date_visible = false;
        }
    }

    public function render()
    {
        return view('marketplace::livewire.order.table.filter');
    }
}
