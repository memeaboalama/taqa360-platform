<?php

namespace Application\Admin\Livewire\Catalog\CarType\Table;

use App\Domain\AppBase\Models\Car\CarsType;
use App\Domain\AppBase\Repositories\CarTypeRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected CarTypeRepository $carTypeRepository;

    public function __construct()
    {
        $this->carTypeRepository = new CarTypeRepository(new CarsType());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->carTypeRepository->builder(order: 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Title'), 'title')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->getTranslation('title', app()->getLocale()) ?: $row->getTranslation('title', 'en');
                }),

            Column::make(__('admin::lang.Key'), 'key')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Elm Id'), 'elm_id')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Status'), 'is_active')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        echo '<span class="badge bg-success">' . __('admin::lang.Active') . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __('admin::lang.Inactive') . '</span>';
                    }
                })
                ->searchable(),

            Column::make(__('admin::lang.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.catalog.car-type.table.action')->withRow($row);
                })
        ];
    }
}
