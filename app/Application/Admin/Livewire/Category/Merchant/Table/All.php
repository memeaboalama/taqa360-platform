<?php

namespace Application\Admin\Livewire\Category\Merchant\Table;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use Carbon\Carbon;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Builder;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    protected MerchantCategoryRepository $merchantCategoryRepository;

    public function __construct()
    {
        $this->merchantCategoryRepository = new MerchantCategoryRepository(new MerchantCategory());
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->merchantCategoryRepository->builder();
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

            Column::make('merchant', 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->merchant?->company_name;
                })
                ->searchable(),

            Column::make('priority', 'priority')
                ->sortable()
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
                    return view('admin::livewire.category.merchant.table.action')->withRow($row);
                })
        ];
    }
}
