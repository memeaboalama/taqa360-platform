<?php

namespace Application\Company\Livewire\Provider\Modal;

use App\Domain\Provider\Actions\Dashboard\Provider\DestroyProviderAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteProvider'];

    public function deleteProvider($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteProvider');
    }

    public function destroy(DestroyProviderAction $destroyCategoryAction): void
    {
        DB::beginTransaction();
        $destroyCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteProvider');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Provider Deleted Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.provider.modal.delete');
    }

}
