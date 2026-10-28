<?php

namespace Application\Admin\Livewire\SalesAgent\Modal;

use App\Domain\Admin\Actions\Dashboard\SalesAgent\DestroySalesAgentAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteSalseAgent'];

    public function deleteSalseAgent($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteSalseAgent');
    }

    public function destroy(DestroySalesAgentAction $destroySalesAgentAction): void
    {
        DB::beginTransaction();
        $destroySalesAgentAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteSalseAgent');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.salesagent.modal.delete');
    }
}
