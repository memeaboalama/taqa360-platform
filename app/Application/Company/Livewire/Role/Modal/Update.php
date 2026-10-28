<?php

namespace Application\Company\Livewire\Role\Modal;

use App\Domain\Provider\Models\Company;
use App\Support\Actions\Dashboard\Spatie\Permission\FetchPermissionAction;
use App\Support\Actions\Dashboard\Spatie\Role\FetchSingleRoleAction;
use App\Support\Actions\Dashboard\Spatie\Role\UpdateRoleAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;
    public $role;
    public $permissions = [];
    public $permissionsEdit = [];
    public $companyId;
    protected $listeners = ['updateRole'];

    public function mount(FetchPermissionAction $fetchPermissionAction)
    {
        $this->companyId = resolve('_InitialCompany');
        $this->permissionsEdit = $fetchPermissionAction
            ->handle(['team_type' => Company::class, 'guard_name' => 'company_employee']);
    }

    public function updateRole($id, FetchSingleRoleAction $fetchSingleRoleAction): void
    {
        $role = $fetchSingleRoleAction->handle(['id' => $id], ['permissions']);
        if ($role) {
            $this->role = $role;
            $this->name = (strstr($role->name, '_', true)) ? strstr($role->name, '_', true) : $role->name;
            $this->permissions = $role->permissions()->pluck('permission_id')->toArray();
            $this->emit('openModal', '#updateRole');
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'permissions' => 'nullable|array',
        ];
    }

    public function store(UpdateRoleAction $updateRoleAction): void
    {
        $data = $this->validate();
        $data['team_id'] = $this->companyId;
        DB::beginTransaction();
        $updateRoleAction->handle($this->role->id,$data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateRole');
        $this->alert('success', __('company::home.data_updated_successfully'));
        DB::commit();
    }

    public function render()
    {
        return view('company::livewire.role.modal.update');
    }
}
