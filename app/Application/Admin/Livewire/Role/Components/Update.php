<?php

namespace Application\Admin\Livewire\Role\Components;

use App\Support\Actions\Dashboard\Spatie\Permission\FetchPermissionAction;
use App\Support\Actions\Dashboard\Spatie\Role\FetchSingleRoleAction;
use App\Support\Actions\Dashboard\Spatie\Role\StoreRoleAction;
use App\Support\Actions\Dashboard\Spatie\Role\UpdateRoleAction;
use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;
    public $permissions, $permissionsEdit;
    public $name = null, $role;
    public PermissionRepository $permissionRepository;

    public function mount(int $roleId, FetchPermissionAction $fetchPermissionAction, FetchSingleRoleAction $fetchSingleRoleAction)
    {
        $this->role = $fetchSingleRoleAction->handle(['id' => $roleId]);
        $this->name = (strstr($this->role->name, '_', true)) ? strstr($this->role->name, '_', true) : $this->role->name;
        $this->permissions = $this->role->permissions()->pluck('permission_id')->toArray();
        $this->permissionsEdit = $fetchPermissionAction
            ->handle(['team_type' => Admin::class, 'guard_name' => 'admin']);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'permissions' => 'nullable',
        ];
    }

    public function update(UpdateRoleAction $updateRoleAction)
    {
        $data = $this->validate();
        $data['team_id'] = 1;
        DB::beginTransaction();
        $updateRoleAction->handle($this->role->id, $data);
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Role Updated Successfully'));
        DB::commit();
        return $this->redirect('/admin/roles');
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.role.components.update');
    }
}
