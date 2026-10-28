<?php

namespace Application\Admin\Livewire\Brand\Brand\Table;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Repositories\BrandRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected BrandRepository $brandRepository;
    public function __construct()
    {
        $this->brandRepository = new BrandRepository(new Brand());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->brandRepository->builder()->orderByDesc('id');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Title'), 'title')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.code'), 'code')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Type'), 'type')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.brand.brand.table.action')->withRow($row);
                })
        ];
    }
}
