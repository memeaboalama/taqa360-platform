<?php

namespace Application\Admin\Livewire\Specification\Modal;

use App\Domain\Admin\Actions\Dashboard\Specification\DestroySpecificationAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteSpecification'];

    public function deleteSpecification($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteSpecification');
    }

    public function destroy(DestroySpecificationAction $destroySpecificationAction): void
    {
        DB::beginTransaction();
        $destroySpecificationAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteSpecification');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Specification Deactivated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.specification.modal.delete');
    }
}
