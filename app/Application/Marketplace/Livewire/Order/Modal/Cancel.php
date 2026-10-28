<?php

namespace Application\Marketplace\Livewire\Order\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Order\CancelOrderAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Cancel extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['cancelOrder'];

    public function cancelOrder($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#cancelOrder');
    }

    public function destroy(CancelOrderAction $cancelOrderAction): void
    {
        if (!$this->element_id) return;

        DB::beginTransaction();
        $cancelOrderAction->handle($this->element_id);

        $logHistory = app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class);
        $user = auth()->user() ?? auth('merchant')->user() ?? auth('merchant_employee')->user();
        $logHistory->handle(
            $this->element_id,
            \App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses::USER_CANCELLED->value,
            'Merchant cancelled order via web',
            $user
        );

        DB::commit();

        $this->emit('closeModal', '#cancelOrder');
        $this->emit('refreshDatatable');
        $this->emit('refreshOrder');
        $this->alert('success', __('marketplace::home.order_cancelled_successfully'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.order.modal.cancel');
    }
}
