<?php

namespace App\Domain\MarketPlace\Services;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\OrderSettlementHistory;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use Domain\AppBase\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SettlementWorkflow
{
    public function review(int $settlementId, Model $actor): OrderSettlement
    {
        return $this->transition($settlementId, $actor, 'reviewed', ['pending_review'], 'reviewed');
    }

    public function approve(int $settlementId, Model $actor, array $receipt): OrderSettlement
    {
        return DB::transaction(function () use ($settlementId, $actor, $receipt): OrderSettlement {
            $receivedAt = $receipt['received_at'] ?? null;
            $receivedBy = trim((string) ($receipt['received_by'] ?? ''));
            if (!is_numeric($receipt['received_amount'] ?? null)
                || (float) $receipt['received_amount'] <= 0
                || !$receivedAt instanceof \DateTimeInterface
                || $receivedBy === ''
                || ($receipt['receipt_confirmed'] ?? false) !== true) {
                throw ValidationException::withMessages([
                    'receiptConfirmed' => 'أكمل بيانات الاستلام وأكد مطابقة المبلغ قبل الاعتماد.',
                ]);
            }

            $settlement = OrderSettlement::query()->lockForUpdate()->findOrFail($settlementId);
            $settlements = OrderSettlement::batchQuery($settlement->batch_number)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            if ($settlements->isEmpty()
                || $settlements->contains(fn (OrderSettlement $item) =>
                    (int) $item->driver_id !== (int) $settlement->driver_id)) {
                throw ValidationException::withMessages([
                    'settlement' => 'بيانات مجموعة التسوية غير متطابقة.',
                ]);
            }
            foreach ($settlements as $item) {
                $this->assertStatus($item, 'approved', ['pending_review', 'reviewed']);
                if ($item->approved_at || $item->approved_by) {
                    throw ValidationException::withMessages([
                        'settlement' => 'سبق اعتماد هذه التسوية ولا يمكن اعتمادها مرة أخرى.',
                    ]);
                }
            }

            $settlementIds = $settlements->pluck('id');
            $paymentsBySettlement = [];
            $expectedTotalMinor = 0;
            $settlementTotalMinor = 0;
            $collectedTotalMinor = 0;
            foreach ($settlements as $item) {
                $order = MerchantOrder::query()->lockForUpdate()->findOrFail($item->order_id);
                if (!MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
                    throw ValidationException::withMessages([
                        'settlement' => 'طريقة الدفع لأحد الطلبات لا تسمح باعتماد توريد عهدة السائق.',
                    ]);
                }

                $payments = Payment::query()
                    ->where('payable_id', $order->id)
                    ->whereIn('payable_type', [
                        $order->getMorphClass(),
                        MerchantOrder::class,
                        'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
                    ])
                    ->lockForUpdate()
                    ->get()
                    ->filter(fn (Payment $payment): bool =>
                        $payment->source_message === MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE
                        && MerchantOrder::resolvePaymentStatus($payment, 'unpaid', $order->payment_method) === 'paid'
                    )
                    ->groupBy('payment_id')
                    ->map(fn ($records) => $records->last())
                    ->values();

                $orderDue = $order->total_after_discount > 0
                    ? $order->total_after_discount
                    : $order->total_after_vat;
                $expectedMinor = (int) round((float) $orderDue * 100);
                $settlementMinor = (int) round((float) $item->amount * 100);
                $collectedMinor = (int) $payments->sum(
                    fn (Payment $payment): int => (int) ($payment->captured ?: $payment->amount)
                ) + (int) round((float) PaymentCorrectionRequest::query()
                    ->where('order_id', $order->id)
                    ->where('movement_type', 'collection')
                    ->where('status', 'approved')
                    ->sum('adjustment_amount') * 100);

                if ($payments->isEmpty()
                    || !self::amountsMatch($expectedMinor, $settlementMinor, $collectedMinor, $expectedMinor)) {
                    throw ValidationException::withMessages([
                        'settlement' => 'توجد فروقات في أحد الطلبات بين الإجمالي والتحصيل والتسوية؛ أوقف الاعتماد للمطابقة.',
                    ]);
                }

                if (OrderSettlement::query()
                    ->where('order_id', $order->id)
                    ->where('status', 'approved')
                    ->whereNotIn('id', $settlementIds)
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'settlement' => 'سبق اعتماد توريد لأحد الطلبات المشمولة.',
                    ]);
                }

                $paymentsBySettlement[$item->id] = $payments;
                $expectedTotalMinor += $expectedMinor;
                $settlementTotalMinor += $settlementMinor;
                $collectedTotalMinor += $collectedMinor;
            }

            $receivedMinor = (int) round((float) $receipt['received_amount'] * 100);
            if (!self::amountsMatch(
                $expectedTotalMinor,
                $settlementTotalMinor,
                $collectedTotalMinor,
                $receivedMinor
            )) {
                throw ValidationException::withMessages([
                    'settlement' => 'المبلغ المستلم لا يطابق إجمالي المجموعة والتحصيلات؛ أوقف الاعتماد للمطابقة.',
                ]);
            }

            foreach ($settlements as $item) {
                $previousStatus = $item->status;
                $item->update([
                    'status' => 'approved',
                    'approved_by' => $actor->getKey(),
                    'approved_at' => now(),
                    'received_amount' => $item->amount,
                    'received_at' => $receivedAt,
                    'received_by' => $receivedBy,
                    'receipt_confirmed' => true,
                ]);
                $payments = $paymentsBySettlement[$item->id];
                Payment::query()
                    ->whereIn('id', $payments->pluck('id'))
                    ->update(['settlement_id' => $item->id]);
                PaymentCorrectionRequest::query()
                    ->where('order_id', $item->order_id)
                    ->where('movement_type', 'collection')
                    ->where('status', 'approved')
                    ->update(['settlement_id' => $item->id]);
                $this->record(
                    $item,
                    $actor,
                    'approved',
                    $previousStatus,
                    'approved',
                    null,
                    [
                        'recipient' => $item->recipient,
                        'method' => $item->method,
                        'recipient_name' => $item->recipient_name,
                        'instructions' => $item->instructions,
                        'account_data' => $item->account_data,
                        'amount' => $item->amount,
                        'reference' => $item->reference,
                        'proof_path' => $item->proof_path,
                        'supply_date' => $item->supply_date?->toDateString(),
                        'received_amount' => $item->received_amount,
                        'received_at' => $item->received_at?->toIso8601String(),
                        'received_by' => $item->received_by,
                        'receipt_confirmed' => $item->receipt_confirmed,
                        'payment_ids' => $payments->pluck('payment_id')->all(),
                    ]
                );
            }

            return $settlement->fresh(['order', 'driver']);
        });
    }

    public static function amountsMatch(int $expected, int $settlement, int $collected, int $received): bool
    {
        return $expected > 0
            && $expected === $settlement
            && $expected === $collected
            && $expected === $received;
    }

    public function returnForCompletion(int $settlementId, Model $actor, string $note): OrderSettlement
    {
        return $this->transition(
            $settlementId,
            $actor,
            'returned',
            ['pending_review', 'reviewed'],
            'returned',
            $note
        );
    }

    public function reject(int $settlementId, Model $actor, string $note): OrderSettlement
    {
        return $this->transition(
            $settlementId,
            $actor,
            'rejected',
            ['pending_review', 'reviewed'],
            'rejected',
            $note
        );
    }

    public function recordSubmission(
        OrderSettlement $settlement,
        Model $actor,
        string $action,
        ?string $previousStatus,
        array $snapshot
    ): void {
        if (!self::canTransition($previousStatus, 'pending_review')) {
            throw ValidationException::withMessages([
                'settlement' => 'لا تسمح حالة التسوية الحالية بإرسالها للمراجعة.',
            ]);
        }

        $this->record($settlement, $actor, $action, $previousStatus, 'pending_review', null, $snapshot);
    }

    private function transition(
        int $settlementId,
        Model $actor,
        string $action,
        array $allowedStatuses,
        string $newStatus,
        ?string $note = null
    ): OrderSettlement {
        return DB::transaction(function () use (
            $settlementId,
            $actor,
            $action,
            $allowedStatuses,
            $newStatus,
            $note
        ): OrderSettlement {
            $settlement = OrderSettlement::query()->lockForUpdate()->findOrFail($settlementId);
            $settlements = OrderSettlement::batchQuery($settlement->batch_number)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();
            foreach ($settlements as $item) {
                $this->assertStatus($item, $newStatus, $allowedStatuses);
            }

            $attributes = ['status' => $newStatus];
            if ($action === 'reviewed') {
                $attributes['reviewed_by'] = $actor->getKey();
                $attributes['reviewed_at'] = now();
            }
            foreach ($settlements as $item) {
                $previousStatus = $item->status;
                $item->update($attributes);
                $this->record($item, $actor, $action, $previousStatus, $newStatus, $note);
            }

            if ($action === 'rejected') {
                $settlementIds = $settlements->pluck('id');
                Payment::query()->whereIn('settlement_id', $settlementIds)->update(['settlement_id' => null]);
                PaymentCorrectionRequest::query()
                    ->whereIn('settlement_id', $settlementIds)
                    ->where('movement_type', 'collection')
                    ->update(['settlement_id' => null]);
            }

            return $settlement->fresh(['order', 'driver']);
        });
    }

    public static function canTransition(?string $from, string $to): bool
    {
        $allowedTransitions = [
            null => ['pending_review'],
            'pending_review' => ['reviewed', 'approved', 'returned', 'rejected'],
            'reviewed' => ['approved', 'returned', 'rejected'],
            'returned' => ['pending_review'],
        ];

        return in_array($to, $allowedTransitions[$from] ?? [], true);
    }

    private function assertStatus(
        OrderSettlement $settlement,
        string $newStatus,
        ?array $allowedStatuses = null
    ): void
    {
        if (($allowedStatuses && !in_array($settlement->status, $allowedStatuses, true))
            || !self::canTransition($settlement->status, $newStatus)) {
            throw ValidationException::withMessages([
                'settlement' => 'تغيرت حالة التسوية. حدّث الصفحة قبل تنفيذ الإجراء.',
            ]);
        }
    }

    private function record(
        OrderSettlement $settlement,
        Model $actor,
        string $action,
        ?string $previousStatus,
        string $status,
        ?string $note = null,
        array $snapshot = []
    ): void {
        if ($snapshot === []) {
            $snapshot = [
                'recipient' => $settlement->recipient,
                'method' => $settlement->method,
                'recipient_name' => $settlement->recipient_name,
                'instructions' => $settlement->instructions,
                'account_data' => $settlement->account_data,
                'amount' => $settlement->amount,
                'reference' => $settlement->reference,
                'proof_path' => $settlement->proof_path,
                'received_amount' => $settlement->received_amount,
                'received_at' => $settlement->received_at?->toIso8601String(),
                'received_by' => $settlement->received_by,
                'receipt_confirmed' => $settlement->receipt_confirmed,
            ];
        }

        OrderSettlementHistory::create([
            'settlement_id' => $settlement->id,
            'actor_type' => $actor->getMorphClass(),
            'actor_id' => $actor->getKey(),
            'action' => $action,
            'status_from' => $previousStatus,
            'status_to' => $status,
            'note' => $note,
            'snapshot' => $snapshot ?: null,
            'created_at' => now(),
        ]);
    }
}
