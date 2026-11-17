<?php

namespace Application\Marketplace\Livewire\Finance\Reports\Order\Components;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use App\Domain\MarketPlace\Services\OrderPaymentLedger;
use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Models\RefundPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CollectionSettlement extends Component
{
    use WithPagination;

    public $code = '';
    public $fromDate = '';
    public $toDate = '';
    public $collectionStatus = '';
    public $settlementStatus = '';

    protected $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $this->code = (string) request()->query('code', '');
    }

    public function applyFilters(): void
    {
        $this->validate([
            'code' => ['nullable', 'string', 'max:255'],
            'fromDate' => ['nullable', 'date'],
            'toDate' => ['nullable', 'date', 'after_or_equal:fromDate'],
            'collectionStatus' => ['nullable', 'in:collected,not_collected'],
            'settlementStatus' => ['nullable', 'in:not_submitted,pending_review,approved'],
        ]);

        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['code', 'fromDate', 'toDate', 'collectionStatus', 'settlementStatus']);
        $this->resetPage();
    }

    private function merchantId(): ?int
    {
        if (Auth::guard('merchant_employee')->check()) {
            return (int) Auth::guard('merchant_employee')->user()->merchant_id;
        }

        if (Auth::guard('merchant')->check()) {
            return (int) Auth::guard('merchant')->id();
        }

        if (app()->bound('_InitialMerchant') && app('_InitialMerchant')) {
            return (int) app('_InitialMerchant');
        }

        return null;
    }

    private function scopedQuery(): Builder
    {
        $merchantId = $this->merchantId();
        $query = MerchantOrder::query()
            ->where('merchant_id', $merchantId ?: 0)
            ->with('branch');

        if (Auth::guard('merchant_employee')->check()) {
            $query->forMerchantEmployee(Auth::guard('merchant_employee')->user());
        }

        if (filled($this->code)) {
            $query->where('code', 'like', '%' . trim($this->code) . '%');
        }

        if (filled($this->fromDate)) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }

        if (filled($this->toDate)) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        if ($this->collectionStatus === 'collected') {
            $query->where(function (Builder $query): void {
                $query->whereHas('payment', $this->confirmedPaymentConstraint())
                    ->orWhereIn('merchant_orders.id', $this->approvedCollectionCorrections());
            });
        } elseif ($this->collectionStatus === 'not_collected') {
            $query->whereDoesntHave('payment', $this->confirmedPaymentConstraint())
                ->whereNotIn('merchant_orders.id', $this->approvedCollectionCorrections());
        }

        if ($this->settlementStatus === 'approved') {
            $query->whereHas('settlement', fn (Builder $settlement) => $settlement->where('status', 'approved'));
        } elseif ($this->settlementStatus === 'pending_review') {
            $query->whereHas('settlement', fn (Builder $settlement) => $settlement
                ->whereNotIn('status', ['approved', 'returned', 'rejected']));
        } elseif ($this->settlementStatus === 'not_submitted') {
            $query->where(function (Builder $query): void {
                $query->whereDoesntHave('settlement')
                    ->orWhereHas('settlement', fn (Builder $settlement) => $settlement
                        ->whereIn('status', ['returned', 'rejected']));
            });
        }

        return $query;
    }

    private function confirmedPaymentConstraint(): \Closure
    {
        return static function (Builder $payments): void {
            $payments->whereIn('status', ['paid', 'success'])
                ->where(function (Builder $payments): void {
                    $payments->whereNotIn('merchant_orders.payment_method', [
                        'cod',
                        'cash',
                        'cash_on_delivery',
                        'cashondelivery',
                    ])->orWhere('source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE);
                })
                ->where(function (Builder $payments): void {
                    $payments->where('captured', '>', 0)
                        ->orWhere('amount', '>', 0);
                });
        };
    }

    private function approvedCollectionCorrections(): \Illuminate\Database\Eloquent\Builder
    {
        return PaymentCorrectionRequest::query()
            ->select('order_id')
            ->where('movement_type', 'collection')
            ->where('status', 'approved');
    }

    private function merchantEntitlement(MerchantOrder $order): ?array
    {
        if (empty($order->package_id)
            || $order->commission_rate === null
            || $order->commission_amount === null) {
            return null;
        }

        $deductions = $this->merchantDeductions($order);
        if ($deductions === null) {
            return null;
        }

        return [
            'amount' => round(
                (float) $order->subtotal
                - $deductions['discount']
                - $deductions['commission']
                - $deductions['payment_fee']
                - $deductions['refund'],
                2
            ),
            'deductions' => $deductions,
        ];
    }

    private function merchantDeductions(MerchantOrder $order): ?array
    {
        $promo = $order->refPromocode;
        $discount = $promo && (int) $promo->merchant_id === (int) $order->merchant_id
            ? (float) $order->discount
            : 0.0;
        $paymentFee = $this->paymentFee($order);
        if ($paymentFee === null) {
            return null;
        }
        $storeRefunds = (float) RefundPayment::query()
            ->where('order_id', $order->id)
            ->where('calculated_on', 'store')
            ->where(function (Builder $query): void {
                $query->where('status', 'executed')->orWhereNull('status');
            })
            ->sum('amount');

        return [
            'commission' => (float) $order->commissionFromSub,
            'discount' => $discount,
            'payment_fee' => $paymentFee,
            'refund' => $storeRefunds,
        ];
    }

    private function paymentFee(MerchantOrder $order): ?float
    {
        $payment = $order->refPayment;
        if (!$payment || MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
            return 0.0;
        }

        $field = match (strtolower(str_replace([' ', '_', '-'], '', (string) $payment->source_company))) {
            'visa' => 'visa_commission',
            'master', 'mastercard' => 'master_commission',
            'mada' => 'mada_commission',
            'applepay' => 'apple_commission',
            'stcpay' => 'stc_commission',
            default => null,
        };
        $setting = $field ? GeneralSetting::query()->first() : null;

        if (!$field || !$setting || $setting->{$field} === null) {
            return null;
        }

        return (float) $order->paymentCommissionFromSub();
    }

    private function reportRow(MerchantOrder $order, OrderPaymentLedger $ledger): array
    {
        $financial = $ledger->forOrder($order);
        $entitlement = $this->merchantEntitlement($order);

        return [
            'order' => $order,
            'gross' => $financial['due'],
            'collected' => $financial['collected'],
            'refunded' => $financial['refunded'],
            'net_collected' => round($financial['collected'] - $financial['refunded'], 2),
            'collection_status' => $financial['payment_label'],
            'settlement_status' => $financial['settlement_status'],
            'settlement_number' => $financial['settlement_number'],
            'merchant_due' => $entitlement['amount'] ?? null,
            'deductions' => $entitlement['deductions'] ?? null,
            'payout_status' => __('marketplace::home.payout_not_recorded'),
        ];
    }

    private function periodTotals(OrderPaymentLedger $ledger): array
    {
        $totals = [
            'orders' => 0,
            'gross' => 0.0,
            'collected' => 0.0,
            'refunded' => 0.0,
            'net_collected' => 0.0,
            'merchant_due' => 0.0,
            'merchant_due_defined' => true,
        ];

        $this->scopedQuery()
            ->reorder()
            ->chunkById(100, function ($orders) use ($ledger, &$totals): void {
                foreach ($orders as $order) {
                    $financial = $ledger->forOrder($order);
                    $entitlement = $this->merchantEntitlement($order);
                    $totals['orders']++;
                    $totals['gross'] += $financial['due'];
                    $totals['collected'] += $financial['collected'];
                    $totals['refunded'] += $financial['refunded'];
                    $totals['net_collected'] += $financial['collected'] - $financial['refunded'];

                    if ($entitlement === null) {
                        $totals['merchant_due_defined'] = false;
                    } else {
                        $totals['merchant_due'] += $entitlement['amount'];
                    }
                }
            });

        foreach (['gross', 'collected', 'refunded', 'net_collected', 'merchant_due'] as $amount) {
            $totals[$amount] = round($totals[$amount], 2);
        }

        if (!$totals['merchant_due_defined']) {
            $totals['merchant_due'] = null;
        }

        return $totals;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $ledger = app(OrderPaymentLedger::class);
        $orders = $this->scopedQuery()->orderByDesc('id')->paginate(15);
        $orders->setCollection($orders->getCollection()->map(
            fn (MerchantOrder $order): array => $this->reportRow($order, $ledger)
        ));

        return view('marketplace::livewire.finance.reports.order.components.collection-settlement', [
            'orders' => $orders,
            'totals' => $this->periodTotals($ledger),
        ]);
    }
}
