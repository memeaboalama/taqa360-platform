<?php

namespace Application\Admin\Livewire\Finance\Reports\Order\Merchant\Components;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\Admin\Actions\Dashboard\City\FetchSingleCityAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchDistrictAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchSingleDistrictAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\AppBase\Actions\CashBack\FetchCashBackAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchSingleEmployeeAction;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $date = null;
    public $to_date = null;
    public bool $to_date_visible = false;
    public $date_condition = null;
    public $branch_name = null;
    public $merchant_company_name = null;
    public $city_name = null;
    public $cashBack_name = null;
    public $cashBack_id = null;
    public $district_name = null;
    public $branch_id = null;
    public $merchant_id = null;
    public $city_id = null;
    public $district_id = null;
    public Collection $statuses;
    public $branches, $employees, $cities, $districts, $merchants,$cashBack;
    public array $filters = [];

    protected $listeners = [
        'refreshMerchantFilter',
        'removeMerchantFilter',
        'removeStatusFilter',
        'removeBranchFilter',
        'removeDateFilter',
        'removeCityFilter',
        'removeDistrictFilter',
        'removeCashBackFilter',
        'getBetweenDates'];

    public function mount(
        FetchMerchants      $fetchMerchants,
        FetchDistrictAction $fetchDistrictAction,
        FetchCitiesAction   $fetchCitiesAction,
        FetchCashBackAction $fetcCashBackAction)
    {
        $this->date_condition = "equal";
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->branches = collect();
        $this->employees = collect();
        $this->cities = $fetchCitiesAction->handle();
        $this->districts = $fetchDistrictAction->all();
        $this->merchants = $fetchMerchants->fetchCollection();
        $this->cashBack = $fetcCashBackAction->handle();
    }

    public function resetSearch(): void
    {
        $this->emit('resetSearch');
    }

    public function filterStatus(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->filters['status'] = $this->status;
        $this->emit('refreshOrderReport', $this->filters);
    }
    public function filterCashBack(FetchCashBackAction $fetcCashBackAction): void
    {
        // $this->cashBack = $fetcCashBackAction->handle();
        Log::debug("message Cash Back Filter");
        $this->filters['cash_back_id'] = $this->cashBack_id;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function removeCashBackFilter( FetchCashBackAction $fetcCashBackAction, int $cashBack_id = null, string $cashBack_name): void
{
    $this->cashBack_id = $cashBack_id;
    $this->cashBack_name =  null;
    $this->cashBack = $fetcCashBackAction->handle();
    $this->filters['cash_back_id'] = $this->cashBack_id;
    $this->emit('refreshOrderReport', $this->filters);
}
    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->filters['status'] = null;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function refreshMerchantFilter(FetchBranchAction $fetchBranchAction
        , FetchEmployessAction $fetchEmployeesAction, int $merchantId = null, string $company_name): void
    {
        $this->merchant_id = $merchantId;
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->merchant_company_name = $company_name ?? null;
        $this->branches = $fetchBranchAction->handle($this->merchant_id);
        $this->employees = $fetchEmployeesAction->getByMerchant($this->merchant_id);
        $this->filters['merchant_id'] = $this->merchant_id;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function removeMerchantFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->merchant_id = null;
        $this->merchant_company_name = null;
        $this->filters['merchant_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function filterBranch(FetchSingleBranchAction $fetchSingleBranchAction): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        if ($this->branch_id == "") {
            $this->branch_id = null;
        }

        $this->branch_name = $fetchSingleBranchAction
            ->handle($this->branch_id)
            ->title ?? null;
        $this->filters['merchant_branch_id'] = $this->branch_id;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function removeBranchFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->branch_id = null;
        $this->branch_name = null;
        $this->filters['merchant_branch_id'] = null;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function filterCity(FetchSingleCityAction $fetchSingleCityAction): void
    {
        if ($this->city_id == "") {
            $this->city_id = null;
        }


        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->city_name = $fetchSingleCityAction
            ->handle($this->city_id)
            ?->title ?? null;

        $this->filters['city_id'] = $this->city_id;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function removeCityFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->city_id = null;
        $this->city_name = null;
        $this->filters['city_id'] = null;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function filterDistrict(FetchSingleDistrictAction $fetchSingleDistrictAction): void
    {
        if ($this->district_id == "") {
            $this->district_id = null;
        }

        $this->statuses = collect(MerchantOrderStatuses::cases());

        $this->district_name = $fetchSingleDistrictAction
            ->handle($this->district_id)
            ?->title ?? null;

        $this->filters['district_id'] = $this->district_id;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function removeDistrictFilter(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->district_id = null;
        $this->district_name = null;
        $this->filters['district_id'] = null;
        $this->emit('refreshOrderReport', $this->filters);
    }

    public function filterDate(): void
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshOrderReport', $this->filters);
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
        $this->emit('refreshOrderReport', $this->filters);
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
        return view('admin::livewire.finance.reports.order.merchant.components.filter');
    }
}
