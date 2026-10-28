<?php

namespace Application\Admin\Livewire\Order\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Employee\FetchEmployeeAction;
use App\Domain\MarketPlace\Actions\Dashboard\Order\AssignEmployeeAction;
use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Assign extends Component
{
    use LivewireAlert;
    public mixed $employees = [];
    public $order, $employee;
    protected $listeners = ['assignEmployee'];

    public function assignEmployee($id, FetchSingleOrderAction $fetchSingleOrderAction, FetchEmployeeAction $fetchEmployeeAction): void
    {
        $order = $fetchSingleOrderAction->getById($id);
        if ($order) {
            $this->order = $order;
            $this->employee = $order->employee_id;
            $this->employees = $fetchEmployeeAction->handle($this->order->merchant_id, $this->order->merchant_branch_id);
            $this->emit('openModal', '#assignEmployee');
        }
    }

    protected function rules(): array
    {
        return [
            'employee' => 'required|exists:merchant_employees,id',
        ];
    }

    public function store(AssignEmployeeAction $assignEmployeeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $assignEmployeeAction->handle($this->order->id, $data);
        $this->emit('closeModal', '#assignEmployee');
        $this->emit( 'refresh_employees', $this->order);
        $this->emit('refreshDatatable');
        $this->alert('success', 'Employee Assigned Successfully');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.order.modal.assign');
    }
}
