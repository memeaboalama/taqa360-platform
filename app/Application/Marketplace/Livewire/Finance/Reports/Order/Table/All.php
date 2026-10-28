<?php

namespace Application\Marketplace\Livewire\Finance\Reports\Order\Table;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;
use Illuminate\Support\Facades\Log;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    public $merchantId;
    protected $model = MerchantOrder::class;
    protected MerchantOrderRepository $orderRepository;
    public $listeners = ['refreshOrderReportMerchant'];

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

    protected function getMerchantId()
    {
        if (app()->bound('_InitialMerchant') && app('_InitialMerchant')) {
            return app('_InitialMerchant');
        }
        if (\Illuminate\Support\Facades\Auth::guard('merchant')->check()) {
            return \Illuminate\Support\Facades\Auth::guard('merchant')->user()->id;
        }
        if (\Illuminate\Support\Facades\Auth::guard('merchant_employee')->check()) {
            return \Illuminate\Support\Facades\Auth::guard('merchant_employee')->user()->merchant_id;
        }
        return null;
    }

    public function mountComponentUtilities()
    {
        $this->merchantId = $this->getMerchantId();
        $this->filters['merchant_id'] = $this->merchantId;
        $this->emit('prepareMerchantOrderExportMark', $this->filters);
        $this->emit('prepareMerchantOrderPdf', $this->filters);
    }

    public function refreshOrderReportMerchant(array $filters = [])
    {
        $this->filters = $filters;
        $this->merchantId = $this->getMerchantId();
        $this->filters['merchant_id'] = $this->merchantId;
        $this->builder();
        try{

            Log::debug('filters----->'.$this->filters[0]);
        }
        catch(\Exception $e)
        {

        }
        $this->emit('prepareMerchantOrderExportMark', $this->filters);
        $this->emit('prepareMerchantOrderPdf', $this->filters);
        $this->emit('refreshDatatable');
    }


    public function builder(): Builder
    {
        $this->merchantId = $this->getMerchantId();
        $this->filters['merchant_id'] = $this->merchantId;
        return $this->orderRepository->filterOrders($this->filters,
            ['user', 'merchant', 'branch', 'payment', 'merchant.packages'],
            ['*']);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true),

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



            Column::make(__('admin::home.Branch Name'), 'merchant_branch_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->branch?->title;
                })
                ->eagerLoadRelations()
                ->searchable(),


            Column::make(__('admin::home.Order Price'), 'subtotal')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2);
                })
                ->footer(function($rows) {
                    return __('admin::home.totalOrder') .' : '. number_format($rows->sum('subtotal'), 2) .' '. __('admin::home.SARR');
                })
                ->searchable(),

            Column::make(__('admin::home.discount'), 'discount')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2) ;
                })
                ->footer(function($rows) {
                    return __('admin::home.discount') .' : '. $rows->sum(function($row) { return (float) $row->discount; }).' '. __('admin::home.SARR');
                })
                ->searchable(),   
            Column::make(__('admin::home.Total after discount'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format((float) $row->subtotal - (float) $row->discount, 2) ;
                })
                ->footer(function($rows) {
                    return __('admin::home.Total after discount') .' : '. $rows->sum(function($row) {return (float) $row->subtotal - (float) $row->discount;}). ' ' . __('admin::home.SARR');
                })
                ->searchable(),           

                Column::make(__('admin::home.commission percentage'), 'commission_rate')
                ->sortable()
                ->searchable(),
    
                Column::make(__('admin::home.commission'), 'commission_amount')
                    ->sortable()
                    ->searchable()
    
                ->footer(function ($rows) {
                    return __('admin::home.commission') . ' : ' . number_format($rows->sum(function ($row) {
                        return (float) $row->commission_amount;
                    })) . ' ' . __('admin::home.SARR');
                })
                    ->searchable(),

            Column::make(__('admin::home.Due to the merchant'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($row->dueToMerchantFromSub, 2);
                })
                ->footer(function($rows) {
                    return __('admin::home.Due to the merchant') .' : '. number_format($rows->sum(function($row) {return $row->dueToMerchantFromSub;})). ' ' . __('admin::home.SARR');
                })
                ->searchable(),


            Column::make(__('admin::home.date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('Y-m-d | h:i');
                })
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.status'), 'status')
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


            Column::make(__('marketplace::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('marketplace::livewire.order.table.action')->withRow($row);
                })
        ];
    }
}
