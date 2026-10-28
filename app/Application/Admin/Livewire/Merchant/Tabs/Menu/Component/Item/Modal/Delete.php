<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Item\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Item\DestroyItemAction;
use App\Domain\Admin\Actions\Dashboard\Menu\Item\FetchSingleItemAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteItem'];
    public $menu_id = null, $category_id;
    public function deleteItem($id, $menu_id, FetchSingleItemAction $fetchSingleItemAction): void
    {
        $item = $fetchSingleItemAction->handle($id);
        $this->element_id = $id;
        $this->menu_id = $menu_id;
        $this->category_id = $item->category_id;
        $this->emit('openModal', '#deleteItem');
    }

    public function destroy(DestroyItemAction $destroyItemAction): void
    {
        DB::beginTransaction();
        $destroyItemAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteItem');
        $this->emit('refreshItems', $this->category_id, $this->menu_id);
        $this->reset('element_id');
        $this->alert('success', __('admin::home.data_deleted_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.item.modal.delete');
    }
}
