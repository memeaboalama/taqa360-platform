<?php

namespace Application\Admin\Livewire\RefundPayment\Table;

use App\Domain\RefundPayment\Repositories\RefundPaymentRepository;
use Carbon\Carbon;
use App\Domain\AppBase\Models\RefundPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Support\Traits\Datatables\TableConfiguration;

class All extends DataTableComponent
{
    use TableConfiguration;
    public $filters = [];
    protected RefundPaymentRepository $refundpaymentRepository;
    public $listeners = ['refreshRefundPayments'];
    public function __construct()
    {
        $this->refundpaymentRepository = new RefundPaymentRepository(new RefundPayment());
        parent::__construct();
    }

    public function refreshRefundPayments(array $filters = [])
    {
        $this->filters = $filters;
        $this->builder();
        $this->emit('refreshDatatable');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        $query = $this->refundpaymentRepository->filter(
            $this->filters,
            ['order', 'collectionPayment', 'settlement', 'requestedBy', 'executedBy']
        );

        $collectionAmount = DB::table('payments as collection')
            ->selectRaw('COALESCE(NULLIF(collection.captured, 0), collection.amount) / 100')
            ->where(function ($query) {
                $query->whereColumn('collection.id', 'refund_payments.collection_payment_record_id')
                    ->orWhere(function ($query) {
                        $query->whereColumn('collection.payable_id', 'refund_payments.order_id')
                            ->whereColumn('collection.payment_id', 'refund_payments.payment_id');
                    });
            })
            ->whereIn('collection.status', ['paid', 'success'])
            ->orderByDesc('collection.id')
            ->limit(1);

        $previousRefunds = DB::table('refund_payments as previous_refunds')
            ->selectRaw('COALESCE(SUM(previous_refunds.amount), 0)')
            ->whereColumn('previous_refunds.order_id', 'refund_payments.order_id')
            ->whereColumn('previous_refunds.id', '<', 'refund_payments.id')
            ->whereRaw('(previous_refunds.collection_payment_record_id = refund_payments.collection_payment_record_id OR (previous_refunds.collection_payment_record_id IS NULL AND previous_refunds.payment_id = refund_payments.payment_id))')
            ->where(function ($query) {
                $query->where('previous_refunds.status', 'executed')
                    ->orWhereNull('previous_refunds.status');
            });

        $pendingRefunds = DB::table('refund_payments as previous_refunds')
            ->selectRaw('COALESCE(SUM(previous_refunds.amount), 0)')
            ->whereColumn('previous_refunds.order_id', 'refund_payments.order_id')
            ->whereColumn('previous_refunds.id', '<', 'refund_payments.id')
            ->whereRaw('(previous_refunds.collection_payment_record_id = refund_payments.collection_payment_record_id OR (previous_refunds.collection_payment_record_id IS NULL AND previous_refunds.payment_id = refund_payments.payment_id))')
            ->where('previous_refunds.status', 'pending');

        return $query->addSelect([
            'collection_amount' => $collectionAmount,
            'previously_refunded_amount' => $previousRefunds,
            'pending_before_amount' => $pendingRefunds,
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true)
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.RefundpaymentId'), 'refund_payment_id')
                ->sortable()
                ->searchable(),

            Column::make(__('admin::home.RefundpaymentType'), 'type')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'toWallet') {
                        echo '<span class="badge bg-success">' . __("admin::home.RefundpaymentType toWallet") . '</span>';
                    } else {
                        echo '<span class="badge bg-info">' . __("admin::home.RefundpaymentType toAccount") . '</span>';
                    }
                    
                })
                ->searchable(),

            Column::make(__('admin::home.refund_status'), 'status')
                ->sortable()
                ->format(function ($value) {
                    $status = $value ?: 'executed';
                    return __('admin::home.refund_status_' . $status);
                }),

            Column::make(__('admin::home.refund_method'), 'refund_method')
                ->format(function ($value) {
                    return $value === 'wallet'
                        ? __('admin::home.refund_method_wallet')
                        : ($value ?: '—');
                }),

            Column::make(__('admin::home.refund_funding_source'), 'funded_by')
                ->format(function ($value) {
                    return $value
                        ? __('admin::home.refund_funded_by_' . $value)
                        : '—';
                }),

            Column::make(__('admin::home.RefundpaymentAmount'), 'amount')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return number_format((float) $value, 2);
                })
                ->searchable(),

            Column::make(__('admin::home.refund_collection_amount'), 'collection_amount')
                ->format(fn ($value) => $value === null ? '—' : number_format((float) $value, 2)),

            Column::make(__('admin::home.previously_refunded_amount'), 'previously_refunded_amount')
                ->format(fn ($value) => number_format((float) $value, 2)),

            Column::make(__('admin::home.available_before_refund_request'), 'available_refund_amount')
                ->label(function ($row) {
                    $available = (float) ($row->collection_amount ?? 0)
                        - (float) $row->previously_refunded_amount
                        - (float) $row->pending_before_amount;

                    return number_format(max($available, 0), 2);
                }),

            Column::make(__('admin::home.executed_by'), 'executed_by')
                ->label(fn ($row) => $row->executedBy?->name ?: '—'),

                Column::make(__('admin::home.calculated on'), 'calculated_on')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value == 'company') {
                        echo '<span class="badge bg-success">' . __("admin::home.Company") . '</span>';
                    }
                    else if ($value == 'delivery')  {
                        echo '<span class="badge bg-info">' . __("admin::home.Delivery") . '</span>';
                    }
                     else {
                        echo '<span class="badge bg-primary">' . __("admin::home.Store") . '</span>';
                    }
                    
                })
                ->searchable(),
            Column::make(__('admin::home.RefundpaymentNote'), 'note')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->note;
                })
                ->searchable(),
            Column::make(__('admin::home.Customer Name'), 'user_name')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->user_name;
                })
                ->searchable(),
            Column::make(__('admin::home.Order No'), 'order_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->order?->code ?: $row->order_id;
                })
                ->searchable(),

            Column::make(__('admin::home.PaymentId'), 'payment_id')
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    echo $row->collectionPayment?->payment_id ?: $row->payment_id;
                })
                ->searchable(),

            Column::make(__('admin::home.settlement_number'), 'settlement_id')
                ->format(function ($value) {
                    return $value ? 'STL-' . $value : '—';
                }),

            Column::make(__('admin::home.refund_execution_reference'), 'reference')
                ->format(fn ($value) => $value ?: '—')
                ->searchable(),

            Column::make(__('admin::home.executed_at'), 'executed_at')
                ->format(function ($value) {
                    return $value ? Carbon::parse($value)->format('Y-m-d H:i') : '—';
                })
                ->sortable(),
                
            Column::make(__('admin::home.Date'), 'created_at')
                ->format(function ($value, $row, Column $column) {
                    echo Carbon::parse($value)->format('j F Y');
                })
                ->sortable()
                ->searchable(),

            // Column::make(__('admin::home.actions'), 'action')
            //     ->label(function ($row, Column $column) {
            //         return view('admin::livewire.refundpayment.table.action')->withRow($row);
            //     })

        ];
    }
}
