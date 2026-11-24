<?php

namespace Application\Driver\Livewire\Home;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Livewire\Component;
use Livewire\WithPagination;

class AssignedOrders extends Component
{
    use WithPagination;

    public int $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $this->perPage = (int) request('perPage', 10);
        if (!in_array($this->perPage, [10, 25, 50, 100], true)) {
            $this->perPage = 10;
        }
    }

    public function updatedPerPage($perPage): void
    {
        $this->perPage = in_array((int) $perPage, [10, 25, 50, 100], true)
            ? (int) $perPage
            : 10;
        $this->resetPage();
    }

    public function render()
    {
        $driver = auth('driver')->user();
        abort_unless($driver, 403);

        $recentOrders = MerchantOrder::query()
            ->where('driver_id', $driver->id)
            ->with([
                'user',
                'payment',
                'orders_history' => function ($query) {
                    $query->whereIn('action', [
                        MerchantOrderHistoryStatuses::DRIVER_ACCEPT->value,
                        MerchantOrderHistoryStatuses::MARKED_READY->value,
                        MerchantOrderHistoryStatuses::DRIVER_ASSIGNED_MANUALLY->value,
                    ])->orderByDesc('id');
                },
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('driver::livewire.home.assigned-orders', compact('driver', 'recentOrders'));
    }
}
