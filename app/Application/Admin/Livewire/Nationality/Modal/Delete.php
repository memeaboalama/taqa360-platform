<?php

namespace Application\Admin\Livewire\Nationality\Modal;

use App\Domain\Admin\Actions\Dashboard\Nationality\DestroyNationalityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteNationality'];

    public function deleteNationality($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteNationality');
    }

    public function destroy(DestroyNationalityAction $destroyNationalityAction): void
    {
        DB::beginTransaction();
        $destroyNationalityAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteNationality');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Nationality Deleted Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.nationality.modal.delete');
    }

}
