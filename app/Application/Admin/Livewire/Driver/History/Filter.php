<?php

namespace Application\Admin\Livewire\Driver\History;

use App\Domain\Admin\Actions\Dashboard\Company\FetchCompanyAction;
use App\Domain\Admin\Actions\Dashboard\Company\FetchSingleCompanyAction;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Filter extends Component
{
    public $status = null;
    public $network_status = null;
    public $driver_id = null;
    public $driver_name = null;
    public $driver_phone = null;
    public $company_name = null;
    public $company_id = null;
    public $companies;
    public $date = null;
    public $to_date = null;
    public bool $to_date_visible = false;
    public $date_condition = null;
    public array $filters = [];

    protected $listeners = [
        'refreshDriverFilter',
        'removeDriverHistoryFilter',
        'removeDateFilter',
        'getBetweenDates',
        'removeCompanyHistoryFilter',
    ];

    public function mount(FetchCompanyAction $fetchCompanyAction)
    {
        $this->companies = $fetchCompanyAction->handle();

    }

    public function refreshDriverFilter(Driver $driver): void
    {
        $this->driver_id = $driver->id;
        $this->driver_name = $driver->name ?? null;
        $this->driver_phone = $driver->phone ?? null;
        $this->filters['driver_id'] =  $this->driver_id;
        Log::info($driver);
        $this->emit('refreshDriverHistory', $this->filters);
    }

    public function removeDriverHistoryFilter(): void
    {
        $this->driver_id = null;
        $this->driver_name = null;
        $this->driver_phone = null;
        $this->filters['driver_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshDriverHistory', $this->filters);
    }

    public function filterCompanyHistory(FetchSingleCompanyAction $fetchSingleCompanyAction): void
    {
        if ($this->company_id == "")
        {
            $this->company_id = null;
        }
        $this->company_name = $fetchSingleCompanyAction
            ->handle($this->company_id)
            ?->company_name ?? null;

        $this->filters['company_id'] =  $this->company_id;
        $this->emit('refreshDriverHistory', $this->filters);
    }

    public function removeCompanyHistoryFilter(): void
    {
        $this->company_id = null;
        $this->company_name = null;
        $this->filters['company_id'] = null;
        $this->emit('refreshDriverHistory', $this->filters);
    }
    public function filterDate(): void
    {
        $this->filters['date_condition'] =  $this->date_condition;
        $this->filters['date'] =  $this->date;
        $this->filters['to_date'] =  $this->to_date;
        $this->emit('refreshDriverHistory', $this->filters);
    }

    public function removeDateFilter(): void
    {
        $this->date_condition = "equal";
        $this->date = null;
        $this->to_date = null;
        $this->filters['date_condition'] = null;
        $this->filters['date'] = null;
        $this->filters['to_date'] = null;
        $this->emit('refreshDriverHistory', $this->filters);
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
        return view('admin::livewire.driver.history.filter');
    }
}
