<?php

namespace Application\Admin\Livewire\Brand\Model\Modal;

use App\Domain\Admin\Actions\Dashboard\Brand\Model\DestroyModelAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteModel'];

    public function deleteModel($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteModel');
    }

    public function destroy(DestroyModelAction $destroyModelAction): void
    {
        DB::beginTransaction();
        $destroyModelAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteModel');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Model Deleted Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.brand.model.modal.delete');
    }
}
