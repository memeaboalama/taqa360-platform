<?php

namespace Application\Admin\Livewire\Driver\TableRegestier;

use App\Domain\Admin\Actions\Dashboard\Company\FetchCompanyAction;
use App\Domain\Admin\Actions\Dashboard\Company\FetchSingleCompanyAction;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use App\Domain\Provider\Models\DriversRegisters;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $network_status = null;
    public $driver_id = null;
    public $driver_name = null;
    public $driver_phone = null;
    public $company_name = null;
    public $company_id = null;
    public Collection $statuses;
    public Collection $network_statuses;
    public $companies;
    public array $filters = [];

    protected $listeners = [
        'refreshDriverFilter',
        'removeDriverFilter',
        'removeStatusFilter',
        'removeNetworkStatusFilter'
    ];

    public function mount(FetchCompanyAction $fetchCompanyAction)
    {
        $this->statuses = collect(StatusEnum::cases());
        $this->network_statuses = collect(NetworkStatusEnum::cases());
        $this->companies = $fetchCompanyAction->handle();

    }

    public function refreshDriverFilter(DriversRegisters $driver): void
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
        return view('admin::livewire.driver.tableregestier.filter');
    }
}
