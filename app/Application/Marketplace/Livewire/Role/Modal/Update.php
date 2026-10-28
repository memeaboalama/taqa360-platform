<?php

namespace Application\Marketplace\Livewire\Role\Modal;

use App\Support\Actions\Dashboard\Spatie\Permission\FetchPermissionAction;
use App\Support\Actions\Dashboard\Spatie\Role\FetchSingleRoleAction;
use App\Support\Actions\Dashboard\Spatie\Role\UpdateRoleAction;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Update extends Component
{
    use LivewireAlert;
    public $role;
    public $permissions = [];
    public $permissionsEdit = [];
    public $merchantId;
    protected $listeners = ['updateRole'];

    public function mount(FetchPermissionAction $fetchPermissionAction)
    {
        $this->merchantId = resolve('_InitialMerchant');
        $this->permissionsEdit = $fetchPermissionAction
            ->handle(['team_type' => Merchant::class, 'guard_name' => 'merchant_employee']);
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
        $data['team_id'] = $this->merchantId;
        DB::beginTransaction();
        $updateRoleAction->handle($this->role->id,$data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateRole');
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();
    }

    public function render()
    {
        return view('marketplace::livewire.role.modal.update');
    }
}
