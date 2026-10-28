<?php

namespace Application\Admin\Livewire\Category\Main\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Main\DestroyMainCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Nationality\DestroyNationalityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteMainCategory'];

    public function deleteMainCategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteMainCategory');
    }

    public function destroy(DestroyMainCategoryAction $destroyMainCategoryAction): void
    {
        DB::beginTransaction();
        $destroyMainCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteMainCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.main.modal.delete');
    }

}
