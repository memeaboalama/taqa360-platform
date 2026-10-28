<?php

namespace Application\Admin\Livewire\Towing\City\Table;

use App\Domain\Towing\Models\TowingCity;
use App\Domain\Towing\Repositories\TowingCityRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected TowingCityRepository $towingCityRepository;

    public function __construct()
    {
        $this->towingCityRepository = new TowingCityRepository(new TowingCity());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return $this->towingCityRepository->builder();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make('title', 'title')
                ->sortable()
                ->searchable(),

            Column::make('priority', 'priority')
                ->sortable()
                ->searchable(),


            Column::make('can pickup', 'can_pickup')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value->value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make('can dropoff', 'can_dropoff')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        echo '<span class="badge bg-success">' . __("marketplace::lang.$value->value") . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __("marketplace::lang.$value->value") . '</span>';
                    }
                })
                ->searchable(),

            Column::make('Date', 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make('actions', 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.towing.city.table.action')->withRow($row);
                })
        ];
    }
}
