<?php

namespace Application\Marketplace\Livewire\Menu\Component\Item\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\DestroyItemAction;
use App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteItem'];
    public $menu_id = null, $category_id;
    public $merchantId;
    public function mount(): void
    {
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function deleteItem($id, $menu_id, FetchSingleItemAction $action): void
    {
        $item = $action->handle($id, $this->merchantId);
        $this->element_id = $id;
        $this->menu_id = $menu_id;
        $this->category_id = $item->category_id;
        $this->emit('openModal', '#deleteItem');
    }

    public function destroy(DestroyItemAction $destroyItemAction): void
    {
        $item = \Domain\MarketPlace\Models\Item::find($this->element_id);
        if (!$item) {
            $this->alert('error', __('marketplace::home.delete_failed'));
            return;
        }
        if ($item->orderItems()->exists()
            || $item->cartItems()->exists()
            || \Illuminate\Support\Facades\DB::table('web_cart_items')->where('item_id', $this->element_id)->exists()
            || \Illuminate\Support\Facades\DB::table('cart_choices')->where('item_id', $this->element_id)->exists()
            || \Illuminate\Support\Facades\DB::table('favorites')->where('item_id', $this->element_id)->exists()
        ) {
            $this->alert('error', __('marketplace::home.cannot_delete_item_linked'));
            return;
        }

        DB::beginTransaction();
        try {
            $item->itemPrices()->delete();
            $item->additionGroups()->detach();
            $item->specificationsItems()->delete();
            $item->specificationsPropertiesItems()->delete();
            $item->files()->delete();
            \Illuminate\Support\Facades\DB::table('merchant_choices')->where('item_id', $this->element_id)->delete();

            $destroyItemAction->handle($this->element_id);
            $this->emit('closeModal', '#deleteItem');
            $this->emit('refreshItems', $this->category_id, $this->menu_id);
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
        return view('marketplace::livewire.menu.component.item.modal.delete');
    }
}
