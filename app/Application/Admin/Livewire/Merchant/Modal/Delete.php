<?php

namespace Application\Admin\Livewire\Merchant\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Category\DestroyNationalityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteCategory'];

    public function deleteCategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteCategory');
    }

    public function destroy(DestroyNationalityAction $destroyCategoryAction): void
    {
        DB::beginTransaction();
        $destroyCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Category Deleted Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.modal.delete');
    }

}
