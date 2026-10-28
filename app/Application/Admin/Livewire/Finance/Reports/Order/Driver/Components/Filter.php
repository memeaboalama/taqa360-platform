<?php

namespace Application\Admin\Livewire\Finance\Reports\Order\Driver\Components;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $order_status = null;
    public $driver_id = null;
    public $driver_name = null;
    public $driver_phone = null;
    public array $filters = [];

    protected $listeners = [
        'refreshDriverFilter',
        'removeDriverFilter',
        'removeOrderStatusFilter'
    ];

    public function getOrderStatusesProperty(): Collection
    {
        return collect(MerchantOrderStatuses::cases());
    }

    public function refreshDriverFilter(Driver $driver): void
    {
        $this->driver_id = $driver->id;
        $this->driver_name = $driver->name ?? null;
        $this->driver_phone = $driver->phone ?? null;
        $this->filters['driver_id'] =  $this->driver_id;
        $this->emit('refreshDriverOrderReport', $this->filters);
    }

    public function removeDriverFilter(): void
    {
        $this->driver_id = null;
        $this->driver_name = null;
        $this->driver_phone = null;
        $this->filters['driver_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshDriverOrderReport', $this->filters);
    }


    public function filterOrderStatus(): void
    {
        $this->filters['status'] =  $this->order_status;
        $this->emit('refreshDriverOrderReport', $this->filters);
    }

    public function removeOrderStatusFilter(): void
    {
        $this->order_status = null;
        $this->filters['status'] = null;
        $this->emit('refreshDriverOrderReport', $this->filters);
    }

    public function render()
    {
        return view('admin::livewire.finance.reports.order.driver.components.filter');
    }
}