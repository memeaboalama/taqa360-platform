<?php

namespace Application\Admin\Livewire\Role\Modal;

use App\Support\Actions\Dashboard\Spatie\Role\DestroyRoleAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteRole'];

    public function deleteRole($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteRole');
    }

    public function destroy(DestroyRoleAction $destroyRoleAction): void
    {
        DB::beginTransaction();
        $destroyRoleAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteRole');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Role Deleted Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.role.modal.delete');
    }
}
