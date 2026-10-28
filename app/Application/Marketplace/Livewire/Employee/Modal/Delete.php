<?php

namespace Application\Marketplace\Livewire\Employee\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Employee\DestroyEmployeeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{

    use LivewireAlert;

    public  $element_id = null;
    protected $listeners = ['deleteEmployee'];

    public function deleteEmployee($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteEmployee');
    }

    public function destroy(DestroyEmployeeAction $destroyEmployeeAction): void
    {
        DB::beginTransaction();
        $destroyEmployeeAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteEmployee');
        $this->emit('refreshDatatable');
        $this->reset('element_id');
        $this->alert('success', __('marketplace::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.employee.modal.delete');
    }
}
