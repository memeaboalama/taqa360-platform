<?php

namespace Application\Admin\Livewire\Role\Components;

use App\Support\Actions\Dashboard\Spatie\Permission\FetchPermissionAction;
use App\Support\Actions\Dashboard\Spatie\Role\StoreRoleAction;
use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;
    public $permissions, $permissionsShow;
    public $name = null;
    public PermissionRepository $permissionRepository;

    public function mount(FetchPermissionAction $fetchPermissionAction)
    {
        $this->permissionsShow = $fetchPermissionAction
            ->handle(['team_type' => Admin::class, 'guard_name' => 'admin']);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'permissions' => 'nullable|array',
        ];
    }

    public function store(StoreRoleAction $storeRoleAction)
    {
        $data = $this->validate();
        $data['team_id'] = 1;
        $data['guard_name'] = 'admin';
        DB::beginTransaction();
        $storeRoleAction->handle($data);
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Role Saved Successfully'));
        $this->reset('name', 'permissions');
        DB::commit();
        return $this->redirect('/admin/roles');
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.role.components.create');
    }
}
