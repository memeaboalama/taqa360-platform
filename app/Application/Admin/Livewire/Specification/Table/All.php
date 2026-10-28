<?php

namespace Application\Admin\Livewire\Specification\Table;

use App\Domain\AppBase\Models\Specification;
use App\Domain\AppBase\Repositories\SpecificationRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected SpecificationRepository $specificationRepository;
    public function __construct()
    {
        $this->specificationRepository = new SpecificationRepository(new Specification());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->specificationRepository->builder()->reorder();
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
            Column::make(__('admin::home.Description'), 'description')
                ->sortable()
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
            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),
            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.specification.table.action')->withRow($row);
                })
        ];
    }
}
