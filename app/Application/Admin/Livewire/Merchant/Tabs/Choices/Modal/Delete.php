<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Choices\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Choice\DestroyChoiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

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

    public function destroy(DestroyChoiceAction $destroyChoiceAction): void
    {
        DB::beginTransaction();
        $destroyChoiceAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteChoice');
        $this->emit('refreshDatatable');
        $this->reset('element_id');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.choices.modal.delete');
    }
}
