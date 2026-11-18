<?php

namespace App\Domain\MarketPlace\Services;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use Domain\AppBase\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DriverSettlementSubmissionWorkflow
{
    public function eligibleCollections(int $driverId, ?int $returnedBatchId = null): Collection
    {
        $paymentRows = Payment::query()
            ->join('merchant_orders as orders', 'orders.id', '=', 'payments.payable_id')
            ->where('orders.driver_id', $driverId)
            ->where('orders.status', MerchantOrderStatuses::PREVIOUS->value)
            ->whereIn('payments.payable_type', [
                (new MerchantOrder())->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ])
            ->where('payments.source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE)
            ->whereIn('payments.status', ['paid', 'success'])
            ->select('payments.*')
            ->orderBy('payments.id')
            ->get()
            ->groupBy('payable_id');

        if ($paymentRows->isEmpty()) {
            return collect();
        }

        $orders = MerchantOrder::query()
            ->where('driver_id', $driverId)
            ->whereIn('id', $paymentRows->keys())
            ->with('settlement')
            ->get()
            ->keyBy('id');
        $returnedSettlementIds = $returnedBatchId
            ? OrderSettlement::batchQuery($returnedBatchId)->pluck('id')->map(fn ($id) => (int) $id)->all()
            : [];

        $ledger = app(OrderPaymentLedger::class);
        $custody = app(DriverCustodyLedger::class);
        $eligible = collect();

        foreach ($orders as $order) {
            if (!MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
                continue;
            }

            $settlement = $order->settlement;
            $batchId = $settlement?->batch_number;
            $isNewRequest = !$settlement || $settlement->status === 'rejected';
            $isReturnedBatch = $returnedBatchId
                && $batchId === $returnedBatchId
                && $settlement?->status === 'returned';
            if (!$isNewRequest && !$isReturnedBatch) {
                continue;
            }

            $latestFailure = $order->collectionFailureReports()->latest('id')->first();
            if (in_array($latestFailure?->status, [
                'open',
                'retry_requested',
                'return_requested',
                'return_completed',
            ], true)) {
                continue;
            }

            $paymentLedger = $ledger->forOrder($order);
            if ($paymentLedger['payment_status'] !== 'paid'
                || abs($custody->balanceForOrder($order->id, $driverId) - $paymentLedger['due']) > 0.009) {
                continue;
            }

            $config = $order->collection_config ?? [];
            $methodData = $config['methods'] ?? [];
            $confirmedIds = collect($paymentLedger['movements'])
                ->where('type', 'collection')
                ->pluck('record_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            foreach ($paymentRows->get($order->id, collect()) as $payment) {
                if (!in_array((int) $payment->id, $confirmedIds, true)) {
                    continue;
                }
                if ($isReturnedBatch
                    && $payment->settlement_id
                    && !in_array((int) $payment->settlement_id, $returnedSettlementIds, true)) {
                    continue;
                }
                if ($isNewRequest && $payment->settlement_id) {
                    $linked = OrderSettlement::query()->find($payment->settlement_id);
                    if ($linked && !in_array($linked->status, ['rejected'], true)) {
                        continue;
                    }
                }

                $movement = collect($paymentLedger['movements'])->first(
                    fn (array $candidate): bool => (int) $candidate['record_id'] === (int) $payment->id
                );
                if (!$movement) {
                    continue;
                }

                $eligible->push([
                    'payment_id' => (int) $payment->id,
                    'payment_number' => $payment->payment_id,
                    'order_id' => (int) $order->id,
                    'order_code' => $order->code,
                    'amount' => (float) $movement['amount'],
                    'due' => (float) $paymentLedger['due'],
                    'collected' => (float) $paymentLedger['collected'],
                    'occurred_at' => $payment->capturedAt ?: $payment->created_at,
                    'recipient' => $config['recipient'] ?? null,
                    'enabled_methods' => $config['enabled_methods'] ?? [],
                    'methods' => $methodData,
                    'settlement_id' => $settlement?->id,
                    'batch_id' => $batchId,
                    'status' => $settlement?->status,
                ]);
            }
        }

        return $eligible->sortByDesc('occurred_at')->values();
    }

    public function submit(
        int $driverId,
        array $paymentIds,
        string $method,
        ?string $reference,
        ?string $proofPath,
        string $supplyDate,
        \Illuminate\Database\Eloquent\Model $actor,
        ?int $returnedBatchId = null
    ): array {
        Validator::make([
            'payment_ids' => $paymentIds,
            'method' => $method,
            'reference' => $reference,
            'supply_date' => $supplyDate,
        ], [
            'payment_ids' => ['required', 'array', 'min:1'],
            'payment_ids.*' => ['required', 'integer', 'min:1'],
            'method' => ['required', 'in:cash,transfer,deposit'],
            'reference' => ['nullable', 'string', 'max:255'],
            'supply_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ])->validate();

        $paymentIds = array_map('intval', $paymentIds);
        if (count($paymentIds) !== count(array_unique($paymentIds))) {
            $this->reject('payment_ids', 'تحتوي القائمة على تحصيل مكرر.');
        }
        if ($paymentIds === []) {
            throw ValidationException::withMessages([
                'selectedCollectionIds' => 'حدد تحصيلًا واحدًا على الأقل.',
            ]);
        }
        if (OrderSettlement::requiresProof($method) && (trim((string) $reference) === '' || !$proofPath)) {
            $this->reject('settlementProof', 'يتطلب التحويل أو الإيداع رقم المرجع وإثبات التوريد.');
        }

        $batchId = DB::transaction(function () use (
            $driverId,
            $paymentIds,
            $method,
            $reference,
            $proofPath,
            $supplyDate,
            $actor,
            $returnedBatchId,
        ): int {
            $payments = Payment::query()
                ->whereKey($paymentIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            if ($payments->count() !== count($paymentIds)) {
                $this->reject('selectedCollectionIds', 'تغيرت أهلية بعض التحصيلات. حدّث الصفحة ثم أعد الاختيار.');
            }

            $orderIds = $payments->pluck('payable_id')->map(fn ($id) => (int) $id)->unique()->sort()->values();
            $orders = MerchantOrder::query()
                ->where('driver_id', $driverId)
                ->whereKey($orderIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            if ($orders->count() !== $orderIds->count()) {
                $this->reject('selectedCollectionIds', 'بعض التحصيلات لا تخص حساب السائق الحالي.');
            }

            $candidates = $this->eligibleCollections($driverId, $returnedBatchId)->keyBy('payment_id');
            foreach ($paymentIds as $paymentId) {
                if (!$candidates->has($paymentId)) {
                    $this->reject('selectedCollectionIds', 'تغيرت أهلية بعض التحصيلات. حدّث الصفحة ثم أعد الاختيار.');
                }
            }

            $selected = $candidates->only($paymentIds)->values();
            foreach ($selected->groupBy('order_id') as $orderId => $orderCollections) {
                $availableIds = $candidates->where('order_id', (int) $orderId)->pluck('payment_id')->sort()->values();
                $selectedIds = $orderCollections->pluck('payment_id')->sort()->values();
                if ($availableIds->all() !== $selectedIds->all()) {
                    $this->reject('selectedCollectionIds', 'يجب اختيار جميع حركات التحصيل للطلب نفسه دون تجزئة.');
                }
            }

            $recipientIds = $selected->pluck('recipient')->unique()->values();
            if ($recipientIds->count() !== 1 || !$recipientIds->first()) {
                $this->reject('selectedCollectionIds', 'لا يمكن جمع تحصيلات لجهات استلام مختلفة أو غير محددة.');
            }
            foreach ($selected as $collection) {
                if (!in_array($method, $collection['enabled_methods'], true)) {
                    $this->reject('settlementMethod', 'وسيلة التوريد غير مفعّلة لجميع التحصيلات المحددة.');
                }
                $methodData = $collection['methods'][$method] ?? [];
                if (empty($methodData['recipient_name']) || empty($methodData['instructions'])
                    || (OrderSettlement::requiresProof($method) && empty($methodData['account_data']))) {
                    $this->reject('settlementMethod', 'إعدادات جهة الاستلام لهذه الوسيلة غير مكتملة. تواصل مع الإدارة.');
                }
            }

            $destinations = $selected->map(fn (array $collection) => [
                'recipient' => $collection['recipient'],
                'method' => $method,
                'method_data' => $collection['methods'][$method] ?? [],
            ])->unique(fn (array $destination) => sha1(json_encode($destination)))->values();
            if ($destinations->count() !== 1) {
                $this->reject('selectedCollectionIds', 'لا يمكن جمع تحصيلات تختلف بيانات جهة استلامها أو حسابها.');
            }

            $destination = $destinations->first();
            $methodData = $destination['method_data'];
            $items = $selected->groupBy('order_id');

            if ($returnedBatchId) {
                $settlements = OrderSettlement::batchQuery($returnedBatchId)
                    ->where('driver_id', $driverId)
                    ->lockForUpdate()
                    ->get();
                if ($settlements->isEmpty() || $settlements->contains(
                    fn (OrderSettlement $settlement): bool => $settlement->status !== 'returned'
                )) {
                    $this->reject('settlement', 'لم تعد هذه المجموعة متاحة للاستكمال.');
                }

                if ($settlements->pluck('order_id')->sort()->values()->all()
                        !== $items->keys()->map(fn ($id) => (int) $id)->sort()->values()->all()) {
                    $this->reject('selectedCollectionIds', 'يجب استكمال جميع تحصيلات التسوية المعادة نفسها.');
                }
                $settlementIds = $settlements->pluck('id');
                $linkedPaymentIds = Payment::query()
                    ->whereIn('settlement_id', $settlementIds)
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
                if (array_diff($linkedPaymentIds, $paymentIds) !== []) {
                    $this->reject('selectedCollectionIds', 'تغيرت حركات التحصيل المرتبطة بالتسوية المعادة.');
                }

                foreach ($settlements as $settlement) {
                    $settlement->update([
                        'recipient' => $destination['recipient'],
                        'method' => $method,
                        'recipient_name' => $methodData['recipient_name'],
                        'instructions' => $methodData['instructions'],
                        'account_data' => $methodData['account_data'] ?? null,
                        'amount' => $items->get($settlement->order_id)->sum('due'),
                        'reference' => $reference,
                        'proof_path' => $proofPath,
                        'supply_date' => $supplyDate,
                        'status' => 'pending_review',
                        'reviewed_by' => null,
                        'reviewed_at' => null,
                        'approved_by' => null,
                        'approved_at' => null,
                    ]);
                    app(SettlementWorkflow::class)->recordSubmission(
                        $settlement,
                        $actor,
                        'resubmitted',
                        'returned',
                        $this->snapshot(
                            $settlement,
                            $items->get($settlement->order_id)->pluck('payment_id')->all()
                        )
                    );
                    $selectedIdsForOrder = $items->get($settlement->order_id)->pluck('payment_id');
                    Payment::query()->whereKey($selectedIdsForOrder)->update([
                        'settlement_id' => $settlement->id,
                    ]);
                    PaymentCorrectionRequest::query()
                        ->where('order_id', $settlement->order_id)
                        ->where('movement_type', 'collection')
                        ->whereNull('settlement_id')
                        ->update(['settlement_id' => $settlement->id]);
                }

                return $returnedBatchId;
            }

            $created = collect();
            foreach ($items as $orderId => $orderCollections) {
                $amount = round((float) $orderCollections->first()['due'], 2);
                $collectionTotal = round((float) $orderCollections->sum('amount'), 2);
                if (abs($collectionTotal - $amount) > 0.009
                    || $orderCollections->contains(fn (array $collection) =>
                        abs($collection['collected'] - $amount) > 0.009)) {
                    $this->reject('selectedCollectionIds', 'يجب أن يطابق مجموع التحصيل كامل مستحق الطلب.');
                }

                $created->push(OrderSettlement::query()->create([
                    'order_id' => (int) $orderId,
                    'driver_id' => $driverId,
                    'recipient' => $destination['recipient'],
                    'method' => $method,
                    'recipient_name' => $methodData['recipient_name'],
                    'instructions' => $methodData['instructions'],
                    'account_data' => $methodData['account_data'] ?? null,
                    'amount' => $amount,
                    'reference' => $reference,
                    'proof_path' => $proofPath,
                    'supply_date' => $supplyDate,
                    'status' => 'pending_review',
                ]));
            }

            $batchId = (int) $created->first()->id;
            foreach ($created as $settlement) {
                $settlement->update(['batch_id' => $batchId]);
                $settlement->refresh();
                app(SettlementWorkflow::class)->recordSubmission(
                    $settlement,
                    $actor,
                    'submitted',
                    null,
                    $this->snapshot(
                        $settlement,
                        $items->get($settlement->order_id)->pluck('payment_id')->all()
                    )
                );
                $selectedIdsForOrder = $items->get($settlement->order_id)->pluck('payment_id');
                Payment::query()->whereKey($selectedIdsForOrder)->update([
                    'settlement_id' => $settlement->id,
                ]);
                PaymentCorrectionRequest::query()
                    ->where('order_id', $settlement->order_id)
                    ->where('movement_type', 'collection')
                    ->whereNull('settlement_id')
                    ->update(['settlement_id' => $settlement->id]);
            }

            return $batchId;
        });

        return [
            'batch_id' => $batchId,
        ];
    }

    private function snapshot(OrderSettlement $settlement, array $paymentRecordIds): array
    {
        return [
            'recipient' => $settlement->recipient,
            'method' => $settlement->method,
            'recipient_name' => $settlement->recipient_name,
            'instructions' => $settlement->instructions,
            'account_data' => $settlement->account_data,
            'amount' => $settlement->amount,
            'reference' => $settlement->reference,
            'proof_path' => $settlement->proof_path,
            'supply_date' => $settlement->supply_date?->toDateString(),
            'batch_id' => $settlement->batch_number,
            'payment_record_ids' => $paymentRecordIds,
        ];
    }

    private function reject(string $field, string $message): never
    {
        throw ValidationException::withMessages([$field => $message]);
    }
}
