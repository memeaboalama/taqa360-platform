<?php

namespace Application\Driver\Livewire\Home;

use App\Application\Driver\Enums\DriverOrderStatuses;
use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Support\Enum\StatusEnum;
use Livewire\Component;
use Livewire\WithPagination;

class AssignedOrders extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public $code = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $cityId = null;
    public $cityName = null;
    public string $citySearch = '';
    public int $cityPage = 1;
    public bool $hasMoreCities = false;
    public $districtId = null;
    public $districtName = null;
    public string $districtSearch = '';
    public int $districtPage = 1;
    public bool $hasMoreDistricts = false;
    public array $cities = [];
    public array $districts = [];
    public array $appliedFilters = [];

    protected $paginationTheme = 'bootstrap';

    public function mount(FetchCitiesAction $fetchCitiesAction): void
    {
        $this->perPage = (int) request('perPage', 10);
        if (!in_array($this->perPage, [10, 25, 50, 100], true)) {
            $this->perPage = 10;
        }

        $this->loadCityPage($fetchCitiesAction);
    }

    private function loadCityPage(FetchCitiesAction $fetchCitiesAction, bool $append = false): void
    {
        $citiesPage = $fetchCitiesAction->handle([
            'paginated' => true,
            'per_page' => 25,
            'page' => $this->cityPage,
            'page_name' => 'driver_home_city_filter_page',
            'search' => $this->citySearch,
        ]);

        $cities = collect($citiesPage->items())
            ->map(static fn ($city) => [
                'id' => $city->id,
                'title' => $city->title,
            ])
            ->all();

        $this->hasMoreCities = $citiesPage->hasMorePages();
        $this->cities = $append ? array_merge($this->cities, $cities) : $cities;
    }

    private function loadDistrictPage(bool $append = false): void
    {
        if (!$this->cityId) {
            $this->districts = [];
            $this->hasMoreDistricts = false;
            return;
        }

        $query = Distract::query()
            ->where('city_id', $this->cityId)
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

        $districtsPage = $query->paginate(
            25,
            ['id', 'title'],
            'driver_home_district_filter_page',
            $this->districtPage
        );
        $districts = collect($districtsPage->items())
            ->map(static fn (Distract $district) => [
                'id' => $district->id,
                'title' => $district->title,
            ])
            ->all();

        $this->hasMoreDistricts = $districtsPage->hasMorePages();
        $this->districts = $append ? array_merge($this->districts, $districts) : $districts;
    }

    public function updatedCitySearch(): void
    {
        $this->cityPage = 1;
        $this->loadCityPage(resolve(FetchCitiesAction::class));
    }

    public function updatedDistrictSearch(): void
    {
        $this->districtPage = 1;
        $this->loadDistrictPage();
    }

    public function loadMoreCities(): void
    {
        if (!$this->hasMoreCities) {
            return;
        }

        $this->cityPage++;
        $this->loadCityPage(resolve(FetchCitiesAction::class), true);
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
        $city = collect($this->cities)->first(
            static fn (array $option) => (string) $option['id'] === (string) $cityId
        );
        if (!$city) {
            return;
        }

        $this->cityId = $city['id'];
        $this->cityName = $city['title'];
        $this->districtId = null;
        $this->districtName = null;
        $this->districtSearch = '';
        $this->districtPage = 1;
        $this->loadDistrictPage();
    }

    public function selectDistrict($districtId): void
    {
        $district = collect($this->districts)->first(
            static fn (array $option) => (string) $option['id'] === (string) $districtId
        );
        if (!$district) {
            return;
        }

        $this->districtId = $district['id'];
        $this->districtName = $district['title'];
    }

    public function applyFilters(): void
    {
        $this->status = $this->status ?: null;
        $this->dateFrom = $this->dateFrom ?: null;
        $this->dateTo = $this->dateTo ?: null;

        $this->validate([
            'code' => 'nullable|string|max:100',
            'status' => ['nullable', \Illuminate\Validation\Rule::in(DriverOrderStatuses::values())],
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date',
        ]);

        if ($this->dateFrom && $this->dateTo && $this->dateTo < $this->dateFrom) {
            $this->addError('dateTo', __('driver::home.order_date_range_invalid'));
            return;
        }

        $this->appliedFilters = [
            'code' => trim((string) $this->code),
            'status' => $this->status,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'city_id' => $this->cityId,
            'district_id' => $this->districtId,
        ];
        $this->resetErrorBag();
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->code = '';
        $this->status = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->cityId = null;
        $this->cityName = null;
        $this->citySearch = '';
        $this->cityPage = 1;
        $this->districtId = null;
        $this->districtName = null;
        $this->districtSearch = '';
        $this->districtPage = 1;
        $this->districts = [];
        $this->hasMoreDistricts = false;
        $this->appliedFilters = [];
        $this->resetErrorBag();
        $this->loadCityPage(resolve(FetchCitiesAction::class));
        $this->resetPage();
    }

    public function updatedPerPage($perPage): void
    {
        $this->perPage = in_array((int) $perPage, [10, 25, 50, 100], true)
            ? (int) $perPage
            : 10;
        $this->resetPage();
    }

    public function render()
    {
        $driver = auth('driver')->user();
        abort_unless($driver, 403);

        $query = MerchantOrder::query()
            ->where('driver_id', $driver->id);

        if (!empty($this->appliedFilters['code'])) {
            $query->where('code', 'like', '%' . $this->appliedFilters['code'] . '%');
        }

        if (!empty($this->appliedFilters['status'])) {
            $query->where('status', $this->appliedFilters['status']);
        }

        if (!empty($this->appliedFilters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->appliedFilters['date_from']);
        }

        if (!empty($this->appliedFilters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->appliedFilters['date_to']);
        }

        if (!empty($this->appliedFilters['city_id']) || !empty($this->appliedFilters['district_id'])) {
            $query->whereHas('branch', function ($branchQuery) {
                if (!empty($this->appliedFilters['city_id'])) {
                    $branchQuery->where('city_id', $this->appliedFilters['city_id']);
                }

                if (!empty($this->appliedFilters['district_id'])) {
                    $branchQuery->where('distract_id', $this->appliedFilters['district_id']);
                }
            });
        }

        $recentOrders = $query
            ->with([
                'user',
                'payment',
                'orders_history' => function ($query) {
                    $query->whereIn('action', [
                        MerchantOrderHistoryStatuses::DRIVER_ACCEPT->value,
                        MerchantOrderHistoryStatuses::MARKED_READY->value,
                        MerchantOrderHistoryStatuses::DRIVER_ASSIGNED_MANUALLY->value,
                    ])->orderByDesc('id');
                },
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('driver::livewire.home.assigned-orders', compact('driver', 'recentOrders'));
    }
}
