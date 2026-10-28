<?php

namespace Application\Company\Livewire\Order\Components;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Domain\Driver\Models\Driver;
use Livewire\Component;

class Filter extends Component
{
    public $driver_id = null;
    public $driver_name = null;
    public $driver_phone = null;
    public $date = null;
    public $to_date = null;
    public bool $to_date_visible = false;
    public $date_condition = null;
    public array $filters = [];

    protected $listeners = [
        'refreshDriverFilter',
        'removeDriverFilter',
        'removeDateFilter',
        'getBetweenDates'
    ];

    public function mount()
    {
        $this->date_condition = "equal";
    }

    public function refreshDriverFilter(Driver $driver): void
    {
        $this->driver_id = $driver->id;
        $this->driver_name = $driver->name ?? null;
        $this->driver_phone = $driver->phone ?? null;
        $this->filters['driver_id'] =  $this->driver_id;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeDriverFilter(): void
    {
        $this->driver_id = null;
        $this->driver_name = null;
        $this->driver_phone = null;
        $this->filters['driver_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshOrders', $this->filters);
    }

    public function filterDate(): void
    {
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshOrders', $this->filters);
    }

    public function removeDateFilter(): void
    {
        $this->date_condition = "equal";
        $this->date = null;
        $this->to_date = null;
        $this->filters['date_condition'] = null;
        $this->filters['date'] = null;
        $this->filters['to_date'] = null;
        $this->emit('refreshOrders', $this->filters);
    }

    public function getBetweenDates(): void
    {
        if ($this->date_condition == "between")
        {
            $this->to_date_visible = true;
        }else{
            $this->to_date_visible = false;
        }
    }

    public function render()
    {
        return view('company::livewire.order.components.filter');
    }
}
