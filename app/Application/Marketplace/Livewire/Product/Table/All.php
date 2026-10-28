<?php

namespace Application\Marketplace\Livewire\Product\Table;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Carbon\Carbon;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public $filters = [];
    public $merchantId;
    protected ItemRepository $itemRepository;
    public $listeners = [
        'refreshProducts',
        'refreshDatatable' => '$refresh',
    ];

    public function __construct()
    {
        $this->itemRepository = new ItemRepository(new Item());
        parent::__construct();
    }

    public function refreshProducts(array $filters = [])
    {
        $this->filters = $filters;
        $this->setBuilder($this->builder());
        $this->emit('refreshDatatable');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }
    public function mount()
    {
        $this->merchantId = resolve('_InitialMerchant');
    }
    public function builder(): Builder
    {
        if (!isset($this->filters['merchant_id']) || $this->filters['merchant_id'] === null) {
            $this->filters['merchant_id'] = $this->merchantId;
        }
        return $this->itemRepository->filter($this->filters)
            ->with(['merchant', 'mainCategory', 'subCategory', 'childCategory']);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.Title'), 'title')
                ->sortable()
                ->searchable(),

            // Column::make('Merchant', 'merchant.name')
            //     ->sortable()
            //     ->searchable(),

            Column::make(__('admin::main.fields.category'), 'main_category_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return $row->mainCategory?->title;
                })
                ->searchable(),

            Column::make(__('admin::main.fields.subcategory'), 'sub_category_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return $row->subCategory?->title;
                })
                ->searchable(),

            Column::make(__('admin::main.fields.child_category'), 'child_category_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return $row->childCategory?->title;
                })
                ->searchable(),

            Column::make(__('admin::main.fields.price'), 'default_price')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return \App\Domain\AppBase\Models\Setting\Currency::format((float) $value);
                })
                ->searchable(),

            Column::make(__('admin::main.fields.stock'), 'stock')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value === null) {
                        return '<span class="badge bg-secondary">' . __('admin::main.fields.stock_not_tracked') . '</span>';
                    }

                    $badge = ((int) $value) > 0 ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $badge . '">' . (int) $value . '</span>';
                })
                ->html()
                ->searchable(),

            Column::make(__('marketplace::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value->value == 'active') {
                        return '<span class="badge bg-success">' . __("admin::main.status.active") . '</span>';
                    } else {
                        return '<span class="badge bg-danger">' . __("admin::main.status.inactive") . '</span>';
                    }
                })
                ->html()
                ->searchable(),

            Column::make(__('marketplace::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    return Carbon::parse($value)->translatedFormat('j F Y');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('marketplace::livewire.product.table.action')->withRow($row);
                })
        ];
    }
}
