<?php

namespace Application\Admin\Livewire\Services\Modal;

use App\Domain\Admin\Actions\Dashboard\Employee\FetchSingleEmployeeAction;
use App\Domain\Admin\Actions\Dashboard\Employee\UpdateEmployeeAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchSingleCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\StoreCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\UpdateCategoryAction;
use DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public $name, $email, $password, $phone, $password_confirmation, $status = 'active';
    public $element_id;
    protected $listeners = ['updateEmployee'];

    public $rolesShow, $role;

//    public function mount(FetchRolesAction $fetchRolesAction)
//    {
//        $this->rolesShow = $fetchRolesAction->handle(['id','name']);
//    }

    public function updateEmployee($id, FetchSingleEmployeeAction $fetchEmployeeAction): void
    {

        $row = $fetchEmployeeAction->handle($id);
        if ($row) {
            $this->name = $row->name;
            $this->email = $row->email;
//            $this->phone = $row->phone;
            $this->status = $row->status;
            $this->element_id = $row->id;
//            $this->role = $row->roles()?->first()?->id;
            $this->emit('openModal', '#updateEmployee');
        }
    }

    protected function rules(): array
    {
        return [
//            'role' => 'required|exists:roles,id',
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|string|min:2|max:200|unique:merchant_employees,email,' . $this->element_id,
//            'phone' => 'required|string|min:2|max:200|unique:merchant_employees,phone,' . $this->element_id,
            'password' => 'sometimes|string|min:6|max:50|confirmed|nullable',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateEmployeeAction $updateEmployeeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateEmployeeAction->handle($this->element_id, $data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateEmployee');
        $this->alert('success', 'Employee Updated Successfully');
        $this->reset('name', 'role', 'email', 'password', 'password_confirmation', 'status', 'element_id');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.service.modal.update');
    }
}
