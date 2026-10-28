<?php

namespace Application\Marketplace\Livewire\Product\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Item\DestroyItemAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteProduct'];

    public function deleteProduct($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteItem');
    }

    public function destroy(DestroyItemAction $destroyItemAction): void
    {
        DB::beginTransaction();
        try {
            $destroyItemAction->handle($this->element_id);
            $this->emit('closeModal', '#deleteItem');
            $this->emit('refreshProducts');
            $this->reset('element_id');
            $this->alert('success', __('admin::main.messages.product_deleted'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete Product Error: ' . $e->getMessage());
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.product.modal.delete');
    }
}