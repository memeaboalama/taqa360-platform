<?php

namespace Application\Admin\Livewire\Category\Merchant\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Merchant\DestroyMerchantCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteMerchantCategory'];

    public function deleteMerchantCategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteMerchantCategory');
    }

    public function destroy(DestroyMerchantCategoryAction $destroyMerchantCategoryAction): void
    {
        DB::beginTransaction();
        $destroyMerchantCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteMerchantCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Merchant Category Deactivated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.merchant.modal.delete');
    }

}
