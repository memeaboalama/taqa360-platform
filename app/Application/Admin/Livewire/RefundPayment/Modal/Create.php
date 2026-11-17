<?php

namespace Application\Admin\Livewire\RefundPayment\Modal;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Services\RefundExecutionWorkflow;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    protected $listeners = ['refreshOrderData'];

    public $type = 'toWallet',
        $order_id = null,
        $order_code = null,
        $payment_id = null,
        $payment = null,
        $refund_payment_id = null,
        $payment_amount = null,
        $amount = 0,
        $currency = 'SAR',
        $status = '',
        $note = null,
        $fundedBy = 'platform',
        $user_id = null,
        $user_name = null,
        $refund_payment_amount_last = 0,
        $availableRefundAmount = 0,
        $pendingRefundAmount = 0,
        $calculated_on = 'company',
        $merchant_name = '',
        $collectionPaymentRecordId = null,
        $availableCollections = [];
    public  $refundpayment;
    public MerchantOrder  $orderNew;
    public $messageFlag = false;

    public function mount(MerchantOrder $order)
    {
        if ($order)
            $this->refreshOrderData($order);
    }
    protected function rules(): array
    {
        return [
            'type' => 'required|string|in:toWallet',
            'order_id' => 'required|integer',
            'payment_id' => 'required',
            'user_id' => 'required|integer',
            'collectionPaymentRecordId' => 'required|integer|min:1',
            'amount' => 'required|numeric|gt:0',
            'currency' => 'required',
            'note' => 'required|string',
            'user_name' => 'required|string',
            'calculated_on' => 'required|string|in:company,store,delivery',
            'fundedBy' => 'required|string|in:platform,merchant,driver',
        ];
    }

    public function refreshOrderData(MerchantOrder $order)
    {
        $this->orderNew = $order;
        $this->order_id = $order->id;
        $this->order_code = $order->code;
        $this->user_id = $order->user_id;
        $this->user_name = $order->client_name ?: $order->user?->name;
        $this->refundpayment = $order->refundpayment()
            ->with(['collectionPayment', 'settlement', 'requestedBy', 'executedBy'])
            ->latest('id')
            ->get();
        $this->merchant_name = $order?->merchant_name;
        $this->status = $order->status instanceof \BackedEnum
            ? $order->status->value
            : (string) $order->status;
        $this->availableCollections = app(RefundExecutionWorkflow::class)
            ->confirmedCollections($order)
            ->map(fn ($payment) => [
                'id' => $payment->id,
                'payment_id' => $payment->payment_id,
                'amount' => round((float) ($payment->captured ?: $payment->amount) / 100, 2),
            ])
            ->all();
        $latestCollection = collect($this->availableCollections)->last();
        $this->collectionPaymentRecordId = $latestCollection['id'] ?? null;
        $this->fundedBy = 'platform';
        $this->refreshRefundAvailability();
    }

    public function updatedCollectionPaymentRecordId(): void
    {
        $this->refreshRefundAvailability();
    }

    public function store(RefundExecutionWorkflow $workflow): void
    {
        $data = $this->validate();
        $admin = auth('admin')->user();
        abort_unless($admin, 403);

        $refund = $workflow->request(
            (int) $data['order_id'],
            (int) $data['collectionPaymentRecordId'],
            (string) $data['amount'],
            (string) $data['note'],
            (string) $data['fundedBy'],
            (string) $data['calculated_on'],
            $admin
        );

        $this->amount = 0;
        $this->note = null;
        $this->messageFlag = false;
        $this->refreshOrderData(MerchantOrder::query()->findOrFail($refund->order_id));
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createRefundPayment');
        $this->alert('success', __('admin::home.refund_request_created_pending'));
    }

    public function executeRefund(int $refundId, RefundExecutionWorkflow $workflow): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin && $this->order_id, 403);

        $refund = \App\Domain\AppBase\Models\RefundPayment::query()
            ->where('order_id', $this->order_id)
            ->whereKey($refundId)
            ->firstOrFail();
        $workflow->execute($refund->id, $admin);

        $this->refreshOrderData(MerchantOrder::query()->findOrFail($this->order_id));
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.refund_executed_successfully'));
    }

    private function refreshRefundAvailability(): void
    {
        $payment = collect($this->availableCollections)
            ->firstWhere('id', (int) $this->collectionPaymentRecordId);
        if (!$payment || !$this->order_id) {
            $this->payment_id = null;
            $this->payment_amount = 0;
            $this->refund_payment_amount_last = 0;
            $this->availableRefundAmount = 0;
            $this->pendingRefundAmount = 0;
            return;
        }

        $order = MerchantOrder::query()->findOrFail($this->order_id);
        $collection = app(RefundExecutionWorkflow::class)
            ->confirmedCollections($order)
            ->firstWhere('id', (int) $this->collectionPaymentRecordId);
        if (!$collection) {
            $this->payment_id = null;
            $this->payment_amount = 0;
            $this->refund_payment_amount_last = 0;
            $this->availableRefundAmount = 0;
            $this->pendingRefundAmount = 0;
            return;
        }

        $amounts = app(RefundExecutionWorkflow::class)->availableForCollection($order, $collection);
        $this->payment_id = $collection->payment_id;
        $this->payment_amount = $amounts['collection_amount'];
        $this->refund_payment_amount_last = $amounts['refunded_amount'];
        $this->availableRefundAmount = $amounts['available_amount'];
        $this->pendingRefundAmount = $amounts['pending_amount'];
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.refundpayment.modal.create');
    }
}
