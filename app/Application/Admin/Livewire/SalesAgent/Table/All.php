<?php

namespace Application\Admin\Livewire\SalesAgent\Table;

use App\Domain\SalesAgent\Repositories\SalesAgentRepository;
use Carbon\Carbon;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    protected SalesAgentRepository $salesAgentRepository;
    public $listeners = ['refreshSalesAgents'];
    public function __construct()
    {
        $this->salesAgentRepository = new SalesAgentRepository(new SalesAgent());
        parent::__construct();
    }

    public function refreshSalesAgents(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->salesAgentRepository->filter($this->filters)
            ->where('is_complete', 1);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Email'), 'email')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Phone Number'), 'phone')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->PhoneNumber;
                })
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value->value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.files'), 'id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->filesCompleted()) {
                        echo '<span class="badge bg-success">'.__('admin::home.completed').'</span>';
                    } else {
                        echo '<span class="badge bg-danger">'.__('admin::home.Incomplete').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.salesAgentStatus'), 'is_complete')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        echo '<span class="badge bg-success">'.__('admin::home.approved').'</span>';
                    } else {
                        echo '<span class="badge bg-warning">'.__('admin::home.pending').'</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.salesagent.table.action')->withRow($row);
                })

        ];
    }
}
