<?php

namespace Application\Admin\Livewire\Brand\Model\Table;

use App\Domain\AppBase\Models\Car\Model;
use App\Domain\AppBase\Repositories\ModelRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected ModelRepository $modelRepository;

    public function __construct()
    {
        $this->modelRepository = new ModelRepository(new Model());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->modelRepository->builder();
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

            Column::make('brand', 'brand_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->brand?->title;
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
                    return view('admin::livewire.brand.model.table.action')->withRow($row);
                })
        ];
    }
}
