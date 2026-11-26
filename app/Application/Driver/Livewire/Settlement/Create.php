<?php

namespace Application\Driver\Livewire\Settlement;

use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Services\DriverSettlementSubmissionWorkflow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $listeners = [
        'openDriverSettlementForm',
        'resubmitDriverSettlementBatch',
        'viewDriverSettlementBatch',
    ];

    public array $selectedCollectionIds = [];
    public string $settlementMethod = '';
    public string $settlementReference = '';
    public string $supplyDate = '';
    public $settlementProof;
    public ?int $returnedBatchId = null;
    public bool $showBatchDetails = false;
    public array $batchDetails = [];

    public function mount(): void
    {
        abort_unless(auth('driver')->id(), 403);
        $this->supplyDate = now()->toDateString();
    }

    public function openDriverSettlementForm($paymentId = null): void
    {
        abort_unless(auth('driver')->id(), 403);
        $this->resetForm();
        if ($paymentId !== null) {
            $candidates = $this->collections();
            $collection = $candidates->firstWhere('payment_id', (int) $paymentId);
            if ($collection) {
                $this->selectedCollectionIds = $candidates
                    ->where('order_id', $collection['order_id'])
                    ->pluck('payment_id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->all();
            }
        }
        $this->chooseDefaultMethod();
        $this->emit('openModal', '#driverSettlementSubmissionModal');
    }

    public function resubmitDriverSettlementBatch(int $batchId): void
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);

        $settlements = OrderSettlement::batchQuery($batchId)
            ->where('driver_id', $driverId)
            ->with('order')
            ->get();
        abort_unless($settlements->isNotEmpty()
            && $settlements->every(fn (OrderSettlement $settlement) => $settlement->status === 'returned'), 404);

        $candidateIds = $this->collections($batchId)
            ->whereIn('order_id', $settlements->pluck('order_id'))
            ->pluck('payment_id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
        abort_unless($candidateIds !== [], 404);

        $root = $settlements->firstWhere('id', $batchId) ?? $settlements->first();
        $this->resetForm();
        $this->returnedBatchId = $batchId;
        $this->selectedCollectionIds = $candidateIds;
        $this->settlementMethod = $root->method;
        $this->settlementReference = (string) ($root->reference ?? '');
        $this->supplyDate = $root->supply_date?->toDateString() ?? now()->toDateString();
        $this->showBatchDetails = false;
        $this->emit('openModal', '#driverSettlementSubmissionModal');
    }

    public function viewDriverSettlementBatch(int $batchId): void
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);
        $settlements = OrderSettlement::batchQuery($batchId)
            ->where('driver_id', $driverId)
            ->with('order')
            ->get();
        abort_unless($settlements->isNotEmpty(), 404);

        $this->batchDetails = $this->formatBatchDetails($batchId, $settlements);
        $this->showBatchDetails = true;
        $this->emit('openModal', '#driverSettlementSubmissionModal');
    }

    public function updatedSelectedCollectionIds(): void
    {
        $this->chooseDefaultMethod();
    }

    public function updatedSettlementMethod(): void
    {
        if (!in_array($this->settlementMethod, ['transfer', 'deposit'], true)) {
            $this->settlementReference = '';
            $this->settlementProof = null;
        }
    }

    public function submit(DriverSettlementSubmissionWorkflow $workflow): void
    {
        $driver = auth('driver')->user();
        abort_unless($driver, 403);

        $selected = $this->collections($this->returnedBatchId)
            ->whereIn('payment_id', array_map('intval', $this->selectedCollectionIds));
        if ($selected->isEmpty() || $selected->count() !== count(array_unique($this->selectedCollectionIds))) {
            $this->addError('selectedCollectionIds', 'لا توجد تحصيلات مؤهلة محددة. حدّث الصفحة ثم حاول مجددًا.');
            return;
        }

        if ($selected->contains(fn (array $collection) => !$collection['recipient']
            || !$collection['enabled_methods'])) {
            $this->addError('settings', 'إعدادات جهة الاستلام ووسائل التوريد غير مضبوطة لبعض التحصيلات؛ أوقف الإرسال وتواصل مع الإدارة.');
            return;
        }

        $destinations = $selected->pluck('recipient')->unique();
        if ($destinations->count() !== 1) {
            $this->addError('selectedCollectionIds', 'لا يمكن جمع تحصيلات لجهات استلام مختلفة.');
            return;
        }

        $availableMethods = $this->availableMethods($selected);
        $methodData = $selected->first()['methods'][$this->settlementMethod] ?? [];
        $needsProof = OrderSettlement::requiresProof($this->settlementMethod);
        $validated = $this->validate([
            'selectedCollectionIds' => ['required', 'array', 'min:1'],
            'selectedCollectionIds.*' => ['required', 'integer'],
            'settlementMethod' => ['required', Rule::in($availableMethods)],
            'settlementReference' => [$needsProof ? 'required' : 'nullable', 'string', 'max:255'],
            'settlementProof' => [$needsProof ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'supplyDate' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ]);
        if (empty($methodData['recipient_name']) || empty($methodData['instructions'])
            || ($needsProof && empty($methodData['account_data']))) {
            $this->addError('settings', 'إعدادات جهة الاستلام لهذه الوسيلة غير مكتملة؛ تواصل مع الإدارة.');
            return;
        }

        $proofPath = null;
        if ($needsProof) {
            $proofPath = $this->settlementProof->store('settlements', 'settlement_proofs');
            if (!$proofPath) {
                $this->addError('settlementProof', 'تعذر حفظ إثبات التوريد. حاول مرة أخرى.');
                return;
            }
        }

        try {
            $result = $workflow->submit(
                (int) $driver->id,
                $validated['selectedCollectionIds'],
                $validated['settlementMethod'],
                $validated['settlementReference'] ?: null,
                $proofPath,
                $validated['supplyDate'],
                $driver,
                $this->returnedBatchId
            );
        } catch (\Illuminate\Validation\ValidationException $exception) {
            if ($proofPath) {
                Storage::disk('settlement_proofs')->delete($proofPath);
            }
            throw $exception;
        } catch (\Throwable $exception) {
            if ($proofPath) {
                Storage::disk('settlement_proofs')->delete($proofPath);
            }
            report($exception);
            $this->addError('settlement', 'تعذر إرسال طلب التسوية: ' . $exception->getMessage());
            return;
        }

        $batchId = $result['batch_id'];
        $settlements = OrderSettlement::batchQuery($batchId)->with('order')->get();
        $this->batchDetails = $this->formatBatchDetails($batchId, $settlements);
        $this->showBatchDetails = true;
        $this->returnedBatchId = $batchId;
        $this->emit('refreshMySettlements');
        $this->emit('refreshDriverCustodyStatement');
        $this->emit('openModal', '#driverSettlementSubmissionModal');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);
        $collections = $this->collections($this->returnedBatchId);
        $selected = $collections->whereIn('payment_id', array_map('intval', $this->selectedCollectionIds));
        $availableMethods = $this->availableMethods($selected);

        return view('driver::livewire.settlement.create', [
            'collections' => $collections,
            'selected' => $selected,
            'selectedTotal' => round((float) $selected->sum('amount'), 2),
            'availableMethods' => $availableMethods,
            'mixedRecipients' => $selected->pluck('recipient')->filter()->unique()->count() > 1,
            'settingsMissing' => $collections->contains(
                fn (array $collection) => !$collection['recipient'] || !$collection['enabled_methods']
            ),
        ]);
    }

    private function collections(?int $batchId = null): Collection
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);

        return app(DriverSettlementSubmissionWorkflow::class)
            ->eligibleCollections((int) $driverId, $batchId);
    }

    private function availableMethods(Collection $selected): array
    {
        if ($selected->isEmpty() || $selected->pluck('recipient')->filter()->unique()->count() !== 1) {
            return [];
        }

        $methods = collect($selected->first()['enabled_methods'] ?? [])
            ->filter(fn ($method) => in_array($method, ['cash', 'transfer', 'deposit'], true));
        foreach ($selected->skip(1) as $collection) {
            $methods = $methods->intersect($collection['enabled_methods'] ?? []);
        }

        return $methods->filter(function ($method) use ($selected) {
            $destinations = $selected->map(fn (array $collection) => json_encode([
                'recipient' => $collection['recipient'],
                'method' => $method,
                'method_data' => $collection['methods'][$method] ?? [],
            ]))->unique();

            return $destinations->count() === 1;
        })->values()->all();
    }

    private function chooseDefaultMethod(): void
    {
        $selected = $this->collections($this->returnedBatchId)
            ->whereIn('payment_id', array_map('intval', $this->selectedCollectionIds));
        $availableMethods = $this->availableMethods($selected);
        if (!in_array($this->settlementMethod, $availableMethods, true)) {
            $this->settlementMethod = $availableMethods[0] ?? '';
            if (!in_array($this->settlementMethod, ['transfer', 'deposit'], true)) {
                $this->settlementReference = '';
                $this->settlementProof = null;
            }
        }
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->selectedCollectionIds = [];
        $this->settlementMethod = '';
        $this->settlementReference = '';
        $this->settlementProof = null;
        $this->supplyDate = now()->toDateString();
        $this->returnedBatchId = null;
        $this->showBatchDetails = false;
        $this->batchDetails = [];
    }

    private function formatBatchDetails(int $batchId, Collection $settlements): array
    {
        $settlements->loadMissing(['order', 'payments', 'histories.actor']);
        $primarySettlement = $settlements->firstWhere('id', $batchId) ?? $settlements->first();
        $history = $primarySettlement->histories;
        $historicalPaymentIds = $settlements->flatMap(fn (OrderSettlement $settlement) =>
            $settlement->histories->flatMap(fn ($item) => $item->snapshot['payment_record_ids'] ?? [])
        )->unique()->values();
        $payments = $settlements->flatMap(fn (OrderSettlement $settlement) => $settlement->payments)
            ->keyBy('id');
        if ($historicalPaymentIds->isNotEmpty()) {
            $payments = $payments->merge(
                \Domain\AppBase\Models\Payment::query()->whereKey($historicalPaymentIds)->get()->keyBy('id')
            );
        }
        $ordersById = $settlements->keyBy('order_id');
        $approvedHistory = $history->firstWhere('action', 'approved');

        return [
            'number' => 'STL-' . $batchId,
            'status' => $primarySettlement->status,
            'total' => round((float) $settlements->sum('amount'), 2),
            'received_amount' => round((float) $settlements->sum('received_amount'), 2),
            'approved_at' => $primarySettlement->approved_at?->format('Y-m-d H:i'),
            'approved_by' => $approvedHistory?->actor?->name,
            'recipient' => $primarySettlement->recipient,
            'recipient_name' => $primarySettlement->recipient_name,
            'instructions' => $primarySettlement->instructions,
            'account_data' => $primarySettlement->account_data,
            'reference' => $primarySettlement->reference,
            'proof_url' => $primarySettlement->proof_path
                ? route('driver:settlements.proof', $primarySettlement)
                : null,
            'supply_date' => $primarySettlement->supply_date?->toDateString(),
            'method' => $primarySettlement->method,
            'latest_note' => $history->last()?->note,
            'orders' => $settlements->map(fn (OrderSettlement $settlement) => [
                'code' => $settlement->order?->code,
                'amount' => (float) $settlement->amount,
            ])->values()->all(),
            'collections' => $payments->map(function ($payment) use ($ordersById) {
                $occurredAt = $payment->capturedAt ?: $payment->created_at;

                return [
                    'order_code' => $ordersById->get($payment->payable_id)?->order?->code,
                    'payment_number' => $payment->payment_id ?: $payment->id,
                    'amount' => (float) ($payment->captured ?: $payment->amount) / 100,
                    'occurred_at' => $occurredAt instanceof \DateTimeInterface
                        ? $occurredAt->format('Y-m-d H:i')
                        : (string) $occurredAt,
                ];
            })->values()->all(),
            'history' => $history->map(fn ($item) => [
                'action' => $item->action,
                'status' => $item->status_to,
                'actor' => $item->actor?->name,
                'note' => $item->note,
                'created_at' => $item->created_at?->format('Y-m-d H:i'),
                'reference' => $item->snapshot['reference'] ?? null,
                'proof_url' => !empty($item->snapshot['proof_path'])
                    ? route('driver:settlements.history.proof', [$primarySettlement, $item])
                    : null,
            ])->values()->all(),
        ];
    }
}
