<?php

namespace Application\Admin\Livewire\Towing\Line\Modal;

use App\Domain\Admin\Actions\Dashboard\Towing\Line\DestroyLineAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteTowingLine'];

    public function deleteTowingLine($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteTowingLine');
    }

    public function destroy(DestroyLineAction $destroyLineAction): void
    {
        DB::beginTransaction();
        $destroyLineAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteTowingLine');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Towing Line Deleted Successfully');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.towing.line.modal.delete');
    }
}
