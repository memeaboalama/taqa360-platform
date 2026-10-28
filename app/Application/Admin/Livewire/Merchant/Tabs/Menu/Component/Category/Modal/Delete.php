<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Category\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Category\DestroyMerchantCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    public $menu_id = null;
    protected $listeners = ['deleteCategory'];

    public function deleteCategory($id, $menu_id): void
    {
        $this->element_id = $id;
        $this->menu_id = $menu_id;
        $this->emit('openModal', '#deleteCategory');
    }

    public function destroy(DestroyMerchantCategoryAction $destroyMerchantCategoryAction): void
    {
        DB::beginTransaction();
        $destroyMerchantCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteCategory');
        $this->emit('refreshCategories', $this->menu_id);
        $this->reset('element_id');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.category.modal.delete');
    }
}
