<?php

namespace Application\Admin\Livewire\Towing\City\Modal;

use App\Domain\Admin\Actions\Dashboard\Towing\City\DestroyCityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteTowingCity'];

    public function deleteTowingCity($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteTowingCity');
    }

    public function destroy(DestroyCityAction $destroyCityAction): void
    {
        DB::beginTransaction();
        $destroyCityAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteTowingCity');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Towing City Deleted Successfully');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.towing.city.modal.delete');
    }
}
