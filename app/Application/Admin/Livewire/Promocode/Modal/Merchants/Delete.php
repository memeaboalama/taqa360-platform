<?php

namespace Application\Admin\Livewire\Promocode\Modal\Merchants;

use App\Domain\AppBase\Actions\Promocode\DestroyPromocodeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deletePromocode'];

    public function deletePromocode($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deletePromocode');
    }

    public function destroy(DestroyPromocodeAction $destroyPromocodeAction): void
    {
        DB::beginTransaction();
        $destroyPromocodeAction->handle($this->element_id);
        $this->emit('closeModal', '#deletePromocode');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Coupon code Deactivated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.promocode.modal.delete');
    }
}
