<?php

namespace Application\Admin\Livewire\Catalog\ChildCategory\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\ChildCategory\DestroyChildCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteChildCategory'];

    public function deleteChildCategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteChildCategory');
    }

    public function destroy(DestroyChildCategoryAction $destroyChildCategoryAction): void
    {
        DB::beginTransaction();
        $destroyChildCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteChildCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::catalog.messages.child_category_deleted'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.childcategory.modal.delete');
    }
}
