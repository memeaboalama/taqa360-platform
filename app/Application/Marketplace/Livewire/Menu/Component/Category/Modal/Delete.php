<?php

namespace Application\Marketplace\Livewire\Menu\Component\Category\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Category\DestroyCategoryAction;
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

    public function destroy(DestroyCategoryAction $destroyCategoryAction): void
    {
        $category = \Domain\MarketPlace\Models\MerchantCategory::find($this->element_id);
        if (!$category) {
            $this->alert('error', __('marketplace::home.delete_failed'));
            return;
        }
        if ($category->items()->exists() || $category->branches()->exists()) {
            $this->alert('error', __('marketplace::home.cannot_delete_category_has_items'));
            return;
        }
        DB::beginTransaction();
        try {
            $destroyCategoryAction->handle($this->element_id);
            $this->emit('closeModal', '#deleteCategory');
            $this->emit('refreshCategories', $this->menu_id);
            $this->reset('element_id');
            $this->alert('success', __('marketplace::home.data_deleted_successfully'));
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->alert('error', __('marketplace::home.delete_failed'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.category.modal.delete');
    }
}
