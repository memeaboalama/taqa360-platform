<?php

namespace Application\Admin\Livewire\Catalog\Category\Table;

use App\Domain\MarketPlace\Repositories\Catalog\CategoryRepository;
use Domain\MarketPlace\Models\Catalog\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository(new Category());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->categoryRepository->builder(order: 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::catalog.fields.title'), 'title')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::catalog.fields.slug'), 'slug')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::catalog.fields.is_active'), 'is_active')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        echo '<span class="badge bg-success">' . __('admin::catalog.status.active') . '</span>';
                    } else {
                        echo '<span class="badge bg-danger">' . __('admin::catalog.status.inactive') . '</span>';
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
                    return view('admin::livewire.catalog.category.table.action')->withRow($row);
                })
        ];
    }
}
