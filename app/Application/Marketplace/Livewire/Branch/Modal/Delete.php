<?php

namespace Application\Marketplace\Livewire\Branch\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Branch\DestroyBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\DestroyProviderAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{

    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteBranch'];

    public function deleteBranch($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteBranch');
    }

    public function destroy(DestroyBranchAction $destroyBranchAction): void
    {
        DB::beginTransaction();
        $destroyBranchAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteBranch');
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.branch.modal.delete');
    }
}
