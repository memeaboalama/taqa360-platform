<?php

namespace Application\Admin\Livewire\SalesAgent\Table;

use App\Support\Enum\StatusEnum;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Collection;
use Livewire\Component;

class Filter extends Component
{
    public $status = null;
    public $network_status = null;
    public $sales_agent_id = null;
    public $sales_agent_name = null;
    public $sales_agent_phone = null;
    public Collection $statuses;
    public array $filters = [];

    protected $listeners = [
        'refreshSalesAgentsFilter',
        'removeSalesAgentFilter',
        'removeStatusFilter',
        'removeNetworkStatusFilter'
    ];

    public function mount()
    {
        $this->statuses = $this->filteredStatuses();
    }

    public function refreshSalesAgentsFilter(SalesAgent $salesAgent): void
    {
        $this->sales_agent_id = $salesAgent->id;
        $this->sales_agent_name = $salesAgent->name ?? null;
        $this->sales_agent_phone = $salesAgent->phone ?? null;
        $this->filters['sales_agent_id'] =  $this->sales_agent_id;
        $this->emit('refreshSalesAgents', $this->filters);
    }

    public function removeSalesAgentFilter(): void
    {
        $this->sales_agent_id = null;
        $this->sales_agent_name = null;
        $this->sales_agent_phone = null;
        $this->filters['sales_agent_id'] = null;
        $this->emit('resetSearch');
        $this->emit('refreshSalesAgents', $this->filters);
    }


    public function filterStatus(): void
    {
        $this->statuses = $this->filteredStatuses();
        $this->filters['status'] =  $this->status;
        $this->emit('refreshSalesAgents', $this->filters);
    }

    public function removeStatusFilter(): void
    {
        $this->status = null;
        $this->statuses = $this->filteredStatuses();
        $this->filters['status'] = null;
        $this->emit('refreshSalesAgents', $this->filters);
    }

    private function filteredStatuses(): Collection
    {
        return collect(StatusEnum::cases())
            ->reject(fn (StatusEnum $status) => $status === StatusEnum::DRAFT)
            ->values();
    }


    public function render()
    {
        return view('admin::livewire.salesagent.table.filter');
    }
}
