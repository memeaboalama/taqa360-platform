<?php

namespace Application\Admin\Livewire\Wallet\Modal\Coupon;

use App\Domain\AppBase\Actions\WalletCoupon\DestroyWalletCouponAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteWalletCoupon'];

    public function deleteWalletCoupon($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteWalletCoupon');
    }

    public function destroy(DestroyWalletCouponAction $destroyWalletCouponAction): void
    {
        DB::beginTransaction();
        $destroyWalletCouponAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteWalletCoupon');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Coupon code Deactivated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.wallet.modal.coupon.delete');
    }
}
