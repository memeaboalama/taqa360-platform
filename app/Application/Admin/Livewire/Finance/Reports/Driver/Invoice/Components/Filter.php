<?php

namespace Application\Admin\Livewire\Finance\Reports\Driver\Invoice\Components;

use Domain\Driver\Models\Driver;
use Livewire\Component;

class Filter extends Component
{
    public $driver_id = null;
    public $driver_name = null;
    public $driver_phone = null;
    public $merchant_id = null;
    public $merchant_name = null;
    public array $filters = [];

    protected $listeners = [
        'refreshDriverFilter',
        'removeDriverFilter',
        'refreshMerchantInvoiceFilter',
        'removeMerchantInvoiceFilter',
    ];

    public function refreshDriverFilter(Driver $driver): void
    {
        $this->driver_id = $driver->id;
        $this->driver_name = $driver->name ?? null;
        $this->driver_phone = $driver->phone ?? null;
        $this->filters['driver_id'] = $this->driver_id;
        $this->emit('refreshDriverInvoice', $this->filters);
    }

    public function removeDriverFilter(): void
    {
        $this->driver_id = null;
        $this->driver_name = null;
        $this->driver_phone = null;
        $this->filters['driver_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshDriverInvoice', $this->filters);
    }

    public function refreshMerchantInvoiceFilter(int $merchantId = null, string $company_name = ''): void
    {
        $this->merchant_id = $merchantId;
        $this->merchant_name = $company_name ?? null;
        $this->filters['merchant_id'] = $this->merchant_id;
        $this->emit('refreshDriverInvoice', $this->filters);
    }

    public function removeMerchantInvoiceFilter(): void
    {
        $this->merchant_id = null;
        $this->merchant_name = null;
        $this->filters['merchant_id'] = null;
        $this->emit('resetMerchantSearch');
        $this->emit('refreshDriverInvoice', $this->filters);
    }

    public function render()
    {
        return view('admin::livewire.finance.reports.driver.invoice.components.filter');
    }
}
