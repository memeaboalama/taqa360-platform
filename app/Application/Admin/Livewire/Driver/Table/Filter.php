<?php

namespace Application\Admin\Livewire\Driver\Table;

use App\Domain\Admin\Actions\Dashboard\Company\FetchCompanyAction;
use App\Domain\Admin\Actions\Dashboard\Company\FetchSingleCompanyAction;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $network_status = null;
    public $driver_status = null;
    public $driver_id = null;
    public $driver_name = null;
    public $driver_phone = null;
    public $company_name = null;
    public $company_id = null;
    public Collection $statuses;
    public Collection $network_statuses;
    public $driver_statuses = [];
    public $companies;
    public array $filters = [];

    protected $listeners = [
        'refreshDriverFilter',
        'removeDriverFilter',
        'removeCompanyFilter',
        'removeStatusFilter',
        'removeNetworkStatusFilter',
        'removeDriverStatusFilter'
    ];

    public function mount(FetchCompanyAction $fetchCompanyAction)
    {
        $this->statuses = $this->filterableStatuses()->map(fn($status) => $status->value)->values();
        $this->network_statuses = collect(NetworkStatusEnum::cases())->map(fn($status) => $status->value)->values();
        $this->driver_statuses = ['approved', 'pending', 'rejected'];
        $this->companies = $fetchCompanyAction->handle();

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

    public function filterCompany(FetchSingleCompanyAction $fetchSingleCompanyAction): void
    {
        if ($this->company_id == "")
        {
            $this->company_id = null;
        }

        $this->company_name = null;
        if ($this->company_id !== null) {
            $this->company_name = $fetchSingleCompanyAction
                ->handle($this->company_id)
                ?->company_name ?? null;
        }

        $this->filters['company_id'] =  $this->company_id;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function removeCompanyFilter(): void
    {
        $this->company_id = null;
        $this->company_name = null;
        $this->filters['company_id'] = null;
        $this->emit('refreshDrivers', $this->filters);
    }


    public function filterStatus(): void
    {
        if ($this->status == "")
        {
            $this->status = null;
        }
        $this->statuses = $this->filterableStatuses()->map(fn($status) => $status->value)->values();
        $this->filters['status'] =  $this->status;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = $this->filterableStatuses()->map(fn($status) => $status->value)->values();
        $this->filters['status'] = null;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function filterDriverStatus(): void
    {
        if ($this->driver_status == "")
        {
            $this->driver_status = null;
        }
        $this->filters['driver_status'] =  $this->driver_status;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function removeDriverStatusFilter(): void
    {
        $this->driver_status = null;
        $this->filters['driver_status'] = null;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function filterNetworkStatus(): void
    {
        if ($this->network_status == "")
        {
            $this->network_status = null;
        }
        $this->filters['network_status'] =  $this->network_status;
        $this->emit('refreshDrivers', $this->filters);
    }

    public function removeNetworkStatusFilter(): void
    {
        $this->network_status = null;
        $this->network_statuses = collect(NetworkStatusEnum::cases())->map(fn($status) => $status->value)->values();
        $this->filters['network_status'] = null;
        $this->emit('refreshDrivers', $this->filters);
    }

    private function filterableStatuses(): Collection
    {
        return collect(StatusEnum::cases())
            ->reject(fn($status) => $status === StatusEnum::DRAFT)
            ->values();
    }

    public function render()
    {
        return view('admin::livewire.driver.table.filter');
    }
}
