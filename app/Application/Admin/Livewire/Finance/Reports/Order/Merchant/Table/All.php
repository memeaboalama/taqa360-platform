<?php

namespace Application\Admin\Livewire\Finance\Reports\Order\Merchant\Table;

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
    public $listeners = ['refreshOrderReport'];

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

    public function refreshOrderReport(array $filters = [])
    {
        $this->filters = $filters;
        $this->setBuilder($this->builder());
        $this->emit('prepareMerchantOrderExport', $this->filters);
        $this->emit('prepareMerchantOrderPdf', $this->filters);
        $this->emit('refreshDatatable');
    }


    public function builder(): Builder
    {
        return $this->orderRepository->filterOrders(
            $this->filters,
            ['user', 'merchant', 'branch', 'payment', 'merchant.packages', 'promocode'],
            ['id', 'code', 'user_id', 'merchant_id', 'merchant_branch_id', 'total_after_vat', 'created_at', 'status', 'payment_method', 'discount', 'subtotal','cash_back_id']
        );
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

            Column::make(__('admin::home.Order Price'), 'subtotal')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($value, 2);
                })
                ->footer(function ($rows) {
                    return __('admin::home.Order Price') . ' : ' . $rows->sum('subtotal') . ' ' . __('admin::home.SARR');
                })
                ->searchable(),

            Column::make(__('admin::home.code'), 'merchant_id')
                ->hideIf(true),


            Column::make(__('admin::home.Admin Discount'), 'discount')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->promocode?->hasMerchant() ?  number_format(0, 2) : number_format($value, 2);
                })
                ->footer(function ($rows) {
                    return __('admin::home.discount') . ' : ' . $rows->sum('discount') . ' ' . __('admin::home.SARR');
                })
                ->searchable(),

            Column::make(__('admin::home.Merchant Discount'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->promocode?->hasMerchant() ? number_format($value, 2) : number_format(0, 2);
                })
                ->searchable(),

            Column::make(__('admin::home.Total after discount merchants'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    // echo number_format($row->subtotal - $row->discount, 2) ;
                    echo $row->promocode?->hasMerchant() ? number_format($row->subtotal - $row->discount, 2) : number_format($row->subtotal, 2);
                })
                ->footer(function ($rows) {
                    return __('admin::home.Total after discount') . ' : ' . $rows->sum(function ($row) {
                        return $row->subtotal - $row->discount;
                    }) . ' ' . __('admin::home.SARR');
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
                        return $row->commission_amount;
                    })) . ' ' . __('admin::home.SARR');
                })
                ->searchable(),


            Column::make(__('admin::home.Payment paid'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row?->refPayment?->amountFormat;
                })
                ->searchable(),
            Column::make(__('admin::home.Payment Commission'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row?->PaymentCommission;
                })

                ->footer(function ($rows) {
                    return __('admin::home.Payment Commission') . ' : ' . number_format($rows->sum(function ($row) {
                        return $row->commissionFromSub;
                    })) . ' ' . __('admin::home.SARR');
                })
                ->searchable(),

            Column::make(__('admin::home.RefundpaymentAmount'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row?->refundpaymentSum;
                }),


            Column::make(__('admin::home.Due to the merchant'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo number_format($row->dueToMerchantFromSub, 2);
                })

                ->footer(function ($rows) {
                    return __('admin::home.Due to the merchant') . ' : ' . number_format($rows->sum(function ($row) {
                        return $row->dueToMerchantFromSub;
                    })) . ' ' . __('admin::home.SARR');
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

            Column::make(__('admin::home.payment_method'), 'payment_method')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row?->refPayment?->source_company) {
                        echo '<img width="20px"
                                 src="' . asset("images/paymentMethods/") . '/' . $row?->refPayment?->source_company . '.png"
                                 alt="' . $row?->refPayment?->source_company . '"
                            /><span class="mx-2">' . $row?->refPayment?->source_company . '</span>';
                    } else {
                        echo
                        '<img width="20px" src="' . asset("images/paymentMethods/") . '/' . $row?->payment_method . '.png"
                                 alt="' . $row?->payment_method . '"
                            /><span class="mx-2">' . $row?->payment_method . '</span>';
                    }
                })
                ->searchable(),
                Column::make(__('admin::home.Cash back'), 'cash_back')
                ->sortable()
                ->searchable(),
            // Column::make(__('admin::home.Transaction ID'), 'merchant_id')
            //     ->sortable()
            //     ->format(function ($value, $row, Column $column) {
            //         echo $row->refPayment?->payment_id;
            //     })
            //     ->searchable(),

            Column::make(__('admin::home.actions'), 'action')
                ->label(function ($row, Column $column) {
                    return view('admin::livewire.order.table.action')->withRow($row);
                }),

            Column::make(__('admin::home.Payment Id'), 'merchant_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row?->refPayment?->payment_id;
                })
                ->searchable(),
        ];
    }
}
