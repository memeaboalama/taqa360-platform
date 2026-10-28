<?php

namespace Application\Marketplace\Livewire\Order\Table;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\Admin\Actions\Dashboard\City\FetchSingleCityAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchDistrictAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchSingleDistrictAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchSingleEmployeeAction;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Illuminate\Support\Collection;
use Livewire\Component;

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
    public $district_id = null;
    public Collection $statuses;
    public $branches, $employees, $cities, $districts, $merchants;
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
        FetchDistrictAction $fetchDistrictAction,
        FetchCitiesAction $fetchCitiesAction, FetchBranchAction $fetchBranchAction)
    {
        $this->date_condition = "equal";
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->branches = $fetchBranchAction->handle(resolve('_InitialMerchant'));
        $this->employees = collect();
        $this->cities = $fetchCitiesAction->handle();
        $this->districts = $fetchDistrictAction->all();
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
    }

    public function removeCityFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->city_id = null;
        $this->city_name = null;
        $this->filters['city_id'] = null;
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

        $this->filters['district_id'] =  $this->district_id;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeDistrictFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->district_id = null;
        $this->district_name = null;
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
