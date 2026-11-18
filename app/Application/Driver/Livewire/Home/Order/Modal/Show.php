<?php

namespace Application\Driver\Livewire\Home\Order\Modal;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Services\CollectionFailureWorkflow;
use App\Domain\MarketPlace\Services\SettlementWorkflow;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Services\OrderPaymentLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Show extends Component
{
    use LivewireAlert, WithFileUploads;

    public $listeners = ['showOrderDetails', 'showCollectionFailure'];

    public array $order = [];
    public array $items = [];
    public $settlementMethod;
    public $settlementReference;
    public $settlementProof;
    public string $collectionFailureReason = '';
    public string $collectionFailureNote = '';
    public bool $showCollectionFailureForm = false;
    public string $retryFailureNote = '';
    public bool $deliveryAcknowledged = false;
    public bool $collectionAcknowledged = false;
    public bool $requiresCollectionConfirmation = false;

    public function showOrderDetails($orderId): void
    {
        $order = MerchantOrder::with([
            'user',
            'orderItems.orderChoices.choice',
            'branch',
            'merchant',
            'address',
        ])->where('driver_id', auth()->guard('driver')->id())->find($orderId);

        if (!$order) {
            return;
        }

        $status = $order->status;
        $statusEnum = $status instanceof MerchantOrderStatuses ? $status : MerchantOrderStatuses::tryFrom($status);
        $paymentLedger = app(OrderPaymentLedger::class)->forOrder($order);

        $addressInOrder = $order->addressInOrder;
        if (is_string($addressInOrder)) {
            $decoded = json_decode($addressInOrder);
            $addressInOrder = is_object($decoded) ? $decoded : $addressInOrder;
        }
        $addressText = $order->address?->address
            ?? (is_object($addressInOrder) ? ($addressInOrder->address ?? null) : $addressInOrder);
        $clientLat = $order->client_lat ?? $order->address?->latitude;
        $clientLong = $order->client_long ?? $order->address?->longitude;
        $mapUrl = is_numeric($clientLat) && is_numeric($clientLong)
            ? 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($clientLat . ',' . $clientLong)
            : ($addressText ? 'https://maps.google.com/?q=' . urlencode((string) $addressText) : null);

        $this->order = [
            'id' => $order->id,
            'code' => $order->code,
            'status_value' => $statusEnum?->value,
            'status_trans' => $statusEnum?->trans() ?? $status,
            'status_color' => $statusEnum?->color() ?? 'secondary',
            'created_at' => Carbon::parse($order->created_at)->format('Y-m-d H:i'),
            'user_name' => $order->user?->name,
            'user_phone' => $order->user?->phone_number,
            'user_email' => $order->user?->email,
            'client_name' => $order->client_name,
            'client_phone' => $order->client_phone,
            'merchant_name' => $order->merchant?->company_name ?? $order->merchant?->name ?? $order->merchant_name,
            'branch_name' => $order->branch?->title,
            'address' => $addressText,
            'map_url' => $mapUrl,
            'subtotal' => $order->subtotal,
            'delivery_fee' => $order->delivery_fee,
            'vat' => $order->vat,
            'discount' => $order->discount,
            'total_after_vat' => $order->total_after_vat,
            'total_after_discount' => $order->total_after_discount,
            'delivery_distance' => $order->delivery_distance,
            'payment_method' => $order->payment_method,
            'payment_status' => $paymentLedger['payment_status'],
            'payment_label' => $paymentLedger['payment_label'],
            'collection_remaining' => $paymentLedger['remaining'],
            'prepaid_amount' => $paymentLedger['collected'],
            'prepaid_method' => $paymentLedger['payment_method'],
            'prepaid_reference' => $paymentLedger['payment']?->payment_id,
            'collection_config' => $order->collection_config ?? [],
            'settlement_status' => $order->settlement?->status,
            'settlement_method' => $order->settlement?->method,
            'settlement_custody' => $order->settlement ? 'لدى السائق' : null,
            'settlement_id' => $order->settlement?->batch_number,
            'settlement_reference' => $order->settlement?->reference,
            'settlement_amount' => $order->settlement?->amount,
            'settlement_recipient_name' => $order->settlement?->recipient_name,
            'driver_cod_collection' => $order->payment?->source_message === MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE
                && MerchantOrder::resolvePaymentStatus($order->payment, 'unpaid', $order->payment_method) === 'paid'
                && $paymentLedger['payment_status'] === 'paid',
            'has_driver_cod_collection' => $order->payment?->source_message === MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE
                && MerchantOrder::resolvePaymentStatus($order->payment, 'unpaid', $order->payment_method) === 'paid',
            'settlement_history' => $order->settlement?->histories()
                ->with('actor')
                ->get()
                ->map(fn ($history) => [
                    'action' => $history->action,
                    'status_to' => $history->status_to,
                    'note' => $history->note,
                    'actor' => $history->actor?->name,
                    'created_at' => $history->created_at?->format('Y-m-d H:i'),
                ])
                ->all() ?? [],
            'collection_failure_report' => $order->collectionFailureReports()
                ->with('histories.actor')
                ->latest('id')
                ->first()?->toArray(),
            'payment_source_name' => $order->payment?->source_name,
            'note' => $order->note,
            'order_type' => $order->type_service_name(),
            'schedulable' => $order->schedulable,
            'date' => $order->date ? Carbon::parse($order->date)->format('Y-m-d H:i') : null,
            'from' => $order->from ? Carbon::parse($order->from)->format('H:i') : null,
            'to' => $order->to ? Carbon::parse($order->to)->format('H:i') : null,
        ];
        $this->settlementMethod = $this->order['settlement_method']
            ?? ($this->order['collection_config']['enabled_methods'][0] ?? null);
        $this->settlementReference = $this->order['settlement_reference'];
        $this->settlementProof = null;
        $this->deliveryAcknowledged = false;
        $this->collectionAcknowledged = false;
        $this->collectionFailureReason = '';
        $this->collectionFailureNote = '';
        $this->showCollectionFailureForm = false;
        $this->requiresCollectionConfirmation = $paymentLedger['payment_status'] !== 'paid';

        $this->items = $order->orderItems->map(function ($item) {
            $addons = collect($item->orderChoices)
                ->map(fn($choice) => $choice->choice?->title)
                ->filter()
                ->implode(' - ');

            return [
                'item_name' => $item->item_name,
                'qty' => $item->qty,
                'price' => $item->itemPrice,
                'total' => $item->item_total,
                'addons' => $addons,
            ];
        })->values()->toArray();

        $this->emit('openModal', '#showOrderDetailsModal');
    }

    public function showCollectionFailure($orderId): void
    {
        $this->showOrderDetails($orderId);

        if (($this->order['status_value'] ?? null) === MerchantOrderStatuses::ON_WAY->value
            && MerchantOrder::isCashOnDeliveryMethod($this->order['payment_method'] ?? null)
            && ($this->order['payment_status'] ?? null) !== 'paid'
            && empty($this->order['collection_failure_report'])) {
            $this->showCollectionFailureForm = true;
        }
    }

    public function refreshPaymentStatus(): void
    {
        $orderId = $this->order['id'] ?? null;
        if (!$orderId) {
            return;
        }

        $order = MerchantOrder::where('driver_id', auth()->guard('driver')->id())->find($orderId);
        if (!$order) {
            return;
        }

        $this->order['payment_method'] = $order->payment_method;
        $paymentLedger = app(OrderPaymentLedger::class)->forOrder($order);
        $this->order['payment_status'] = $paymentLedger['payment_status'];
        $this->order['payment_label'] = $paymentLedger['payment_label'];
        $this->order['collection_remaining'] = $paymentLedger['remaining'];
        $this->order['prepaid_amount'] = $paymentLedger['collected'];
        $this->order['prepaid_method'] = $paymentLedger['payment_method'];
        $this->order['prepaid_reference'] = $paymentLedger['payment']?->payment_id;
        $this->order['total_after_discount'] = $order->total_after_discount;
        $this->requiresCollectionConfirmation = $paymentLedger['payment_status'] !== 'paid';
        $this->order['settlement_status'] = $order->settlement?->status;
        $this->order['settlement_method'] = $order->settlement?->method;
        $this->order['settlement_custody'] = $order->settlement ? 'لدى السائق' : null;
        $this->order['settlement_reference'] = $order->settlement?->reference;
        $this->order['settlement_amount'] = $order->settlement?->amount;
        $this->order['settlement_id'] = $order->settlement?->batch_number;
        $this->order['settlement_history'] = $order->settlement?->histories()
            ->with('actor')
            ->get()
            ->map(fn ($history) => [
                'action' => $history->action,
                'status_to' => $history->status_to,
                'note' => $history->note,
                'actor' => $history->actor?->name,
                'created_at' => $history->created_at?->format('Y-m-d H:i'),
            ])
            ->all() ?? [];
        $this->order['payment_source_name'] = $order->payment?->source_name;
        $this->order['has_driver_cod_collection'] = $order->payment?->source_message === MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE
            && MerchantOrder::resolvePaymentStatus($order->payment, 'unpaid', $order->payment_method) === 'paid';
        $this->order['driver_cod_collection'] = $this->order['has_driver_cod_collection']
            && $paymentLedger['payment_status'] === 'paid';
        $this->order['collection_failure_report'] = $order->collectionFailureReports()
            ->with('histories.actor')
            ->latest('id')
            ->first()?->toArray();
    }

    public function reportCollectionFailure(CollectionFailureWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        if (!$driver || empty($this->order['id'])) {
            abort(403);
        }

        $data = $this->validate([
            'collectionFailureReason' => ['required', Rule::in(CollectionFailureWorkflow::REASONS)],
            'collectionFailureNote' => ['required_if:collectionFailureReason,other', 'nullable', 'string', 'max:2000'],
        ]);
        try {
            $report = $workflow->report(
                (int) $this->order['id'],
                (int) $driver->id,
                $data['collectionFailureReason'],
                $data['collectionFailureNote'] ?: null,
                $driver
            );
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?: $exception->getMessage();
            $this->alert('warning', $message);
            return;
        } catch (\Throwable $exception) {
            Log::error('Driver collection failure report submission failed.', [
                'order_id' => $this->order['id'],
                'driver_id' => $driver->id,
                'exception' => $exception,
            ]);
            $this->alert('error', 'تعذر إرسال بلاغ تعذر التحصيل. لم تُسجل حركة مالية؛ حاول مرة أخرى.');
            return;
        }

        $report->load('histories.actor');
        $this->collectionFailureReason = '';
        $this->collectionFailureNote = '';
        $this->showCollectionFailureForm = false;
        $this->order['collection_failure_report'] = $report->toArray();
        $this->alert('success', 'تم إرسال بلاغ تعذر التحصيل للإدارة.');
    }

    public function cancelCollectionFailure(): void
    {
        $this->collectionFailureReason = '';
        $this->collectionFailureNote = '';
        $this->showCollectionFailureForm = false;
    }

    public function confirmRetryCollection(CollectionFailureWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        $reportId = $this->order['collection_failure_report']['id'] ?? null;
        if (!$driver || !$reportId) {
            abort(403);
        }

        $report = $workflow->confirmCollection((int) $reportId, (int) $driver->id, $driver);
        $report->load('histories.actor');
        $this->refreshPaymentStatus();
        $this->order['collection_failure_report'] = $report->toArray();
        $this->alert('success', 'تم تأكيد التحصيل وإغلاق البلاغ. أرسل التوريد للمراجعة من نموذج التسوية.');
    }

    public function confirmCashCollection(CollectionFailureWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        $orderId = $this->order['id'] ?? null;
        if (!$driver || !$orderId) {
            abort(403);
        }

        try {
            $workflow->confirmOrderCollection((int) $orderId, (int) $driver->id, $driver);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->alert('warning', $exception->errors()['collection'][0] ?? $exception->getMessage());
            return;
        }

        $this->refreshPaymentStatus();
        $this->alert('success', 'تم تأكيد استلام المبلغ وتسجيل التحصيل.');
    }

    public function confirmDeliveryAndCollection(CollectionFailureWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        $orderId = $this->order['id'] ?? null;
        if (!$driver || !$orderId) {
            abort(403);
        }

        $rules = [
            'deliveryAcknowledged' => ['accepted'],
            'collectionAcknowledged' => $this->requiresCollectionConfirmation
                ? ['accepted']
                : ['nullable', 'boolean'],
        ];
        $this->validate($rules);

        try {
            $order = $workflow->completeDeliveryAndCollection(
                (int) $orderId,
                (int) $driver->id,
                $driver,
                $this->deliveryAcknowledged,
                $this->collectionAcknowledged
            );
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?: $exception->getMessage();
            $this->alert('warning', $message);
            return;
        } catch (\Throwable $exception) {
            Log::error('Driver delivery and collection confirmation failed.', [
                'order_id' => $orderId,
                'driver_id' => $driver->id,
                'exception' => $exception,
            ]);
            $this->alert('error', 'تعذر حفظ تأكيد التسليم والتحصيل. لم يتم تغيير حالة الطلب؛ حاول مرة أخرى.');
            return;
        }

        $status = $order->status instanceof MerchantOrderStatuses
            ? $order->status
            : MerchantOrderStatuses::tryFrom((string) $order->status);
        $this->order['status_value'] = $status?->value;
        $this->order['status_trans'] = $status?->trans() ?? (string) $order->status;
        $this->order['status_color'] = $status?->color() ?? 'secondary';
        $this->refreshPaymentStatus();
        $this->alert('success', 'تم تأكيد التسليم والتحصيل وإكمال الطلب.');
        $this->dispatchBrowserEvent('driver-order-delivery-completed', ['orderId' => (int) $orderId]);
    }

    public function confirmPrepaidDelivery(CollectionFailureWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        $orderId = $this->order['id'] ?? null;
        if (!$driver || !$orderId) {
            abort(403);
        }

        $this->validate([
            'deliveryAcknowledged' => ['accepted'],
        ]);

        try {
            $order = $workflow->completePrepaidDelivery(
                (int) $orderId,
                (int) $driver->id,
                $driver,
                $this->deliveryAcknowledged
            );
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?: $exception->getMessage();
            $this->alert('warning', $message);
            return;
        } catch (\Throwable $exception) {
            Log::error('Driver prepaid delivery confirmation failed.', [
                'order_id' => $orderId,
                'driver_id' => $driver->id,
                'exception' => $exception,
            ]);
            $this->alert('error', 'تعذر حفظ تأكيد التسليم. لم يتم تغيير حالة الطلب؛ حاول مرة أخرى.');
            return;
        }

        $status = $order->status instanceof MerchantOrderStatuses
            ? $order->status
            : MerchantOrderStatuses::tryFrom((string) $order->status);
        $this->order['status_value'] = $status?->value;
        $this->order['status_trans'] = $status?->trans() ?? (string) $order->status;
        $this->order['status_color'] = $status?->color() ?? 'secondary';
        $this->refreshPaymentStatus();
        $this->alert('success', 'تم تأكيد تسليم الطلب المدفوع مسبقًا.');
        $this->dispatchBrowserEvent('driver-order-delivery-completed', ['orderId' => (int) $orderId]);
    }

    public function reportRetryFailure(CollectionFailureWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        $reportId = $this->order['collection_failure_report']['id'] ?? null;
        if (!$driver || !$reportId) {
            abort(403);
        }

        $data = $this->validate([
            'retryFailureNote' => ['required', 'string', 'max:2000'],
        ]);
        $report = $workflow->retryFailed((int) $reportId, (int) $driver->id, $data['retryFailureNote'], $driver);
        $this->retryFailureNote = '';
        $report->load('histories.actor');
        $this->order['collection_failure_report'] = $report->toArray();
        $this->alert('success', 'تم تحديث البلاغ وإعادته للإدارة مع ملاحظتك.');
    }

    public function confirmReturnCompleted(CollectionFailureWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        $reportId = $this->order['collection_failure_report']['id'] ?? null;
        if (!$driver || !$reportId) {
            abort(403);
        }

        $report = $workflow->confirmReturn((int) $reportId, (int) $driver->id, $driver);
        $report->load('histories.actor');
        $this->order['collection_failure_report'] = $report->toArray();
        $this->alert('success', 'تم توثيق اكتمال إرجاع الطلب وإغلاق البلاغ دون إنشاء استرداد مالي.');
    }

    public function submitSettlement(SettlementWorkflow $workflow): void
    {
        $orderId = $this->order['id'] ?? null;
        $driverId = auth()->guard('driver')->id();
        if (!$driverId) {
            abort(403);
        }

        $order = MerchantOrder::where('driver_id', $driverId)->find($orderId);

        if (!$order || $order->status !== MerchantOrderStatuses::PREVIOUS
            || !MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
            $this->alert('error', 'لا يمكن تسجيل التوريد لهذا الطلب.');
            return;
        }
        $existingSettlement = $order->settlement;
        $isResubmission = $existingSettlement?->status === 'returned';
        $isNewAfterRejection = $existingSettlement?->status === 'rejected';
        if ($existingSettlement && !$isResubmission && !$isNewAfterRejection) {
            $this->alert('warning', 'لا يمكن إرسال هذه التسوية مرة أخرى في حالتها الحالية.');
            return;
        }
        $existingPayment = $order->payment()->latest('id')->first();
        $paymentLedger = app(OrderPaymentLedger::class)->forOrder($order);
        $latestFailureReport = $order->collectionFailureReports()->latest('id')->first();
        if (in_array($latestFailureReport?->status, ['open', 'retry_requested', 'return_requested', 'return_completed'], true)) {
            $this->alert('warning', 'أكمل معالجة بلاغ تعذر التحصيل قبل إرسال التسوية.');
            return;
        }
        $existingDriverCollection = $existingPayment
            && $existingPayment->source_message === MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE
            && MerchantOrder::resolvePaymentStatus($existingPayment, 'unpaid', $order->payment_method) === 'paid';
        if (!$existingDriverCollection) {
            $this->alert('warning', 'أكد استلام المبلغ قبل إرسال التوريد.');
            return;
        }
        if ($paymentLedger['payment_status'] !== 'paid') {
            $this->alert('warning', 'قيمة التحصيل المصححة لا تطابق المستحق؛ أوقف إرسال التوريد للمراجعة.');
            return;
        }

        $amount = round((float) ($order->total_after_discount > 0
            ? $order->total_after_discount
            : $order->total_after_vat), 2);
        if ($amount <= 0) {
            $this->alert('error', 'لا يمكن تسجيل توريد لطلب بقيمة صفرية.');
            return;
        }

        $config = $order->collection_config ?? [];
        $enabledMethods = array_values(array_intersect(
            $config['enabled_methods'] ?? [],
            ['cash', 'transfer', 'deposit']
        ));
        if (!in_array($this->settlementMethod, $enabledMethods, true)) {
            $this->addError('settlementMethod', 'وسيلة التوريد المحددة غير مفعلة لهذا الطلب.');
            return;
        }

        $needsProof = OrderSettlement::requiresProof((string) $this->settlementMethod);
        $validated = $this->validate([
            'settlementMethod' => ['required', Rule::in($enabledMethods)],
            'settlementReference' => [$needsProof ? 'required' : 'nullable', 'string', 'max:255'],
            'settlementProof' => [$needsProof ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $methodData = $config['methods'][$this->settlementMethod] ?? [];
        if (empty($config['recipient']) || empty($methodData['recipient_name']) || empty($methodData['instructions'])
            || ($needsProof && empty($methodData['account_data']))) {
            $this->alert('error', 'إعدادات جهة الاستلام لهذه الوسيلة غير مكتملة.');
            return;
        }

        $proofPath = null;
        if ($needsProof) {
            $proofPath = $this->settlementProof->store('settlements', 'settlement_proofs');
            if (!$proofPath) {
                $this->alert('error', 'تعذر حفظ إثبات التوريد. حاول مرة أخرى.');
                return;
            }
        }

        try {
            $settlementId = DB::transaction(function () use (
                $order,
                $driverId,
                $config,
                $methodData,
                $validated,
                $proofPath,
                $workflow
            ): ?int {
            $lockedOrder = MerchantOrder::where('driver_id', $driverId)
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ($lockedOrder->status !== MerchantOrderStatuses::PREVIOUS
                || !MerchantOrder::isCashOnDeliveryMethod($lockedOrder->payment_method)) {
                return null;
            }

            $settlement = $lockedOrder->settlement()->lockForUpdate()->first();
            $latestFailureReport = $lockedOrder->collectionFailureReports()
                ->lockForUpdate()
                ->latest('id')
                ->first();
            if (in_array($latestFailureReport?->status, ['open', 'retry_requested', 'return_requested', 'return_completed'], true)) {
                return null;
            }
            $resubmitting = $settlement?->status === 'returned';
            $newAfterRejection = $settlement?->status === 'rejected';
            if ($settlement && !$resubmitting && !$newAfterRejection) {
                return null;
            }

            $amount = round((float) ($lockedOrder->total_after_discount > 0
                ? $lockedOrder->total_after_discount
                : $lockedOrder->total_after_vat), 2);
            if ($amount <= 0) {
                return null;
            }

            $existingPayment = $lockedOrder->payment()->latest('id')->first();
            $existingDriverCollection = $existingPayment
                && $existingPayment->source_message === MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE
                && MerchantOrder::resolvePaymentStatus($existingPayment, 'unpaid', $lockedOrder->payment_method) === 'paid';
            $lockedPaymentLedger = app(OrderPaymentLedger::class)->forOrder($lockedOrder);
            if (!$existingDriverCollection || $lockedPaymentLedger['payment_status'] !== 'paid') {
                return null;
            }
            if ($resubmitting || $newAfterRejection) {
                if ((int) $settlement->driver_id !== (int) $driverId) {
                    return null;
                }
            }

            $previousStatus = $resubmitting ? $settlement->status : null;
            if ($resubmitting && !SettlementWorkflow::canTransition($settlement->status, 'pending_review')) {
                return null;
            }
            $settlementAttributes = [
                'driver_id' => $driverId,
                'recipient' => $config['recipient'],
                'method' => $validated['settlementMethod'],
                'recipient_name' => $methodData['recipient_name'],
                'instructions' => $methodData['instructions'],
                'account_data' => $methodData['account_data'] ?? null,
                'amount' => $amount,
                'reference' => $validated['settlementReference'] ?? null,
                'proof_path' => $proofPath,
                'status' => 'pending_review',
                'reviewed_by' => null,
                'reviewed_at' => null,
                'approved_by' => null,
                'approved_at' => null,
            ];
            if ($resubmitting) {
                $settlement->update($settlementAttributes);
            } else {
                $settlement = $lockedOrder->settlement()->create($settlementAttributes);
            }

            $workflow->recordSubmission(
                $settlement,
                auth('driver')->user(),
                $resubmitting ? 'resubmitted' : 'submitted',
                $previousStatus,
                [
                    'method' => $settlement->method,
                    'reference' => $settlement->reference,
                    'amount' => $settlement->amount,
                    'recipient' => $settlement->recipient,
                    'recipient_name' => $settlement->recipient_name,
                    'instructions' => $settlement->instructions,
                    'account_data' => $settlement->account_data,
                    'proof_path' => $settlement->proof_path,
                ]
            );

                return $settlement->id;
            });
        } catch (\Throwable $exception) {
            if ($proofPath) {
                Storage::disk('settlement_proofs')->delete($proofPath);
            }
            throw $exception;
        }

        if (!$settlementId) {
            if ($proofPath) {
                Storage::disk('settlement_proofs')->delete($proofPath);
            }
            $this->alert('warning', 'تغيرت حالة الطلب أو تم تسجيل التوريد مسبقًا.');
            return;
        }

        $this->settlementProof = null;
        $this->settlementReference = null;
        $this->order['settlement_status'] = 'pending_review';
        $this->order['settlement_id'] = $settlementId;
        $this->order['settlement_method'] = $this->settlementMethod;
        $this->order['settlement_reference'] = $this->settlementReference;
        $this->order['settlement_amount'] = $amount;
        $this->order['settlement_custody'] = 'لدى السائق';
        $this->order['settlement_history'][] = [
            'action' => $isResubmission ? 'resubmitted' : 'submitted',
            'status_to' => 'pending_review',
            'note' => null,
            'actor' => auth('driver')->user()?->name,
            'created_at' => now()->format('Y-m-d H:i'),
        ];
        $this->order['payment_status'] = 'paid';
        $this->order['payment_label'] = MerchantOrder::resolvePaymentLabel('paid', $this->order['payment_method']);
        $this->order['driver_cod_collection'] = true;
        $this->alert('success', 'تم إرسال التوريد للمراجعة بعد تأكيد التحصيل.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('driver::livewire.home.order.modal.show');
    }
}