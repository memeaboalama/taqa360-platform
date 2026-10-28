<?php

namespace Application\Admin\Livewire\Catalog\Subcategory\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory\DestroySubcategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteSubcategory'];

    public function deleteSubcategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteSubcategory');
    }

    public function destroy(DestroySubcategoryAction $destroySubcategoryAction): void
    {
        DB::beginTransaction();
        $destroySubcategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteSubcategory');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::catalog.messages.subcategory_deleted'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.subcategory.modal.delete');
    }
}
