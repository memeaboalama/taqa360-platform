<?php

namespace Application\Admin\Livewire\Finance\Reports\Order\Driver\Table;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    protected $model = MerchantOrder::class;
    protected MerchantOrderRepository $orderRepository;
    public $listeners = ['refreshDriverOrderReport'];

    public function __construct()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        parent::__construct();
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchStatus(false);
    }

    public function refreshDriverOrderReport(array $filters = [])
    {
        $this->filters = $filters;
        $this->setBuilder($this->builder());
        $this->emit('prepareDriverOrderExport', $this->filters);
        $this->emit('prepareDriverOrderPdf', $this->filters);
        $this->emit('refreshDatatable');
    }


    public function builder(): Builder
    {
        return $this->orderRepository->filterOrders($this->filters,
            ['user', 'merchant', 'branch', 'driver'],
            ['id', 'code', 'total_after_vat']);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.code'), 'code')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.userName'), 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user?->name;
                })
                ->eagerLoadRelations()
                ->searchable(),

            Column::make(__('admin::home.Merchant'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->merchant?->company_name;
                })
                ->eagerLoadRelations()
                ->searchable(),


            Column::make(__('admin::home.Branch Name'), 'merchant_branch_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->branch?->title;
                })
                ->eagerLoadRelations()
                ->searchable(),

            Column::make(__('admin::home.Driver Name'), 'driver_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if($row->driver)
                        echo '<a href="'.route('admin:drivers.show', $row->driver->id).'"><span class="badge bg-info">'.$row->driver->name.'</span></a>';
                    else
                        echo '<span class="badge bg-warning">'. __('admin::home.Not Assigned') .'</span>';
                })
                ->eagerLoadRelations()
                ->searchable(),

            Column::make(__('admin::home.Driver Phone'), 'driver_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->driver?->phone;
                })
                ->searchable(),


            Column::make(__('admin::home.totalOrder'), 'total_after_vat')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) . ' SAR';
                })
                ->footer(function($rows) {
                    return __('admin::home.totalOrder') .' : '. $rows->sum('total_after_vat');
                })
                ->searchable(),

            Column::make(__('admin::home.delivery_fee'), 'delivery_fee')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) . ' SAR';
                })
                ->footer(function($rows) {
                    return __('admin::home.totalOrder') .' : '. $rows->sum('delivery_fee');
                })
                ->searchable(),

            Column::make(__('admin::home.commission percentage'), 'commission_from_driver')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.commission'), 'commission_from_driver')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($row->driverCommission, 2) . ' SAR';
                })

                ->footer(function($rows) {
                    return __('admin::home.totalOrder') .' : '. number_format($rows->sum(function($row) {return $row->driverCommission;}));
                })
                ->searchable(),


            Column::make(__('admin::home.Due to the driver'), 'driver_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($row->dueToDriver, 2) . ' SAR';
                })

                ->footer(function($rows) {
                    return __('admin::home.totalOrder') .' : '. number_format($rows->sum(function($row) {return $row->dueToDriver;}));
                })
                ->searchable(),


            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.Order Status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),

            Column::make(__('admin::home.payment_method'), 'payment_method')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<img width="20px"
                                 src="'.asset("images/paymentMethods/").'/'.$row?->payment_method.'.png"
                                 alt="'.$row?->payment_method.'"
                            /><span class="mx-2">'.$row->payment_method.'</span>';
                })
                ->searchable(),

            Column::make(__('admin::home.Transaction ID'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->payment?->payment_id;
                })
                ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.order.table.action')->withRow($row);
                })
        ];
    }
}
