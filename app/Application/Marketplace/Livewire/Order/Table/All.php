<?php

namespace Application\Marketplace\Livewire\Order\Table;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Carbon\Carbon;
use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    public $merchantId;
    protected MerchantOrderRepository $orderRepository;

    protected $model = MerchantOrder::class;
    public $listeners = ['refreshOrders'];

    public function __construct()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        parent::__construct();
    }

    public function mountComponentUtilities()
    {
        $status = request()->segment(count(request()->segments()));
        if ($status != "orders")
        {
            $this->filters['status'] = $status;
        }
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setSearchStatus(false);
    }

    public function refreshOrders(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('prepareMerchantOrderExport', $this->filters);
        $this->emit('refreshDatatable');
    }

    public function builder(): Builder
    {
        $this->filters['merchant_id'] = $this->merchantId;
        return $this->orderRepository->filterOrders($this->filters, ['user']);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.userName'), 'user_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user->name;
                })
                ->searchable(),

            Column::make(__('marketplace::home.Branch Name'), 'merchant_branch_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->branch?->title;
                })
                ->searchable(),

            Column::make(__('marketplace::home.Total Price'), 'total_after_vat')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) .' '. __('marketplace::home.SAR');
                })
                ->searchable(),


            Column::make(__('marketplace::home.status'), 'status')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo '<span class="badge ' . $row->status?->color() . '">' . $row->status?->trans() . '</span>';
                })
                ->searchable(),


            Column::make(__('marketplace::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y-m-d | h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('marketplace::livewire.order.table.action')->withRow($row);
                })
        ];
    }
}
