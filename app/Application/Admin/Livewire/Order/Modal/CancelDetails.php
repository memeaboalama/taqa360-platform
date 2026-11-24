<?php

namespace Application\Admin\Livewire\Order\Modal;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Order\CancelOrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CancelDetails extends Component
{
    use LivewireAlert;

    public ?int $element_id = null;

    protected $listeners = ['cancelOrder'];

    public function cancelOrder(int $id): void
    {
        abort_unless(auth('admin')->check(), 403);
        MerchantOrder::query()->findOrFail($id);
        $this->element_id = $id;
        $this->emit('openModal', '#cancelOrder');
    }

    public function destroy(CancelOrderAction $cancelOrderAction): void
    {
        abort_unless(auth('admin')->check(), 403);
        if (!$this->element_id) {
            return;
        }

        $cancelled = DB::transaction(function () use ($cancelOrderAction) {
            $order = MerchantOrder::query()->lockForUpdate()->findOrFail($this->element_id);
            if (!in_array($order->status, [
                MerchantOrderStatuses::PENDING,
                MerchantOrderStatuses::UPCOMING,
                MerchantOrderStatuses::CURRENT,
            ], true)) {
                return false;
            }

            $cancelOrderAction->handle($order->id);
            app(LogOrderHistoryAction::class)->handle(
                $order->id,
                MerchantOrderHistoryStatuses::USER_CANCELLED->value,
                'Admin cancelled order via web',
                auth('admin')->user()
            );

            return true;
        });

        $this->element_id = null;
        $this->emit('closeModal', '#cancelOrder');
        $this->emit('refreshDatatable');
        $this->emit('refreshOrder');
        $this->alert(
            $cancelled ? 'success' : 'warning',
            $cancelled
                ? __('marketplace::home.order_cancelled_successfully')
                : __('marketplace::home.order_status_changed_try_again')
        );
    }

    public function render()
    {
        return view('marketplace::livewire.order.modal.cancel');
    }
}
