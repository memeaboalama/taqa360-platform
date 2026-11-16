<?php

namespace App\Domain\MarketPlace\Services;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use Domain\AppBase\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FinancialMovementCorrectionWorkflow
{
    public function preview(int $orderId, string $movementType, int $movementId, ?string $correctedAmount = null): array
    {
        $order = MerchantOrder::query()->findOrFail($orderId);
        $origin = $this->resolveOrigin($order, $movementType, $movementId);
        $currentAmount = $this->effectiveAmount($movementType, $movementId, $origin['amount']);
        $newAmount = $correctedAmount === null || trim($correctedAmount) === ''
            ? $currentAmount
            : $this->validatedAmount($correctedAmount);

        if ($movementType === 'collection') {
            $due = $this->orderDue($order);
            $currentCollected = $this->effectiveCollectedAmount($order);
            $balanceBefore = max($due - $currentCollected, 0);
            $balanceAfter = max($due - ($currentCollected + $newAmount - $currentAmount), 0);
            $balanceLabel = 'customer_due';
        } else {
            $balanceBefore = app(DriverCustodyLedger::class)
                ->balanceForOrder($order->id, (int) $origin['model']->driver_id);
            $balanceAfter = $balanceBefore + $currentAmount - $newAmount;
            $balanceLabel = 'driver_custody';
        }

        return [
            'movement_type' => $movementType,
            'movement_id' => $movementId,
            'original_amount' => round($currentAmount, 2),
            'corrected_amount' => round($newAmount, 2),
            'adjustment_amount' => round($newAmount - $currentAmount, 2),
            'balance_label' => $balanceLabel,
            'balance_before' => round($balanceBefore, 2),
            'balance_after' => round($balanceAfter, 2),
            'settlement_id' => $origin['settlement_id'],
        ];
    }

    public function request(
        int $orderId,
        string $movementType,
        int $movementId,
        string $correctedAmount,
        string $reason,
        Model $admin
    ): PaymentCorrectionRequest {
        $this->authorizeCorrection();
        $validated = Validator::make(
            ['correctedAmount' => $correctedAmount, 'reason' => $reason],
            [
                'correctedAmount' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
                'reason' => ['required', 'string', 'max:1000'],
            ]
        )->validate();

        return DB::transaction(function () use ($orderId, $movementType, $movementId, $validated, $admin) {
            $order = MerchantOrder::query()->lockForUpdate()->findOrFail($orderId);
            $origin = $this->resolveOrigin($order, $movementType, $movementId, true);
            $originalAmount = $this->effectiveAmount($movementType, $movementId, $origin['amount']);
            $correctedAmount = $this->validatedAmount((string) $validated['correctedAmount']);

            if ($this->toMinor($originalAmount) === $this->toMinor($correctedAmount)) {
                throw ValidationException::withMessages([
                    'correctedAmount' => 'القيمة المصححة مطابقة للقيمة الحالية.',
                ]);
            }

            $hasPendingRequest = PaymentCorrectionRequest::query()
                ->where('movement_type', $movementType)
                ->where('movement_id', $movementId)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->exists();
            if ($hasPendingRequest) {
                throw ValidationException::withMessages([
                    'correction' => 'يوجد طلب تصحيح قيد المراجعة لهذه الحركة.',
                ]);
            }

            return PaymentCorrectionRequest::query()->create([
                'order_id' => $order->id,
                'movement_type' => $movementType,
                'movement_id' => $movementId,
                'reason' => trim($validated['reason']),
                'requested_by' => $admin->getKey(),
                'status' => 'pending',
                'original_amount' => $originalAmount,
                'corrected_amount' => $correctedAmount,
                'settlement_id' => $origin['settlement_id'],
            ]);
        });
    }

    public function approve(int $requestId, Model $admin): PaymentCorrectionRequest
    {
        $this->authorizeCorrection();

        return DB::transaction(function () use ($requestId, $admin) {
            $request = PaymentCorrectionRequest::query()->lockForUpdate()->findOrFail($requestId);
            if ($request->status !== 'pending') {
                throw ValidationException::withMessages([
                    'correction' => 'لا يمكن اعتماد هذا الطلب في حالته الحالية.',
                ]);
            }

            $order = MerchantOrder::query()->lockForUpdate()->findOrFail($request->order_id);
            $origin = $this->resolveOrigin($order, $request->movement_type, (int) $request->movement_id, true);
            $currentAmount = $this->effectiveAmount(
                $request->movement_type,
                (int) $request->movement_id,
                $origin['amount']
            );
            if ($this->toMinor($currentAmount) !== $this->toMinor((float) $request->original_amount)) {
                throw ValidationException::withMessages([
                    'correction' => 'تغيرت قيمة الحركة منذ تقديم الطلب؛ ألغِ الطلب وقدّم تصحيحًا بالقيمة الحالية.',
                ]);
            }

            $correctedAmount = (float) $request->corrected_amount;
            $request->update([
                'status' => 'approved',
                'adjustment_amount' => round($correctedAmount - $currentAmount, 2),
                'settlement_id' => $origin['settlement_id'],
                'approved_by' => $admin->getKey(),
                'approved_at' => now(),
            ]);

            return $request->fresh(['requestedBy', 'approvedBy', 'settlement']);
        });
    }

