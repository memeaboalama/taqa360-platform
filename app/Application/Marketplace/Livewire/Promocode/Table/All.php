<?php

namespace Application\Marketplace\Livewire\Promocode\Table;

use App\Domain\AppBase\Models\Promocode;
use App\Domain\AppBase\Repositories\PromocodeRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $merchantId;

    protected PromocodeRepository $promocodeRepository;
    public function __construct()
    {
        $this->promocodeRepository = new PromocodeRepository(new Promocode());
        parent::__construct();
    }

    public $listeners = ['refreshMerchantPromocode'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function mount(): void
    {
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function builder(): Builder
    {
        return $this->promocodeRepository->builder(condition:['merchant_id' => $this->merchantId], order:'desc');
    }
    public function refreshMerchantPromocode()
    {
        $this->emit('refreshDatatable');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Code'), 'code')
                ->sortable()
                ->searchable(),
            Column::make( __('admin::home.Usage Lift'), 'usages_left')
                ->sortable()
                ->searchable(),

            Column::make( __('admin::home.limit per user'), 'limit_per_user')
                ->sortable()
                ->searchable(),    
                
            Column::make( __('admin::home.Min Order Value'), 'min_order_value')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2);
                })
                ->searchable(),

            Column::make( __('admin::home.Type'), 'type')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo __('marketplace::lang.' . $value);
                })
                ->searchable(),

            Column::make(__('admin::home.Discount Value'), 'discount_value')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2);
                })
                ->searchable(),

            Column::make(__('admin::home.Max Discount'), 'max_discount')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2);
                })
                ->searchable(),

            Column::make(__('admin::home.Sales Agent'), 'sales_agent_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo  $row->sales_agent?->name;
                })
                ->searchable(),

            Column::make(__('admin::home.Discount type'), 'type_discount')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo __('marketplace::lang.' . $value);
                })
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

            Column::make(__('admin::home.Expire At'), 'expired_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

    
            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('marketplace::livewire.promocode.table.action')->withRow($row);
                })
        ];
    }
}
