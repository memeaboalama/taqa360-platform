<?php

namespace Application\Admin\Livewire\Educator\Modal;

use App\Domain\Admin\Actions\Dashboard\Educator\DestroyEducatorAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteEducator'];

    public function deleteEducator($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteEducator');
    }

    public function destroy(DestroyEducatorAction $destroyEducatorAction): void
    {
        DB::beginTransaction();
        $destroyEducatorAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteEducator');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.educator.modal.delete');
    }
}
