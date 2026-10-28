<?php

namespace Application\Admin\Livewire\Educator\Pending\Table;

use Carbon\Carbon;
use Domain\Educator\Models\Educator;
use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    protected EducatorRepository $educatorRepository;
    public $listeners = ['refreshEducators', 'refreshDatatable' => '$refresh'];
    public function __construct()
    {
        $this->educatorRepository = new EducatorRepository(new Educator());
        parent::__construct();
    }

    public function refreshEducators(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('prepareEducatorOrderExport', $this->filters);
        $this->emit('refreshDatatable');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->educatorRepository->filter($this->filters)
            ->where('is_accepted', 0);
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
                    echo $row->phone ? '<a href="tel:' . $row->phone . '">' . $row->phone . '</a>' : '';
                })
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . ($row->status?->trans() ?? '') . '</span>';
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

            Column::make(__('admin::home.Acceptance status'), 'is_accepted')
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
                    return view('admin::livewire.educator.table.action')->withRow($row);
                })

        ];
    }
}

