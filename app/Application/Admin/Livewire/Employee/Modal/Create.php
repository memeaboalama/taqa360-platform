<?php

namespace Application\Admin\Livewire\Employee\Modal;

use App\Domain\Admin\Actions\Dashboard\Employee\StoreEmployeeAction;
use App\Support\Actions\Dashboard\Spatie\Role\FetchRolesAction;
use App\Support\Rules\IsValidPhone;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public $name, $email, $password, $password_confirmation, $status = 'active';
    public $rolesShow, $role;
    protected StoreEmployeeAction $storeEmployeeAction;

    public function mount(FetchRolesAction $fetchRolesAction)
    {
        $this->rolesShow = $fetchRolesAction->fetchFilter(['id','name'], ['guard_name' => 'admin', 'team_id' => 1]);
    }

    protected function rules(): array
    {
        return [
            'role' => 'required|exists:roles,id',
            'name' => 'required|string|min:6|max:120',
            'email' => 'required|string|min:8|max:200|unique:admins,email',
            'password' => 'required|string|min:6|max:50|confirmed',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreEmployeeAction $storeEmployeeAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeEmployeeAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createEmployee');
        $this->alert('success', __('admin::home.Employee Created Successfully'));
        $this->reset('name', 'role', 'email', 'password', 'status', 'password_confirmation');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.employee.modal.create');
    }
}