    public function cancel(int $requestId, Model $admin): PaymentCorrectionRequest
    {
        $this->authorizeCorrection();

        $request = DB::transaction(function () use ($requestId, $admin) {
            $request = PaymentCorrectionRequest::query()->lockForUpdate()->findOrFail($requestId);
            if ($request->status !== 'pending') {
                throw ValidationException::withMessages([
                    'correction' => 'لا يمكن إلغاء هذا الطلب في حالته الحالية.',
                ]);
            }

            $request->update([
                'status' => 'cancelled',
                'cancelled_by' => $admin->getKey(),
                'cancelled_at' => now(),
            ]);

            return $request;
        });

        return $request->fresh(['requestedBy', 'cancelledBy', 'settlement']);
    }

    private function resolveOrigin(
        MerchantOrder $order,
        string $movementType,
        int $movementId,
        bool $lock = false
    ): array {
        if ($movementType === 'collection') {
            $query = Payment::query()
                ->where('payable_id', $order->id)
                ->whereIn('payable_type', [
                    $order->getMorphClass(),
                    MerchantOrder::class,
                    'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
                ])
                ->whereKey($movementId)
                ->whereIn('status', ['paid', 'success']);
            if ($lock) {
                $query->lockForUpdate();
            }
            $payment = $query->firstOrFail();
            if (!OrderPaymentLedger::isConfirmedPayment($order, $payment)) {
                throw ValidationException::withMessages([
                    'correction' => 'لا يمكن تصحيح حركة غير مؤكدة التحصيل.',
                ]);
            }

            $settlementId = $payment->settlement_id
                ?: OrderSettlement::query()
                    ->where('order_id', $order->id)
                    ->where('status', 'approved')
                    ->latest('id')
                    ->value('id');

            return [
                'model' => $payment,
                'amount' => round((float) ($payment->captured ?: $payment->amount) / 100, 2),
                'settlement_id' => $settlementId ? (int) $settlementId : null,
            ];
        }

        if ($movementType === 'settlement') {
            $query = OrderSettlement::query()
                ->where('order_id', $order->id)
                ->where('status', 'approved')
                ->whereNotNull('driver_id')
                ->whereKey($movementId);
            if ($lock) {
                $query->lockForUpdate();
            }
            $settlement = $query->firstOrFail();

            return [
                'model' => $settlement,
                'amount' => (float) $settlement->amount,
                'settlement_id' => $settlement->id,
            ];
        }

        throw ValidationException::withMessages([
            'correction' => 'نوع الحركة غير قابل للتصحيح من هذا النموذج.',
        ]);
    }

    private function effectiveAmount(string $movementType, int $movementId, float $baseAmount): float
    {
        $adjustment = (float) PaymentCorrectionRequest::query()
            ->where('movement_type', $movementType)
            ->where('movement_id', $movementId)
            ->where('status', 'approved')
            ->sum('adjustment_amount');

        return round($baseAmount + $adjustment, 2);
    }

    private function effectiveCollectedAmount(MerchantOrder $order): float
    {
        $payments = Payment::query()
            ->where('payable_id', $order->id)
            ->whereIn('payable_type', [
                $order->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ])
            ->whereIn('status', ['paid', 'success'])
            ->get()
            ->filter(fn (Payment $payment): bool => OrderPaymentLedger::isConfirmedPayment($order, $payment));

        $baseAmount = $payments->sum(
            fn (Payment $payment): float => round((float) ($payment->captured ?: $payment->amount) / 100, 2)
        );
        $adjustments = (float) PaymentCorrectionRequest::query()
            ->where('order_id', $order->id)
            ->where('movement_type', 'collection')
            ->where('status', 'approved')
            ->sum('adjustment_amount');

        return round($baseAmount + $adjustments, 2);
    }

    private function orderDue(MerchantOrder $order): float
    {
        return round((float) ($order->total_after_discount > 0
            ? $order->total_after_discount
            : $order->total_after_vat), 2);
    }

    private function validatedAmount(string $value): float
    {
        return round((float) $value, 2);
    }

    private function toMinor(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function authorizeCorrection(): void
    {
        abort_unless(CollectionSetting::adminCan('correct'), 403);
    }
}
