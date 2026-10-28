<?php

namespace Application\Admin\Livewire\Finance\Reports\Driver\Invoice\Table;


use App\Application\Marketplace\Enums\MerchantOrderStatuses;
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
    public $listeners = ['refreshDriverInvoice'];

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

    public function refreshDriverInvoice(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }


    public function builder(): Builder
    {
        return $this->orderRepository->filterOrders($this->filters,
            ['user', 'merchant', 'branch', 'driver'],
            ['id', 'code', 'total_after_vat'], ['status' => MerchantOrderStatuses::PREVIOUS->value]);
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


            Column::make(__('admin::home.Merchant'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->merchant?->company_name;
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


            Column::make(__('admin::home.delivery_fee'), 'delivery_fee')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) . ' '. __('admin::home.SAR');
                })
                ->footer(function($rows) {
                    return __('admin::home.totalOrder') .' : '. $rows->sum('delivery_fee');
                })
                ->searchable(),



            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y m d - h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),


            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.finance.reports.driver.invoice.table.action')->withRow($row);
                })
        ];
    }
}
