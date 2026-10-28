<?php

namespace Application\Admin\Livewire\Order\Table;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Application\Marketplace\Enums\MerchantOrderMarks;
use App\Domain\Admin\Actions\Dashboard\City\FetchSingleCityAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchDistrictAction;
use App\Domain\Admin\Actions\Dashboard\District\FetchSingleDistrictAction;
use App\Domain\Admin\Actions\Dashboard\Merchant\FetchSingleMerchantAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployessAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchSingleEmployeeAction;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Domain\MarketPlace\Models\MerchantBranch;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Domain\User\Models\User;
use App\Support\Enum\ActiveEnum;
use Illuminate\Support\Facades\Log;
use App\Console\Commands\OrderChangeStatusIfPaided;
use App\Domain\MarketPlace\Jobs\OrderChangeStatusIfPaidedJop;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Support\Carbon;
use App\Domain\MarketPlace\Models\MerchantOrder;
use SebastianBergmann\Type\VoidType;

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
    public $customer_id = null;
    public $customer_name = null;
    public $customer_phone = null;
    public $mark = null;
    public $mark_name = null;
    public $pending = null;
    public $statusList = null;
    public $confirmed = null;
    public Collection $statuses;
    public $branches, $employees, $cities, $districts, $merchants, $marks;
    public array $filters = [];

    protected $listeners = [
        'refreshMerchantFilter',
        'removeCodeFilter',
        'removeMerchantFilter',
        'refreshCustomerFilter',
        'removeCustomerFilter',
        'removeStatusFilter',
        'removeBranchFilter',
        'removeEmployeeFilter',
        'removeDateFilter',
        'removeCityFilter',
        'removeDistrictFilter',
        'removeMarkFilter',
        'getBetweenDates',
        'refreshOrdersTable'
    ];

    public function handle()
    {
        //  Log::warning("begin order-change-status-if-paided");
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        // $orderAction= new OrderAction($orderRepo);
        $pastOrdersWithoutDriver = $orderRepo->getDraftOrdersWithPaymentStatusPaid();
        foreach ($pastOrdersWithoutDriver as $order) {
            Log::warning("begin paided order change status from draft to pinding order code : " . $order->code);
            $this->UpdateStatus($order);
        }

        // Log::warning("end paided order change status from draft to pinding order code : ");
        // echo 'About ' . $pastOrdersWithoutDriver->count(). 'draft orders with paymant paid status\n';
        // echo Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s') . " now\n";

    }

    public function UpdateStatus($order)
    {
        // Log::warning("begin order-change-status-if-paidedmnbm");
        dispatch(new OrderChangeStatusIfPaidedJop($order))->afterResponse();
    }
    public function mount(
        FetchMerchants $fetchMerchants,
        FetchDistrictAction $fetchDistrictAction,
        FetchCitiesAction $fetchCitiesAction
    ) {
        $this->date_condition = "equal";
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->marks = collect(MerchantOrderMarks::cases());
        $this->branches = collect();
        $this->employees = collect();
        $this->cities = $fetchCitiesAction->handle();
        $this->districts = $fetchDistrictAction->all();
        $this->merchants = $fetchMerchants->fetchCollection();
    }

    public function resetSearch(): void
    {
        $this->emit('resetSearch');
    }

    public function filterCode(): void
    {
        $this->filters['code'] =  $this->code;
        $this->emit('refreshOrders', $this->filters);
    }
    public function refreshOrdersTable(): void
    {
        try {
            $this->handle();
            $this->removeFilter();
            $this->emit('refreshOrders', $this->filters);
        } catch (\Exception $ex) {
            // Log::error($ex->getMessage());
        }
    }
    public function removeFilter(): void
    {
        $this->filters = [];
        $this->code = null;
        $this->status = null;
        $this->date = null;
        $this->to_date = null;
        $this->to_date_visible = false;
        $this->date_condition = null;
        $this->branch_name = null;
        $this->merchant_company_name = null;
        $this->employee_name = null;
        $this->city_name = null;
        $this->district_name = null;
        $this->branch_id = null;
        $this->employee_id = null;
        $this->merchant_id = null;
        $this->city_id = null;
        $this->district_id = null;
        $this->customer_id = null;
        $this->customer_name = null;
        $this->customer_phone = null;
        $this->mark_name = null;
        $this->mark = null;
        $this->pending = null;
        $this->confirmed = null;
    }
    public function removeCodeFilter(): void
    {
        $this->code = null;
        $this->filters['code'] = null;
        $this->emit('refreshOrders', $this->filters);
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

    public function refreshMerchantFilter(
        FetchBranchAction $fetchBranchAction,
        FetchEmployessAction $fetchEmployeesAction,
        int $merchantId = null,
        string $company_name
    ): void {
        $this->merchant_id = $merchantId;

        $this->merchant_company_name = $company_name ?? null;
        $this->branches = $fetchBranchAction->handle($this->merchant_id);
        $this->employees = $fetchEmployeesAction->getByMerchant($this->merchant_id);
        $this->filters['merchant_id'] =  $this->merchant_id;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeMerchantFilter(): void
    {
        $this->merchant_id = null;
        $this->merchant_company_name = null;
        $this->filters['merchant_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshOrders', $this->filters);
    }

    public function refreshCustomerFilter(User $customer): void
    {
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->name ?? null;
        $this->customer_phone = $customer->phone ?? null;
        $this->filters['user_id'] =  $this->customer_id;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeCustomerFilter(): void
    {
        $this->customer_id = null;
        $this->customer_name = null;
        $this->customer_phone = null;
        $this->filters['user_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterBranch(FetchSingleBranchAction $fetchSingleBranchAction): void
    {
        if ($this->branch_id == "") {
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



    public function filterEmployee(FetchSingleEmployeeAction $fetchSingleEmployeeAction): void
    {

        if ($this->employee_id == "") {
            $this->employee_id = null;
        }

        $this->employee_name = $fetchSingleEmployeeAction
            ->handle($this->employee_id, $this->merchant_id)
            ?->name ?? null;

        $this->filters['employee_id'] =  $this->employee_id;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeEmployeeFilter(): void
    {
        $this->employee_id = null;
        $this->employee_name = null;
        $this->filters['employee_id'] = null;
        $this->emit('refreshOrders', $this->filters);
    }


    public function filterCity(FetchSingleCityAction $fetchSingleCityAction): void
    {
        if ($this->city_id == "") {
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
        $this->city_id = null;
        $this->city_name = null;
        $this->filters['city_id'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterDistrict(FetchSingleDistrictAction $fetchSingleDistrictAction): void
    {
        if ($this->district_id == "") {
            $this->district_id = null;
        }

        $this->district_name = $fetchSingleDistrictAction
            ->handle($this->district_id)
            ?->title ?? null;

        $this->filters['district_id'] =  $this->district_id;
        $this->emit('refreshOrders', $this->filters);
    }
    public function filterMark(): void
    {
        if ($this->mark == "") {
            $this->mark = null;
        }
        $this->filters['mark'] =  $this->mark;
        $this->emit('refreshOrders', $this->filters);
    }
    public function removeMarkFilter(): void
    {
        $this->mark = null;
        $this->filters['mark'] = null;
        $this->emit('refreshOrders', $this->filters);
    }
    public function filterStatusList(): void
    {
        $this->statusList = null;
        if ($this->pending) {
            $this->statusList = [$this->pending];
        }
        if ($this->confirmed) {
            $this->statusList = $this->statusList ? [...$this->statusList, $this->confirmed] : [$this->confirmed];
        }

        $this->filters['statusList'] =  $this->statusList;
        $this->emit('refreshOrders', $this->filters);
    }
    public function filterStatusConfirmed(): void
    {
        if (!$this->confirmed)
            $this->confirmed = 'confirmed';
        else
            $this->confirmed = null;
        $this->filterStatusList();
    }
    public function filterStatusPending(): void
    {
        if (!$this->pending)
            $this->pending = 'pending';
        else
            $this->pending = null;
        $this->filterStatusList();
    }

    public function removeDistrictFilter(): void
    {
        $this->district_id = null;
        $this->district_name = null;
        $this->filters['district_id'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterDate(): void
    {
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeDateFilter(): void
    {
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
        if ($this->date_condition == "between") {
            $this->to_date_visible = true;
        } else {
            $this->to_date_visible = false;
        }
    }

    public function render()
    {
        $this->statuses = collect(MerchantOrderStatuses::cases());
        $this->marks = collect(MerchantOrderMarks::cases());
        return view('admin::livewire.order.table.filter');
    }
}
