<?php

namespace Application\Admin\Livewire\Category\Main\Table;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\AppBase\Repositories\MainCategoryRepository;
use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;
use Carbon\Carbon;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected MainCategoryRepository $mainCategoryRepository;
    public function __construct()
    {
        $this->mainCategoryRepository = new MainCategoryRepository(new MainCategory());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
        $this->setDefaultSort('id', 'desc');
    }

    public function builder(): Builder
    {
        return $this->mainCategoryRepository->builder()->reorder();
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

            Column::make(__('admin::home.Type'), 'type')
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
                    return view('admin::livewire.category.main.table.action')->withRow($row);
                })
        ];
    }
}
