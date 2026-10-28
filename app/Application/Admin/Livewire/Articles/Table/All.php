<?php

namespace Application\Admin\Livewire\Articles\Table;

use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Repositories\DistractRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected DistractRepository $distractRepository;

    public function __construct()
    {
        $this->distractRepository = new DistractRepository(new Distract());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->distractRepository->builder();
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

            Column::make('City', 'city_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->city?->title;
                })
                ->searchable(),


            Column::make('status', 'status')
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
                    return view('admin::livewire.district.table.action')->withRow($row);
                })
        ];
    }
}
