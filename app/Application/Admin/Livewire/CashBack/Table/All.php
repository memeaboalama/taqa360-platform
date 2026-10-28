<?php

namespace Application\Admin\Livewire\CashBack\Table;

use App\Domain\AppBase\Models\CashBack;
use App\Domain\AppBase\Repositories\CashBackRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;

    public $listeners = ['selectmerchants'];
    protected CashBackRepository $cashBackRepository;
    public function __construct()
    {
        $this->cashBackRepository = new CashBackRepository(new CashBack());
        parent::__construct();
    }
    public function selectmerchants($id){
        $this->emit('openModal', '#selectmerchants');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->cashBackRepository->builder(order:'desc')->whereNull('merchant_id');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Name'), 'name')
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
                ->searchable(),

                // Column::make( __('admin::home.servicer type'), 'service_type_id')
                // ->sortable()
                // ->searchable(),

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

                Column::make(__('admin::home.Promocode Period'), 'period')
                ->sortable()
                ->searchable(),

                Column::make(__('admin::home.is_custom'), 'is_custom')
                ->hideIf(true),
                
            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.cashback.table.action')->withRow($row);
                })
        ];
    }
}
