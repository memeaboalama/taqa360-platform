<?php

namespace Application\Company\Livewire\Role\Modal;

use App\Domain\Provider\Models\Company;
use App\Support\Actions\Dashboard\Spatie\Permission\FetchPermissionAction;
use App\Support\Actions\Dashboard\Spatie\Role\StoreRoleAction;
use App\Support\Repositories\Spatie\PermissionRepository;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;
    public $permissions, $permissionsShow;
    public $name = null;
    public $merchantId;
    public PermissionRepository $permissionRepository;

    public function mount(FetchPermissionAction $fetchPermissionAction)
    {
        $this->merchantId = resolve('_InitialCompany');
        $this->permissionsShow = $fetchPermissionAction
            ->handle(['team_type' => Company::class, 'guard_name' => 'company_employee']);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'permissions' => 'nullable|array',
        ];
    }

    public function store(StoreRoleAction $storeRoleAction): void
    {
        $data = $this->validate();
        $data['team_id'] = $this->merchantId;
        $data['guard_name'] = 'company_employee';
        DB::beginTransaction();
        $storeRoleAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createRole');
        $this->alert('success', __('marketplace::home.data_saved_successfully'));
        $this->reset('name', 'permissions');
        DB::commit();
    }
    public function render()
    {
        return view('company::livewire.role.modal.create');
    }
}
