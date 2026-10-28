<?php

namespace Application\Marketplace\Livewire\Service\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Service\DestroyServiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteService'];

    public function deleteService($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteService');
    }

    public function destroy(DestroyServiceAction $destroyServiceAction): void
    {
        DB::beginTransaction();
        $destroyServiceAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteService');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Service Deleted Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.service.modal.delete');
    }

}
