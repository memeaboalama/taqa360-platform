<?php

namespace Application\Marketplace\Livewire\Choice\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Domain\MarketPlace\Actions\Dashboard\Choice\DestroyChoiceAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\DestroyCategoryAction;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteChoice'];

    public function deleteChoice($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteChoice');
    }

    public function destroy(DestroyChoiceAction $destroyCategoryAction): void
    {
        DB::beginTransaction();
        $destroyCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteChoice');
        $this->emit('refreshDatatable');
        $this->reset('element_id');
        $this->alert('success', __('marketplace::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.choice.modal.delete');
    }
}
