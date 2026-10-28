<?php

namespace Application\Company\Livewire\Order\Table;


use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\Provider\Models\ProviderOrder;
use App\Support\Enum\VehicleType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    public $companyId;
    protected $model = MerchantOrder::class;
    protected MerchantOrderRepository $orderRepository;
    public $listeners = ['refreshOrders'];

    public function __construct()
    {

        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        parent::__construct();
    }

    public function mountComponentUtilities()
    {
        $this->companyId = resolve('_InitialCompany');
    }

    public function refreshOrders(array $filters = [])
    {
        $this->filters = $filters;
        $this->setBuilder($this->builder());
        $this->emit('prepareDriverOrderExport', $this->filters);
        $this->emit('prepareDriverOrderPdf', $this->filters);
        $this->emit('refreshDatatable');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function builder(): Builder
    {
        return $this->orderRepository->getCompanyDriversOrders($this->companyId, $this->filters);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('company::home.CLIENT NAME'), 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user?->name;
                })
                ->searchable(),


            Column::make(__('company::home.Driver NAME'), 'driver_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->driver?->name;
                })
                ->searchable(),

            Column::make(__('company::home.PAYMENT TYPE'), 'payment_type')
                ->sortable()
                ->searchable(),


            Column::make(__('company::home.TOTAL AFTER VAT'), 'total_after_vat')
                ->sortable()
                ->footer(function($rows) {
                    return __('company::home.total') .' : '. $rows->sum('total_after_vat');
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

            Column::make(__('company::home.STATUS'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),

            Column::make(__('company::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->translatedFormat('j F ,Y');
                })
                ->sortable()
                ->searchable()
        ];
    }
}
