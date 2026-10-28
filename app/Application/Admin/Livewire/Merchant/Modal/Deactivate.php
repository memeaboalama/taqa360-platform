<?php

namespace Application\Admin\Livewire\Merchant\Modal;

use App\Domain\Admin\Actions\Dashboard\Merchant\ActivateMerchantAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Deactivate extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deactivateMerchant'];

    public function deactivateMerchant($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deactivateMerchant');
    }
    public function deactivate(ActivateMerchantAction $activateMerchantAction): void
    {
        DB::beginTransaction();
        $activateMerchantAction->deactivate($this->element_id);
        $this->emit('closeModal', '#deactivateMerchant');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.deactivated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.modal.deactivate');
    }
}
