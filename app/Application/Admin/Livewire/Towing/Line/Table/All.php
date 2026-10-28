<?php

namespace Application\Admin\Livewire\Towing\Line\Table;

use App\Domain\Towing\Models\TowingLine;
use App\Domain\Towing\Repositories\TowingLineRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected TowingLineRepository $towingLineRepository;

    public function __construct()
    {
        $this->towingLineRepository = new TowingLineRepository(new TowingLine());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return $this->towingLineRepository->builder();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

//            Column::make('title', 'title')
//                ->sortable()
//                ->searchable(),

            Column::make('from towing city', 'from_towing_city_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->from_city->title;
                })
                ->searchable(),

            Column::make('to towing city', 'to_towing_city_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->to_city->title;
                })
                ->searchable(),

            Column::make('Price', 'price')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->price . ' SAR';
                })
                ->searchable(),

            Column::make('towing type', 'towing_type_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->type->title;
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
                    return view('admin::livewire.towing.line.table.action')->withRow($row);
                })
        ];
    }
}
