<?php

namespace Application\Admin\Livewire\Category\Marketplace\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Marketplace\DestroyMarketplaceCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteMarketplaceCategory'];

    public function deleteMarketplaceCategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteMarketplaceCategory');
    }

    public function destroy(DestroyMarketplaceCategoryAction $destroyMarketplaceCategoryAction): void
    {
        if (!$this->element_id) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $deleted = DB::transaction(
            fn () => $destroyMarketplaceCategoryAction->handle($this->element_id)
        );
        if (!$deleted) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->emit('closeModal', '#deleteMarketplaceCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.marketplace.modal.delete');
    }

}
