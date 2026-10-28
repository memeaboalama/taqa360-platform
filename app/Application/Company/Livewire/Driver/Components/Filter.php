<?php

namespace Application\Company\Livewire\Driver\Components;

use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $network_status = null;
    public $driver_id = null;
    public $driver_name = null;
    public $driver_phone = null;
    public Collection $statuses;
    public Collection $network_statuses;
    public array $filters = [];

    protected $listeners = [
        'refreshDriverFilter',
        'removeDriverFilter',
        'removeStatusFilter',
        'removeNetworkStatusFilter'
    ];

    public function mount()
    {
        $this->statuses = collect(StatusEnum::cases());
        $this->network_statuses = collect(NetworkStatusEnum::cases());
    }

    public function refreshDriverFilter(Driver $driver): void
    {
        $this->driver_id = $driver->id;
        $this->driver_name = $driver->name ?? null;
        $this->driver_phone = $driver->phone ?? null;
        $this->filters['driver_id'] =  $this->driver_id;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function removeDriverFilter(): void
    {
        $this->driver_id = null;
        $this->driver_name = null;
        $this->driver_phone = null;
        $this->filters['driver_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshDrivers', $this->filters);
    }


    public function filterStatus(): void
    {
        $this->statuses = collect(StatusEnum::cases());
        $this->filters['status'] =  $this->status;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = collect(StatusEnum::cases());
        $this->filters['status'] = null;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function filterNetworkStatus(): void
    {
        $this->filters['network_status'] =  $this->network_status;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function removeNetworkStatusFilter(): void
    {
        $this->network_status = null;
        $this->network_statuses = collect(NetworkStatusEnum::cases());
        $this->filters['network_status'] = null;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function render()
    {
        return view('company::livewire.driver.components.filter');
    }
}
