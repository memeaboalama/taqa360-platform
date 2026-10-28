<?php

namespace Application\Marketplace\Livewire\Cashback\Modal;

use App\Domain\AppBase\Actions\CashBack\DestroyCashBackAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteMerchantCashback'];

    public function deleteMerchantCashback($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteMerchantCashback');
    }

    public function destroy(DestroyCashBackAction $destroyCashbackAction): void
    {
        DB::beginTransaction();
        $destroyCashbackAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteMerchantCashback');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Cash Back Deactivated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.cashback.modal.delete');
    }
}
