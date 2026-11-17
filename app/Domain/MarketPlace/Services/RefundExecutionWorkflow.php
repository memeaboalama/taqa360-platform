<?php

namespace App\Domain\MarketPlace\Services;

use App\Domain\AppBase\Models\RefundPayment;
use App\Domain\MarketPlace\Actions\Api\Wallet\WalletOrderAction;
use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
use Domain\Admin\Models\Admin;
use Domain\AppBase\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RefundExecutionWorkflow
{
    public function confirmedCollections(MerchantOrder $order): Collection
    {
        return Payment::query()
            ->where('payable_id', $order->id)
            ->whereIn('payable_type', [
                $order->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ])
            ->whereIn('status', ['paid', 'success'])
            ->orderBy('id')
            ->get()
            ->filter(fn (Payment $payment): bool => OrderPaymentLedger::isConfirmedPayment($order, $payment))
            ->groupBy('payment_id')
            ->map(fn ($records) => $records->last())
            ->values();
    }

    public function availableForCollection(MerchantOrder $order, Payment $payment, bool $reservePending = true): array
    {
        $this->assertCollectionBelongsToOrder($order, $payment);
        $collectionAmount = $this->collectionAmount($order, $payment);
        $refunds = $this->refundsForCollection($order, $payment);
        $executedAmount = (float) $refunds
            ->filter(fn (RefundPayment $refund): bool => $refund->isExecuted())
            ->sum('amount');
        $pendingAmount = (float) $refunds
            ->where('status', 'pending')
            ->sum('amount');
        $paymentRefunded = round((float) ($payment->refunded ?? 0) / 100, 2);
        $alreadyRefunded = max($executedAmount, $paymentRefunded);

        return [
            'collection_amount' => round($collectionAmount, 2),
            'refunded_amount' => round($alreadyRefunded, 2),
            'pending_amount' => round($pendingAmount, 2),
            'available_amount' => round(max(
                $collectionAmount - $alreadyRefunded - ($reservePending ? $pendingAmount : 0),
                0
            ), 2),
        ];
    }

    public function request(
        int $orderId,
        int $paymentRecordId,
        string $amount,
        string $reason,
        string $fundedBy,
        string $calculatedOn,
        Admin $admin
    ): RefundPayment {
        $this->authorize($admin);
        $validated = Validator::make(
            compact('amount', 'reason', 'fundedBy', 'calculatedOn'),
            [
                'amount' => ['required', 'numeric', 'gt:0', 'max:99999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
                'reason' => ['required', 'string', 'max:1000'],
                'fundedBy' => ['required', 'in:platform,merchant,driver'],
                'calculatedOn' => ['required', 'in:company,store,delivery'],
            ]
        )->validate();
        $validated['reason'] = trim($validated['reason']);
        if ($validated['reason'] === '') {
            throw ValidationException::withMessages(['reason' => 'سبب الاسترداد مطلوب.']);
        }

        return DB::transaction(function () use ($orderId, $paymentRecordId, $validated, $admin): RefundPayment {
            $order = MerchantOrder::query()->lockForUpdate()->findOrFail($orderId);
            $payment = $this->lockCollection($order, $paymentRecordId);
            $settlement = $this->settlementFor($order, $payment, true);
            $this->assertFundingAllowed($order, $settlement, $validated['fundedBy']);

            if ($validated['fundedBy'] === 'driver' && !CollectionSetting::adminCan('correct')) {
                abort(403);
            }

            $amount = round((float) $validated['amount'], 2);
            $available = $this->availableForCollection($order, $payment)['available_amount'];
            if ($amount > $available) {
                throw ValidationException::withMessages([
                    'amount' => 'مبلغ الاسترداد يتجاوز المتاح رده من حركة التحصيل.',
                ]);
            }
            $driverId = $validated['fundedBy'] === 'driver'
                ? (int) ($settlement?->driver_id ?: $order->driver_id)
                : null;
            if ($validated['fundedBy'] === 'driver') {
                $custody = app(DriverCustodyLedger::class)->balanceForOrder($order->id, $driverId);
                if ($custody < $amount) {
                    throw ValidationException::withMessages([
                        'amount' => 'مبلغ الاسترداد أكبر من العهدة المتاحة لهذا الطلب.',
                    ]);
                }
            }

            $userName = $order->client_name ?: $order->user?->name;
            if (!$userName) {
                throw ValidationException::withMessages([
                    'refund' => 'تعذر تحديد اسم العميل المرتبط بالطلب.',
                ]);
            }

            return RefundPayment::query()->create([
                'refund_payment_id' => 'RFD-' . Str::upper((string) Str::uuid()),
                'amount' => $amount,
                'currency' => 'SAR',
                'note' => $validated['reason'],
                'type' => 'toWallet',
                'refund_method' => 'wallet',
                'payment_id' => $payment->payment_id,
                'collection_payment_record_id' => $payment->id,
                'settlement_id' => $settlement?->id,
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'user_name' => $userName,
                'calculated_on' => $validated['calculatedOn'],
                'funded_by' => $validated['fundedBy'],
                'driver_id' => $driverId,
                'status' => 'pending',
                'requested_by' => $admin->id,
                'requested_at' => now(),
            ]);
        });
    }

    public function execute(int $refundId, Admin $admin): RefundPayment
    {
        $this->authorize($admin);

        return DB::transaction(function () use ($refundId, $admin): RefundPayment {
            $refund = RefundPayment::query()->lockForUpdate()->findOrFail($refundId);
            if ($refund->status !== 'pending') {
                throw ValidationException::withMessages([
                    'refund' => 'هذا الاسترداد ليس بانتظار التنفيذ أو سبق تنفيذه.',
                ]);
            }

            $order = MerchantOrder::query()->lockForUpdate()->findOrFail($refund->order_id);
            $paymentId = (int) $refund->collection_payment_record_id;
            if ($paymentId <= 0) {
                throw ValidationException::withMessages([
                    'refund' => 'حركة التحصيل الأصلية غير مرتبطة؛ لا يمكن تنفيذ الاسترداد.',
                ]);
            }
            $payment = $this->lockCollection($order, $paymentId);
            $settlement = $refund->settlement_id
                ? OrderSettlement::query()
                    ->whereKey($refund->settlement_id)
                    ->where('order_id', $order->id)
                    ->lockForUpdate()
                    ->first()
                : $this->settlementFor($order, $payment, true);
            if ($refund->settlement_id && !$settlement) {
                throw ValidationException::withMessages([
                    'refund' => 'تعذر العثور على التسوية المرتبطة بطلب الاسترداد.',
                ]);
            }
            $this->assertFundingAllowed($order, $settlement, (string) $refund->funded_by);

            if ($payment->settlement_id
                && (int) $payment->settlement_id !== (int) ($settlement?->id ?? 0)) {
                throw ValidationException::withMessages([
                    'refund' => 'تغيرت التسوية المرتبطة؛ راجع جهة حيازة المبلغ قبل التنفيذ.',
                ]);
            }

            $amount = round((float) $refund->amount, 2);
            $available = $this->availableForCollection($order, $payment, false)['available_amount'];
            if ($amount > $available) {
                throw ValidationException::withMessages([
                    'amount' => 'مبلغ الاسترداد يتجاوز المتاح رده من حركة التحصيل.',
                ]);
            }

            if ($refund->funded_by === 'driver') {
                if (!CollectionSetting::adminCan('correct')) {
                    abort(403);
                }
                $driverId = (int) ($settlement?->driver_id ?: $order->driver_id);
                if (!$driverId || $driverId !== (int) $refund->driver_id) {
                    throw ValidationException::withMessages([
                        'refund' => 'السائق المرتبط بالاسترداد لم يعد مطابقًا لحركة العهدة.',
                    ]);
                }
                $custody = app(DriverCustodyLedger::class)->balanceForOrder($order->id, $driverId);
                if ($custody < $amount) {
                    throw ValidationException::withMessages([
                        'amount' => 'مبلغ الاسترداد أكبر من العهدة المتاحة لهذا الطلب.',
                    ]);
                }
            }

            $walletTransaction = app(WalletOrderAction::class)->add_wallet_refund_payment(
                $order,
                'Wallet Refund Payment',
                $amount
            );
            $totalRefunded = max(
                (float) $this->availableForCollection($order, $payment, false)['refunded_amount'],
                round((float) ($payment->refunded ?? 0) / 100, 2)
            ) + $amount;
            $timestamp = now();
            $payment->update([
                'refunded' => (int) round($totalRefunded * 100),
                'refundedAt' => $timestamp->toIso8601String(),
                'refundedFormat' => number_format($totalRefunded, 2) . ' SAR',
            ]);

            $refund->update([
                'status' => 'executed',
                'settlement_id' => $settlement?->id,
                'executed_by' => $admin->id,
                'executed_at' => $timestamp,
                'reference' => $walletTransaction->uuid ?: (string) $walletTransaction->id,
            ]);

            return $refund->fresh(['collectionPayment', 'settlement', 'requestedBy', 'executedBy']);
        });
    }

    private function lockCollection(MerchantOrder $order, int $paymentId): Payment
    {
        $payment = Payment::query()
            ->whereKey($paymentId)
            ->where('payable_id', $order->id)
            ->whereIn('payable_type', [
                $order->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ])
            ->lockForUpdate()
            ->firstOrFail();

        $latestSuccessful = Payment::query()
            ->where('payable_id', $order->id)
            ->whereIn('payable_type', [
                $order->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ])
            ->where('payment_id', $payment->payment_id)
            ->whereIn('status', ['paid', 'success'])
            ->orderByDesc('id')
            ->value('id');

        if ((int) $latestSuccessful !== (int) $payment->id
            || !OrderPaymentLedger::isConfirmedPayment($order, $payment)
            || $this->collectionAmount($order, $payment) <= 0) {
            throw ValidationException::withMessages([
                'payment' => 'اختر حركة تحصيل مؤكدة وحديثة مرتبطة بهذا الطلب.',
            ]);
        }

        return $payment;
    }

    private function assertCollectionBelongsToOrder(MerchantOrder $order, Payment $payment): void
    {
        if ((int) $payment->payable_id !== (int) $order->id
            || !in_array($payment->payable_type, [
                $order->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ], true)
            || !OrderPaymentLedger::isConfirmedPayment($order, $payment)) {
            throw ValidationException::withMessages([
                'payment' => 'حركة التحصيل غير مؤكدة أو لا تخص هذا الطلب.',
            ]);
        }
    }

    private function collectionAmount(MerchantOrder $order, Payment $payment): float
    {
        $amount = $payment->captured ?: $payment->amount;

        return round((float) $amount / 100, 2);
    }

    private function refundsForCollection(MerchantOrder $order, Payment $payment)
    {
        return RefundPayment::query()
            ->where('order_id', $order->id)
            ->where(function ($query) use ($payment) {
                $query->where('collection_payment_record_id', $payment->id)
                    ->orWhere(function ($query) use ($payment) {
                        $query->whereNull('collection_payment_record_id')
                            ->where('payment_id', $payment->payment_id);
                    });
            })
            ->lockForUpdate()
            ->get();
    }

    private function settlementFor(MerchantOrder $order, Payment $payment, bool $lock): ?OrderSettlement
    {
        $settlementId = $payment->settlement_id
            ?: OrderSettlement::query()
                ->where('order_id', $order->id)
                ->orderByDesc('id')
                ->value('id');
        if (!$settlementId) {
            return null;
        }

        $query = OrderSettlement::query()
            ->whereKey($settlementId)
            ->where('order_id', $order->id);
        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }

    private function assertFundingAllowed(MerchantOrder $order, ?OrderSettlement $settlement, string $fundedBy): void
    {
        if ($fundedBy === 'driver' && $settlement?->status === 'approved') {
            throw ValidationException::withMessages([
                'fundedBy' => 'تم اعتماد التوريد؛ يجب أن تعيد المبلغ الجهة الحائزة له.',
            ]);
        }

        if ($settlement?->status === 'approved') {
            $holder = $settlement->recipient === 'merchant' ? 'merchant' : 'platform';
            if ($fundedBy !== $holder) {
                throw ValidationException::withMessages([
                    'fundedBy' => 'مصدر تمويل الاسترداد لا يطابق جهة حيازة المبلغ بعد التوريد.',
                ]);
            }
        }

        if ($fundedBy === 'driver' && !($settlement?->driver_id ?: $order->driver_id)) {
            throw ValidationException::withMessages([
                'fundedBy' => 'لا يوجد سائق مرتبط بعهدة التحصيل.',
            ]);
        }
    }

    private function authorize(Admin $admin): void
    {
        abort_unless(
            auth('admin')->check()
                && (int) auth('admin')->id() === (int) $admin->id
                && ($admin->type === 'admin' || $admin->hasAnyRole(['create_refundpayments', 'show_roles'])),
            403
        );
    }
}
