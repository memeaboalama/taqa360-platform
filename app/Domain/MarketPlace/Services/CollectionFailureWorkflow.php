<?php

namespace App\Domain\MarketPlace\Services;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\DriverCollectionFailureReport;
use App\Domain\MarketPlace\Models\DriverCollectionFailureReportHistory;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Domain\AppBase\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CollectionFailureWorkflow
{
    public const OPEN_STATUSES = ['open', 'retry_requested', 'return_requested'];

    public function report(int $orderId, int $driverId, string $reason, ?string $driverNote, Model $driver): DriverCollectionFailureReport
    {
        return DB::transaction(function () use ($orderId, $driverId, $reason, $driverNote, $driver): DriverCollectionFailureReport {
            $order = MerchantOrder::query()
                ->whereKey($orderId)
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->firstOrFail();
            $this->assertOrderCanBeReported($order);
            if ($order->collectionFailureReports()->where('status', 'return_completed')->exists()) {
                throw ValidationException::withMessages([
                    'collectionFailure' => 'تم توثيق إرجاع الطلب ولا يمكن فتح بلاغ تحصيل جديد له.',
                ]);
            }

            if (DriverCollectionFailureReport::query()
                ->where('order_id', $orderId)
                ->whereIn('status', self::OPEN_STATUSES)
                ->lockForUpdate()
                ->exists()) {
                throw ValidationException::withMessages([
                    'collectionFailure' => 'يوجد بلاغ تحصيل مفتوح لهذا الطلب.',
                ]);
            }

            $report = DriverCollectionFailureReport::create([
                'order_id' => $order->id,
                'driver_id' => $driverId,
                'reason' => $reason,
                'driver_note' => $driverNote,
                'status' => 'open',
            ]);
            if (!self::canTransition(null, 'open')) {
                throw ValidationException::withMessages(['report' => 'لا يمكن إنشاء البلاغ في حالته الحالية.']);
            }
            $this->record($report, $driver, 'reported', null, 'open', $driverNote);

            return $report;
        });
    }

    public function decide(int $reportId, string $decision, string $instructions, Model $admin): DriverCollectionFailureReport
    {
        return DB::transaction(function () use ($reportId, $decision, $instructions, $admin): DriverCollectionFailureReport {
            $instructions = trim($instructions);
            if ($instructions === '') {
                throw ValidationException::withMessages(['instructions' => 'أدخل تعليمات واضحة للسائق قبل حفظ القرار.']);
            }
            $report = DriverCollectionFailureReport::query()->lockForUpdate()->findOrFail($reportId);
            if (!self::canTransition($report->status, $decision)) {
                throw ValidationException::withMessages(['report' => 'لا يمكن معالجة هذا البلاغ في حالته الحالية.']);
            }

            $order = MerchantOrder::query()->lockForUpdate()->findOrFail($report->order_id);
            $this->assertOrderCanBeReported($order);
            $this->assertNoConfirmedCollection($order);

            if (!in_array($decision, ['retry_requested', 'return_requested'], true)) {
                throw ValidationException::withMessages(['decision' => 'قرار البلاغ غير صالح.']);
            }

            $previous = $report->status;
            $report->update([
                'status' => $decision,
                'admin_instructions' => $instructions,
                'decided_by' => $admin->getKey(),
                'decided_at' => now(),
            ]);
            $this->record($report, $admin, $decision, $previous, $decision, $instructions);

            return $report->fresh(['order', 'driver']);
        });
    }

    public function confirmCollection(int $reportId, int $driverId, Model $driver): DriverCollectionFailureReport
    {
        return DB::transaction(function () use ($reportId, $driverId, $driver): DriverCollectionFailureReport {
            $report = DriverCollectionFailureReport::query()
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->findOrFail($reportId);
            if (!self::canTransition($report->status, 'collection_succeeded')) {
                throw ValidationException::withMessages(['report' => 'لا توجد إعادة محاولة بانتظار تأكيد التحصيل.']);
            }

            $order = MerchantOrder::query()
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->findOrFail($report->order_id);
            $this->assertOrderCanBeReported($order);
            $this->assertNoConfirmedCollection($order);

            $this->recordCollection($order, $driverId, $driver, 'Cash collection confirmed by driver after retry.');

            $previous = $report->status;
            $report->update(['status' => 'collection_succeeded', 'resolved_at' => now()]);
            $this->record($report, $driver, 'collection_succeeded', $previous, 'collection_succeeded', $report->admin_instructions);

            return $report->fresh(['order', 'driver']);
        });
    }

    public function confirmOrderCollection(int $orderId, int $driverId, Model $driver): Payment
    {
        return DB::transaction(function () use ($orderId, $driverId, $driver): Payment {
            $order = MerchantOrder::query()
                ->whereKey($orderId)
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->firstOrFail();
            $status = $order->status instanceof MerchantOrderStatuses
                ? $order->status
                : MerchantOrderStatuses::tryFrom((string) $order->status);

            if ($status !== MerchantOrderStatuses::PREVIOUS
                || !MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
                throw ValidationException::withMessages([
                    'collection' => 'يقبل تأكيد التحصيل لطلب دفع عند الاستلام مكتمل فقط.',
                ]);
            }

            if ($order->collectionFailureReports()
                ->whereIn('status', ['open', 'retry_requested', 'return_requested', 'return_completed'])
                ->exists()) {
                throw ValidationException::withMessages([
                    'collection' => 'أكمل معالجة بلاغ تعذر التحصيل قبل تأكيد الاستلام.',
                ]);
            }

            $existingCollection = Payment::query()
                ->where('payable_id', $order->id)
                ->whereIn('payable_type', [
                    $order->getMorphClass(),
                    MerchantOrder::class,
                    'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
                ])
                ->where('source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE)
                ->whereIn('status', ['paid', 'success'])
                ->latest('id')
                ->first();

            if ($existingCollection) {
                if (app(\App\Domain\MarketPlace\Services\OrderPaymentLedger::class)
                    ->forOrder($order)['payment_status'] !== 'paid') {
                    throw ValidationException::withMessages([
                        'collection' => 'قيمة التحصيل لا تطابق المستحق الحالي؛ اطلب مراجعة الإدارة.',
                    ]);
                }

                return $existingCollection;
            }

            return $this->recordCollection(
                $order,
                $driverId,
                $driver,
                'Cash collection confirmed by driver after delivery.'
            );
        });
    }

    public function retryFailed(int $reportId, int $driverId, string $note, Model $driver): DriverCollectionFailureReport
    {
        return DB::transaction(function () use ($reportId, $driverId, $note, $driver): DriverCollectionFailureReport {
            $report = DriverCollectionFailureReport::query()
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->findOrFail($reportId);
            if (!self::canTransition($report->status, 'open')) {
                throw ValidationException::withMessages(['report' => 'لا يمكن إعادة البلاغ للمراجعة من حالته الحالية.']);
            }

            $order = MerchantOrder::query()
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->findOrFail($report->order_id);
            $this->assertOrderCanBeReported($order);

            $previous = $report->status;
            $report->update([
                'status' => 'open',
                'admin_instructions' => null,
                'decided_by' => null,
                'decided_at' => null,
            ]);
            $this->record($report, $driver, 'retry_failed', $previous, 'open', $note);

            return $report->fresh(['order', 'driver', 'histories.actor']);
        });
    }

    public function confirmReturn(int $reportId, int $driverId, Model $driver): DriverCollectionFailureReport
    {
        return DB::transaction(function () use ($reportId, $driverId, $driver): DriverCollectionFailureReport {
            $report = DriverCollectionFailureReport::query()
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->findOrFail($reportId);
            if (!self::canTransition($report->status, 'return_completed')) {
                throw ValidationException::withMessages(['report' => 'لا يوجد توجيه إرجاع بانتظار التوثيق.']);
            }

            $order = MerchantOrder::query()
                ->where('driver_id', $driverId)
                ->lockForUpdate()
                ->findOrFail($report->order_id);
            $this->assertOrderCanBeReported($order);

            $previous = $report->status;
            $report->update(['status' => 'return_completed', 'resolved_at' => now()]);
            $this->record($report, $driver, 'return_completed', $previous, 'return_completed', $report->admin_instructions);

            return $report->fresh(['order', 'driver']);
        });
    }

    public static function canTransition(?string $from, string $to): bool
    {
        $transitions = [
            null => ['open'],
            'open' => ['retry_requested', 'return_requested'],
            'retry_requested' => ['collection_succeeded', 'open'],
            'return_requested' => ['return_completed'],
        ];

        return in_array($to, $transitions[$from] ?? [], true);
    }

    public function assertOrderCanBeReported(MerchantOrder $order): void
    {
        $status = $order->status instanceof MerchantOrderStatuses
            ? $order->status
            : MerchantOrderStatuses::tryFrom((string) $order->status);

        if ($status !== MerchantOrderStatuses::PREVIOUS || !MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
            throw ValidationException::withMessages(['report' => 'يقبل البلاغ لطلب دفع عند الاستلام مكتمل فقط.']);
        }
        $this->assertNoConfirmedCollection($order);
    }

    private function assertNoConfirmedCollection(MerchantOrder $order): void
    {
        $exists = Payment::query()
            ->where('payable_id', $order->id)
            ->whereIn('payable_type', [$order->getMorphClass(), MerchantOrder::class, 'App\\Domain\\MarketPlace\\Models\\MerchantOrder'])
            ->where('source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE)
            ->whereIn('status', ['paid', 'success'])
            ->exists();
        if ($exists) {
            throw ValidationException::withMessages(['report' => 'الطلب مسجل كمحصّل بالفعل.']);
        }
    }

    private function recordCollection(
        MerchantOrder $order,
        int $driverId,
        Model $driver,
        string $description
    ): Payment {
        $amount = round((float) ($order->total_after_discount > 0
            ? $order->total_after_discount
            : $order->total_after_vat), 2);
        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'collection' => 'لا يمكن تسجيل تحصيل لطلب بقيمة صفرية.',
            ]);
        }

        $timestamp = now()->toIso8601String();
        $amountInHalalas = (int) round($amount * 100);
        $amountFormat = number_format($amount, 2) . ' SAR';

        return $order->payment()->updateOrCreate(
            ['payment_id' => 'COD-' . ($order->code ?: $order->id)],
            [
                'status' => 'paid',
                'amount' => $amountInHalalas,
                'currency' => 'SAR',
                'captured' => $amountInHalalas,
                'capturedAt' => $timestamp,
                'description' => $description,
                'amountFormat' => $amountFormat,
                'capturedFormat' => $amountFormat,
                'source_type' => 'cash',
                'source_name' => $driver->name,
                'source_number' => (string) $driverId,
                'source_message' => MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE,
                'user_id' => $order->user_id,
                'channel' => $order->channel,
                'createdAt' => $timestamp,
                'updatedAt' => $timestamp,
            ]
        );
    }

    private function record(
        DriverCollectionFailureReport $report,
        Model $actor,
        string $action,
        ?string $from,
        string $to,
        ?string $note
    ): void {
        DriverCollectionFailureReportHistory::create([
            'report_id' => $report->id,
            'action' => $action,
            'status_from' => $from,
            'status_to' => $to,
            'note' => $note,
            'actor_type' => $actor->getMorphClass(),
            'actor_id' => $actor->getKey(),
            'created_at' => now(),
        ]);
    }
}
