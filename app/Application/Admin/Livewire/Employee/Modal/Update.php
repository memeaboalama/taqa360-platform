<?php

namespace Application\Admin\Livewire\Employee\Modal;

use App\Domain\Admin\Actions\Dashboard\Employee\FetchSingleEmployeeAction;
use App\Domain\Admin\Actions\Dashboard\Employee\UpdateEmployeeAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchSingleCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\StoreCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\UpdateCategoryAction;
use App\Support\Actions\Dashboard\Spatie\Role\FetchRolesAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public $name, $email, $status = 'active';
    public $rolesShow, $role;
    public $element_id;
    protected $listeners = ['updateEmployee'];

    public function mount(FetchRolesAction $fetchRolesAction)
    {
        $this->rolesShow = $fetchRolesAction->fetchFilter(['id','name'], ['guard_name' => 'admin', 'team_id' => 1]);
    }

    public function updateEmployee($id, FetchSingleEmployeeAction $fetchEmployeeAction): void
    {

        $row = $fetchEmployeeAction->handle($id);
        if ($row) {
            $this->name = $row->name;
            $this->email = $row->email;
            $this->status = $row->status;
            $this->element_id = $row->id;
            $this->role = $row->roles()?->first()?->id;
            $this->emit('openModal', '#updateEmployee');
        }
    }

    protected function rules(): array
    {
        return [
            'role' => 'required|exists:roles,id',
            'name' => 'required|string|min:2|max:120',
            'email' => 'required|string|min:8|max:200|unique:admins,email,' . $this->element_id,
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
        $this->alert('success', __('admin::home.Employee Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.employee.modal.update');
    }
}
