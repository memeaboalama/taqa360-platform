<?php

namespace Application\Admin\Livewire\Catalog\Brand\Table;

use App\Domain\MarketPlace\Repositories\BrandRepository;
use App\Domain\MarketPlace\Models\Brand;
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
        return $this->brandRepository->builder(order: 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::lang.Name'), 'name')
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
                    return view('admin::livewire.catalog.brand.table.action')->withRow($row);
                })
        ];
    }
}
