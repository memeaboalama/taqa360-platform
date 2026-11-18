<?php

namespace Application\Admin\Livewire\SettlementReview;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use App\Domain\MarketPlace\Services\FinancialMovementCorrectionWorkflow;
use App\Domain\MarketPlace\Services\SettlementWorkflow;
use Domain\AppBase\Models\Payment;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Show extends Component
{
    use LivewireAlert;

    public int $settlementId;
    public string $reviewNote = '';
    public string $receivedAmount = '';
    public string $receivedAt = '';
    public string $receivedBy = '';
    public bool $receiptConfirmed = false;
    public bool $showCorrectionForm = false;
    public string $correctionAmount = '';
    public string $correctionReason = '';

    public function mount(OrderSettlement $settlement): void
    {
        $this->authorizeAccess();
        $this->settlementId = $settlement->id;
        $this->receivedAt = now()->format('Y-m-d\TH:i');
        $this->receivedBy = auth('admin')->user()->name;
        $this->receivedAmount = number_format(
            (float) OrderSettlement::batchQuery($settlement->batch_number)->sum('amount'),
            2,
            '.',
            ''
        );
    }

    public function markReviewed(SettlementWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('review')) {
            abort(403);
        }

        $workflow->review($this->settlementId, auth('admin')->user());
        $this->alert('success', 'تمت مطابقة بيانات التسوية وتأكيد المراجعة.');
    }

    public function returnForCompletion(SettlementWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('review')) {
            abort(403);
        }

        $validated = $this->validate([
            'reviewNote' => ['required', 'string', 'max:1000'],
        ]);
        $workflow->returnForCompletion($this->settlementId, auth('admin')->user(), $validated['reviewNote']);
        $this->reviewNote = '';
        $this->alert('success', 'أعيدت التسوية للسائق لاستكمال البيانات.');
    }

    public function reject(SettlementWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('review')) {
            abort(403);
        }

        $validated = $this->validate([
            'reviewNote' => ['required', 'string', 'max:1000'],
        ]);
        $workflow->reject($this->settlementId, auth('admin')->user(), $validated['reviewNote']);
        $this->reviewNote = '';
        $this->alert('success', 'تم رفض التسوية وتسجيل السبب.');
    }

    public function approve(SettlementWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('approve')) {
            abort(403);
        }

        $data = $this->validate([
            'receivedAmount' => ['required', 'numeric', 'min:0.01', 'max:99999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'receivedAt' => ['required', 'date_format:Y-m-d\TH:i', 'before_or_equal:now'],
            'receivedBy' => ['required', 'string', 'max:255'],
            'receiptConfirmed' => ['accepted'],
        ]);
        $workflow->approve($this->settlementId, auth('admin')->user(), [
            'received_amount' => $data['receivedAmount'],
            'received_at' => \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $data['receivedAt']),
            'received_by' => $data['receivedBy'],
            'receipt_confirmed' => $data['receiptConfirmed'],
        ]);
        $this->alert('success', 'تم اعتماد التوريد.');
    }

    public function beginCorrectionRequest(FinancialMovementCorrectionWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        $preview = $workflow->preview($this->settlementOrderId(), 'settlement', $this->settlementId);
        $this->correctionAmount = number_format($preview['original_amount'], 2, '.', '');
        $this->correctionReason = '';
        $this->showCorrectionForm = true;
        $this->resetErrorBag();
    }

    public function submitCorrectionRequest(FinancialMovementCorrectionWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        $data = $this->validate([
            'correctionAmount' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'correctionReason' => ['required', 'string', 'max:1000'],
        ]);
        $workflow->request(
            $this->settlementOrderId(),
            'settlement',
            $this->settlementId,
            $data['correctionAmount'],
            $data['correctionReason'],
            auth('admin')->user()
        );
        $this->showCorrectionForm = false;
        $this->correctionAmount = '';
        $this->correctionReason = '';
        $this->alert('success', 'تم إرسال طلب تصحيح التسوية للمراجعة.');
    }

    public function approveCorrectionRequest(
        FinancialMovementCorrectionWorkflow $workflow,
        int $requestId
    ): void {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        $this->correctionRequestQuery()->whereKey($requestId)->firstOrFail();
        $workflow->approve($requestId, auth('admin')->user());
        $this->alert('success', 'تم اعتماد التصحيح وتحديث عهدة السائق.');
    }

    public function cancelCorrectionRequest(
        FinancialMovementCorrectionWorkflow $workflow,
        int $requestId
    ): void {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        $this->correctionRequestQuery()->whereKey($requestId)->firstOrFail();
        $workflow->cancel($requestId, auth('admin')->user());
        $this->alert('success', 'تم إلغاء طلب التصحيح دون تعديل التسوية.');
    }

    public function getListeners(): array
    {
        return ['refreshSettlement' => '$refresh'];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $this->authorizeAccess();
        $settlement = OrderSettlement::query()
            ->with(['driver', 'order', 'histories.actor'])
            ->findOrFail($this->settlementId);
        $settlements = OrderSettlement::batchQuery($settlement->batch_number)
            ->with('order')
            ->orderBy('id')
            ->get();
        $orderIds = $settlements->pluck('order_id');
        $orders = $settlements->pluck('order')->filter()->keyBy('id');
        $collections = $orderIds->isNotEmpty()
            ? Payment::query()
                ->whereIn('payable_id', $orderIds)
                ->whereIn('payable_type', [
                    (new MerchantOrder())->getMorphClass(),
                    MerchantOrder::class,
                    'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
                ])
                ->where('source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE)
                ->whereIn('status', ['paid', 'success'])
                ->orderBy('id')
                ->get()
                ->groupBy(fn (Payment $payment) => $payment->payable_id . ':' . $payment->payment_id)
                ->map(fn ($records) => $records->last())
                ->each(function (Payment $payment) use ($orders) {
                    $payment->setAttribute(
                        'order_code',
                        $orders->get($payment->payable_id)?->code
                    );
                })
                ->values()
            : collect();
        $correctionRequests = $this->correctionRequestQuery()
            ->with(['requestedBy', 'approvedBy', 'cancelledBy'])
            ->latest('id')
            ->get();
        $correctionPreview = $this->showCorrectionForm
            && preg_match('/^\d+(\.\d{1,2})?$/', $this->correctionAmount) === 1
            && (float) $this->correctionAmount <= 99999999.99
            ? app(FinancialMovementCorrectionWorkflow::class)->preview(
                $settlement->order_id,
                'settlement',
                $settlement->id,
                $this->correctionAmount
            )
            : null;

        return view('admin::livewire.settlement-review.show', [
            'settlement' => $settlement,
            'settlements' => $settlements,
            'orders' => $orders,
            'payment' => $collections->last(),
            'collections' => $collections,
            'collectedAmount' => round($collections->sum(
                fn (Payment $record): float => (float) ($record->captured ?: $record->amount) / 100
            ), 2),
            'batchAmount' => round((float) $settlements->sum('amount'), 2),
            'batchReceivedAmount' => round((float) $settlements->sum('received_amount'), 2),
            'canReview' => CollectionSetting::adminCan('review'),
            'canApprove' => CollectionSetting::adminCan('approve'),
            'canCorrect' => CollectionSetting::adminCan('correct'),
            'correctionRequests' => $correctionRequests,
            'correctionPreview' => $correctionPreview,
        ]);
    }

    private function correctionRequestQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return PaymentCorrectionRequest::query()
            ->where('order_id', $this->settlementOrderId())
            ->where('movement_type', 'settlement')
            ->where('movement_id', $this->settlementId);
    }

    private function settlementOrderId(): int
    {
        return (int) OrderSettlement::query()->whereKey($this->settlementId)->value('order_id');
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth('admin')->check()
                && (CollectionSetting::adminCan('review')
                    || CollectionSetting::adminCan('approve')
                    || CollectionSetting::adminCan('correct')),
            403
        );
    }
}
