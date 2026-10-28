<?php

namespace Application\Marketplace\Livewire\Branch\Tabs;

use App\Domain\AppBase\Actions\Location\GetDistrictsAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\UpdateBranchEmployeesAction;
use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployeeAction;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class Employee extends Component
{
    use LivewireAlert;

    public mixed $employees = [];
    public int $branch_id;
    public int $merchantId;
    public array $employees_ids = [];
    public array $other_employees_ids = [];


    protected GetDistrictsAction $getDistrictsAction;


    public function mount($branch_id, FetchEmployeeAction $fetchEmployeeAction): void
    {
        $this->merchantId = resolve('_InitialMerchant');
        $this->branch_id = $branch_id;
        $this->employees_ids = MerchantBranch::with('employees')
            ->find($branch_id)
            ?->employees()
            ->pluck('merchant_employee_id')
            ->toArray();

        $this->other_employees_ids = DB::table('merchant_branch_employees')
            ->where('merchant_branch_id', $branch_id)
            ->whereNotIn('merchant_employee_id', $this->employees_ids)
            ->pluck('merchant_employee_id')
            ->toArray();

        $this->employees = $fetchEmployeeAction->handle($this->merchantId, $branch_id, $this->other_employees_ids);
    }

    protected function rules(): array
    {
        return [
            'employees_ids' => 'required|array',
        ];
    }

    public function store(UpdateBranchEmployeesAction $updateBranchEmployeesAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateBranchEmployeesAction->handle($this->branch_id, $data);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.branch.tabs.employee');
    }
}
