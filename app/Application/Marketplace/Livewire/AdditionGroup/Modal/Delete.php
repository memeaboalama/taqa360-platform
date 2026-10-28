<?php

namespace Application\Marketplace\Livewire\AdditionGroup\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Domain\MarketPlace\Actions\Dashboard\AdditionGroup\DestroyAdditionGroupAction;

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

    public function destroy(DestroyAdditionGroupAction $destroyCategoryAction): void
    {
        DB::beginTransaction();
        $destroyCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteAdditionGroup');
        $this->emit('refreshDatatable');
        $this->reset('element_id');
        $this->alert('success', __('marketplace::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.additiongroup.modal.delete');
    }
}
