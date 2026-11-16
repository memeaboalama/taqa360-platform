<?php

namespace Application\Admin\Livewire\SettlementReview;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
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

    public function mount(OrderSettlement $settlement): void
    {
        $this->authorizeAccess();
        $this->settlementId = $settlement->id;
        $this->receivedAt = now()->format('Y-m-d\TH:i');
        $this->receivedBy = auth('admin')->user()->name;
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
        $collections = $settlement->order
            ? Payment::query()
                ->where('payable_id', $settlement->order_id)
                ->whereIn('payable_type', [
                    $settlement->order->getMorphClass(),
                    MerchantOrder::class,
                    'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
                ])
                ->where('source_message', MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE)
                ->whereIn('status', ['paid', 'success'])
                ->orderBy('id')
                ->get()
                ->groupBy('payment_id')
                ->map(fn ($records) => $records->last())
                ->values()
            : collect();

        return view('admin::livewire.settlement-review.show', [
            'settlement' => $settlement,
            'payment' => $collections->last(),
            'collections' => $collections,
            'collectedAmount' => round($collections->sum(
                fn (Payment $record): float => (float) ($record->captured ?: $record->amount) / 100
            ), 2),
            'canReview' => CollectionSetting::adminCan('review'),
            'canApprove' => CollectionSetting::adminCan('approve'),
        ]);
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth('admin')->check()
                && (CollectionSetting::adminCan('review') || CollectionSetting::adminCan('approve')),
            403
        );
    }
}
