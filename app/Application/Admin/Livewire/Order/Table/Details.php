<?php

namespace Application\Admin\Livewire\Order\Table;

use App\Domain\MarketPlace\Actions\Order\OrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Application\Marketplace\Livewire\Order\Table\Read;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Details extends Read
{
    protected function actionUser()
    {
        return auth('admin')->user();
    }

    protected function merchantOrderQuery(): Builder
    {
        abort_unless(auth('admin')->check(), 403);

        return MerchantOrder::query();
    }

    public function cancelOrder(): void
    {
        $this->merchantOrderQuery()->whereKey($this->order->id)->firstOrFail();
        $this->emit('cancelOrder', $this->order->id);
    }

    public function saveAdminNote(): void
    {
        $validated = $this->validate([
            'adminNoteDraft' => ['nullable', 'string', 'max:1500'],
        ]);
        $this->merchantOrderQuery()->whereKey($this->order->id)->firstOrFail();

        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $orderAction = new OrderAction($orderRepo);
        $order = DB::transaction(
            fn () => $orderAction->updateAdminNote($this->order->id, $validated['adminNoteDraft'] ?? null)
        );

        if (!$order) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->order->admin_note = $validated['adminNoteDraft'] ?? null;
        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    public function render()
    {
        $this->merchantOrderQuery()->whereKey($this->order->id)->firstOrFail();

        // Reload the model after cancellation in the child modal.
        $this->order->refresh();

        return parent::render()->with([
            'adminContext' => true,
            'ordersUrl' => route('admin:merchant.orders.index'),
            'financialReportUrl' => route('admin:finance.reports.orders.merchant.index', [
                'code' => $this->order->code,
            ]),
        ]);
    }
}
