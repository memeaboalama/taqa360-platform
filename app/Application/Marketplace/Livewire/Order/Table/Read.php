<?php

namespace Application\Marketplace\Livewire\Order\Table;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Read extends Component
{
    use LivewireAlert;

    public $order;
    public $orderUser;
    public $orderServices;
    public $address;

    protected $listeners = ['refreshDatatable' => '$refresh', 'refreshOrder' => '$refresh'];

    public function mount($order, $orderServices, $orderUser, $address)
    {
        $this->order = $order;
        $this->orderServices = $orderServices;
        $this->orderUser = $orderUser;
        $this->address = $address;
    }
    public function markAsReady(): void
    {
        $orderRepo = new \App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository(new \App\Domain\MarketPlace\Models\MerchantOrder());
        $orderRepo->update($this->order->id, [
            'status' => \App\Application\Marketplace\Enums\MerchantOrderStatuses::READY->value
        ]);

        $logHistory = app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class);
        $user = auth()->user() ?? auth('merchant')->user() ?? auth('merchant_employee')->user();
        $logHistory->handle(
            $this->order->id,
            \App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses::MARKED_READY->value,
            'Merchant marked order as ready',
            $user
        );

        $this->order->refresh();
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.order_marked_ready_successfully'));
    }

    public function cancelOrder(): void
    {
        $cancelAction = app(\App\Domain\MarketPlace\Actions\Dashboard\Order\CancelOrderAction::class);
        $cancelAction->handle($this->order->id);

        $logHistory = app(\App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction::class);
        $user = auth()->user() ?? auth('merchant')->user() ?? auth('merchant_employee')->user();
        $logHistory->handle(
            $this->order->id,
            \App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses::USER_CANCELLED->value,
            'Merchant cancelled order via web',
            $user
        );

        $this->order->refresh();
        $this->emit('refreshDatatable');
        $this->alert('success', __('marketplace::home.order_cancelled_successfully'));
    }

    public function render()
    {
        return view('marketplace::livewire.order.table.read', [
            'order' => $this->order,
            'orderServices' => $this->orderServices,
            'orderUser' => $this->orderUser,
            'address' => $this->address,
        ]);
    }
}
