<?php

namespace Application\Admin\Livewire\Merchant\Table;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\Admin\Actions\Dashboard\City\FetchSingleCityAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchDistrictAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchSingleDistrictAction;
use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\Admin\Actions\Dashboard\Region\FetchSingleRegionAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\AppBase\Actions\Location\FetchRegionsAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $date = null;
    public $to_date = null;
    public bool $to_date_visible = false;
    public $date_condition = null;
    public $region_name = null;
    public $city_name = null;
    public $district_name = null;
    public $city_id = null;
    public $region_id = null;
    public $district_id = null;
    public Collection $statuses;
    public $cities, $districts;
    public $merchant_company_name = null;
    public $merchant_id = null;
    public array $filters = [];

    protected $listeners = [
        'refreshMerchantFilter',
        'removeMerchantFilter',
        'removeStatusFilter',
        'removeDateFilter',
        'removeCityFilter',
        'removeRegionFilter',
        'removeDistrictFilter',
        'getBetweenDates'];

    public function mount(
        FetchDistrictAction $fetchDistrictAction,
        FetchCitiesAction $fetchCitiesAction,
        FetchRegionsAction $fetchRegionsAction)
    {
        $this->date_condition = "equal";
        $this->statuses = $this->filterableStatuses();
        $this->cities = $fetchCitiesAction->handle();
        $this->regions = $fetchRegionsAction->handle();
        $this->districts = $fetchDistrictAction->all();
    }

    public function filterStatus(): void
    {
        $this->statuses = $this->filterableStatuses();
        $this->filters['status'] =  $this->status;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = $this->filterableStatuses();
        $this->filters['status'] = null;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function filterRegion(FetchSingleRegionAction $fetchSingleRegionAction): void
    {
        if ($this->region_id == "")
        {
            $this->region_id = null;
        }

        $this->region_name = $fetchSingleRegionAction
            ->handle($this->region_id)
            ?->title ?? null;

        $this->statuses = $this->filterableStatuses();
        $this->filters['region_id'] =  $this->region_id;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function filterCity(FetchSingleCityAction $fetchSingleCityAction): void
    {
        if ($this->city_id == "")
        {
            $this->city_id = null;
        }

        $this->city_name = $fetchSingleCityAction
            ->handle($this->city_id)
            ?->title ?? null;

        $this->statuses = $this->filterableStatuses();
        $this->filters['city_id'] =  $this->city_id;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function removeRegionFilter(): void
    {
        $this->region_id = null;
        $this->region_name = null;
        $this->statuses = $this->filterableStatuses();
        $this->filters['region_id'] = null;
        $this->emit('refreshMerchants', $this->filters);
    }
    public function removeCityFilter(): void
    {
        $this->city_id = null;
        $this->city_name = null;
        $this->statuses = $this->filterableStatuses();
        $this->filters['city_id'] = null;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function filterDistrict(FetchSingleDistrictAction $fetchSingleDistrictAction): void
    {
        if ($this->district_id == "")
        {
            $this->district_id = null;
        }

        $this->district_name = $fetchSingleDistrictAction
            ->handle($this->district_id)
            ?->title ?? null;

        $this->statuses = $this->filterableStatuses();
        $this->filters['district_id'] =  $this->district_id;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function removeDistrictFilter(): void
    {
        $this->district_id = null;
        $this->district_name = null;
        $this->statuses = $this->filterableStatuses();
        $this->filters['district_id'] = null;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function filterDate(): void
    {
        $this->statuses = $this->filterableStatuses();
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function removeDateFilter(): void
    {
        $this->date_condition = "equal";;
        $this->date = null;
        $this->to_date = null;
        $this->statuses = $this->filterableStatuses();
        $this->filters['date_condition'] = null;
        $this->filters['date'] = null;
        $this->filters['to_date'] = null;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function getBetweenDates(): void
    {
        $this->statuses = $this->filterableStatuses();
        if ($this->date_condition == "between")
        {
            $this->to_date_visible = true;
        }else{
            $this->to_date_visible = false;
        }
    }

    public function refreshMerchantFilter(int $merchantId = null, string $company_name): void
    {
        $this->merchant_id = $merchantId;
        $this->statuses = $this->orderStatuses();
        $this->merchant_company_name = $company_name ?? null;
        $this->filters['merchant_id'] = $this->merchant_id;
        $this->emit('refreshMerchants', $this->filters);
    }

    public function removeMerchantFilter(): void
    {
        $this->statuses = $this->orderStatuses();
        $this->merchant_id = null;
        $this->merchant_company_name = null;
        $this->filters['merchant_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshMerchants', $this->filters);
    }
    private function filterableStatuses(): Collection
    {
        return collect(StatusEnum::cases())
            ->reject(fn($status) => $status === StatusEnum::DRAFT)
            ->values();
    }

    private function orderStatuses(): Collection
    {
        return collect(MerchantOrderStatuses::cases())
            ->reject(fn($status) => $status === MerchantOrderStatuses::DRAFT)
            ->values();
    }

    public function render()
    {
        return view('admin::livewire.merchant.table.filter');
    }
}
