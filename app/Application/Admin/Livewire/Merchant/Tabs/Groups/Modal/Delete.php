<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Groups\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups\DestroyAdditionGroupAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteAdditionGroup'];

    public function deleteAdditionGroup($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteAdditionGroup');
    }

    public function destroy(DestroyAdditionGroupAction $destroyAdditionGroupAction): void
    {
        DB::beginTransaction();
        $destroyAdditionGroupAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteAdditionGroup');
        $this->emit('refreshDatatable');
        $this->reset('element_id');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.groups.modal.delete');
    }
}
