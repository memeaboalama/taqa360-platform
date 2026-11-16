<?php

namespace Application\Admin\Livewire\Order\Table;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Domain\MarketPlace\Actions\Order\OrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use App\Domain\MarketPlace\Services\FinancialMovementCorrectionWorkflow;
use App\Domain\MarketPlace\Services\OrderPaymentLedger;
use App\Domain\MarketPlace\Services\SettlementWorkflow;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Application\Marketplace\Enums\MerchantOrderMarks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Domain\AppBase\Models\Payment;

use Domain\Driver\Models\Driver;
use App\Domain\ElmApp\Actions\Order\ManuallyAssignDriverAction;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;

class Read extends Component
{
    use LivewireAlert;

    public $order;
    public $orderItems;
    public $orderUser;
    public $orderServices;
    public $address;
    public $mark = MerchantOrderMarks::WHITE; //'white';
    public $admin_note;
    public $correctionMovementType;
    public $correctionMovementId;
    public $correctionReason;
    public $correctionAmount;

    public $driver;
    public $drivers = [];
    public $selected_driver_id;
    public $payment;
    public Collection $marks;
    protected $listeners = ['refreshDatatable' => '$refresh', 'updateMark', 'updateNote'];
    public function mount($order, $orderItems, $orderUser, $address, $driver, $payment)
    {
        //        dd($order->branch);
        $this->order = $order;
        $this->orderItems = $orderItems;
        $this->orderUser = $orderUser;
        $this->address = $address;
        if (!$this->address && $order->addressInOrder && !is_null($order->addressInOrder)) {
            if (is_string($order->addressInOrder)) {
                $this->order->addressInOrder = json_decode($order->addressInOrder);
                // } else if (is_array($order->addressInOrder) || is_object($order->addressInOrder)) {
            } else if (is_array($order->addressInOrder)) {
                $this->order->addressInOrder = (object)$order->addressInOrder; //= object;
                foreach ($order->addressInOrder as $k => $v) //{
                    // $annotation = $this->doDeserialize($v, $nestedClass, $context);
                    $this->order->addressInOrder->{$k} = $v;
                // $this->order->addressInOrder = (object)$order->addressInOrder;
            } else if (is_object($order->addressInOrder)) {
                $this->order->addressInOrder = (object)$order->addressInOrder;
                // $this->order->addressInOrder = json_decode($order->addressInOrder);
            } else {

                $this->order->addressInOrder = (object)$order->addressInOrder;
            }
        } else {
            $this->order->addressInOrder = null;
        }
        $this->driver = $driver;
        $this->drivers = Driver::all();
        $this->selected_driver_id = $order->driver_id;
        $this->payment = $payment ?? $order->payment()
            ->latest('id')
            ->first()
            ?? Payment::where('payable_id', $order?->id)
                ->where(function ($q) use ($order) {
                    $q->where('payable_type', $order?->getMorphClass())
                      ->orWhere('payable_type', 'App\\Domain\\MarketPlace\\Models\\MerchantOrder');
                })
                ->latest('id')
                ->first()
            ?? $order->payment;
        $this->mark = $order->mark;
        $this->admin_note = $order->admin_note;
        $this->marks = collect(MerchantOrderMarks::cases());
    }

    public function hydrate(): void
    {
        if (is_string($this->mark)) {
            $this->mark = MerchantOrderMarks::tryFrom($this->mark) ?? MerchantOrderMarks::WHITE;
        }
        $this->drivers = Driver::all();
        $this->marks = collect(MerchantOrderMarks::cases());
    }

    public function assignDriver(): void
    {
        if (!$this->selected_driver_id) {
            $this->alert('warning', __('admin::home.Please Select Driver'));
            return;
        }

        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $logHistory = app(LogOrderHistoryAction::class);
        $assignAction = new ManuallyAssignDriverAction($orderRepo, $logHistory);

        $driverModel = Driver::find($this->selected_driver_id);
        if (!$driverModel) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $user = auth()->user() ?? $this->orderUser;
        if (!$user) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $order = DB::transaction(fn () => $assignAction->handle($this->order->id, [
                'driver_id' => $driverModel->id,
                'commission_from_driver' => $driverModel->taken_commission ?? 0,
            ], $user));
        if (!$order) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->order->refresh();
        $this->driver = $driverModel;
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.driver_assigned_successfully'));
    }

    public function confirmCashCollection(): void
    {
        $status = $this->order->status instanceof MerchantOrderStatuses
            ? $this->order->status->value
            : (string) $this->order->status;

        if (!MerchantOrder::isCashOnDeliveryMethod($this->order->payment_method)
            || $status !== MerchantOrderStatuses::PREVIOUS->value) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $amount = (int) round((float) $this->order->total_after_vat * 100);
        if ($amount <= 0) {
            $this->alert('warning', __('admin::home.something_went_wrong'));
            return;
        }

        $timestamp = now()->toIso8601String();
        $amountFormat = number_format($amount / 100, 2) . ' SAR';
        $this->payment = DB::transaction(function () use ($amount, $timestamp, $amountFormat) {
            $lockedOrder = MerchantOrder::whereKey($this->order->id)->lockForUpdate()->firstOrFail();
            $lockedStatus = $lockedOrder->status instanceof MerchantOrderStatuses
                ? $lockedOrder->status->value
                : (string) $lockedOrder->status;
            if ($lockedStatus !== MerchantOrderStatuses::PREVIOUS->value
                || !MerchantOrder::isCashOnDeliveryMethod($lockedOrder->payment_method)) {
                return null;
            }

            $payment = $lockedOrder->payment()->latest('id')->first();
            if (MerchantOrder::resolvePaymentStatus($payment, 'unpaid', $lockedOrder->payment_method) === 'paid') {
                return null;
            }

            return $lockedOrder->payment()->updateOrCreate(
                ['payment_id' => 'COD-' . ($lockedOrder->code ?: $lockedOrder->id)],
                [
                'status' => 'paid',
                'amount' => $amount,
                'currency' => 'SAR',
                'captured' => $amount,
                'capturedAt' => $timestamp,
                'description' => 'Cash on delivery collection confirmed by admin.',
                'amountFormat' => $amountFormat,
                'capturedFormat' => $amountFormat,
                'source_type' => 'cash',
                'source_name' => auth('admin')->user()?->name ?: __('ecommerce::ecommerce.admin_collector'),
                'source_number' => (string) auth('admin')->id(),
                'source_message' => MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE,
                'user_id' => $lockedOrder->user_id,
                'channel' => $lockedOrder->channel,
                'createdAt' => $timestamp,
                'updatedAt' => $timestamp,
                ]
            );
        });

        if (!$this->payment) {
            $this->alert('warning', __('admin::home.Payment Status paid'));
            return;
        }

        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.cash_collection_confirmed'));
    }

    public function reviewSettlement(SettlementWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('review')) {
            abort(403);
        }

        $settlement = $this->order->settlement()->first();
        if (!$settlement) {
            $this->alert('error', 'لا توجد تسوية مرتبطة بهذا الطلب.');
            return;
        }

        try {
            $workflow->review($settlement->id, auth('admin')->user());
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->alert('warning', $exception->errors()['settlement'][0] ?? $exception->getMessage());
            return;
        }
        $this->order->load('settlement');
        $this->alert('success', 'تمت مراجعة التوريد.');
    }

    public function approveSettlement(SettlementWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('approve')) {
            abort(403);
        }

        $settlement = $this->order->settlement()->first();
        if (!$settlement) {
            $this->alert('error', 'لا توجد تسوية مرتبطة بهذا الطلب.');
            return;
        }

        try {
            $workflow->approve($settlement->id, auth('admin')->user());
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->alert('warning', $exception->errors()['settlement'][0] ?? $exception->getMessage());
            return;
        }
        $this->order->load('settlement');
        $this->alert('success', 'تم اعتماد التوريد.');
    }

    public function beginCorrectionRequest(
        FinancialMovementCorrectionWorkflow $workflow,
        string $movementType,
        int $movementId
    ): void
    {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        $this->assertCorrectionMovement($movementType, $movementId);
        $preview = $workflow->preview($this->order->id, $movementType, $movementId);
        $this->correctionMovementType = $movementType;
        $this->correctionMovementId = $movementId;
        $this->correctionReason = null;
        $this->correctionAmount = number_format($preview['original_amount'], 2, '.', '');
        $this->resetErrorBag('correctionReason');
    }

    public function submitCorrectionRequest(FinancialMovementCorrectionWorkflow $workflow): void
    {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        $validated = $this->validate([
            'correctionMovementType' => ['required', 'in:collection'],
            'correctionMovementId' => ['required', 'integer', 'min:1'],
            'correctionAmount' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'correctionReason' => ['required', 'string', 'max:1000'],
        ]);
        $this->assertCorrectionMovement($validated['correctionMovementType'], (int) $validated['correctionMovementId']);

        $workflow->request(
            $this->order->id,
            $validated['correctionMovementType'],
            (int) $validated['correctionMovementId'],
            (string) $validated['correctionAmount'],
            $validated['correctionReason'],
            auth('admin')->user()
        );

        $this->correctionMovementType = null;
        $this->correctionMovementId = null;
        $this->correctionReason = null;
        $this->correctionAmount = null;
        $this->alert('success', 'تم إرسال طلب التصحيح للمراجعة.');
    }

    public function approveCorrectionRequest(
        FinancialMovementCorrectionWorkflow $workflow,
        int $requestId
    ): void {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        PaymentCorrectionRequest::query()
            ->where('order_id', $this->order->id)
            ->whereKey($requestId)
            ->firstOrFail();
        $workflow->approve($requestId, auth('admin')->user());
        $this->alert('success', 'تم اعتماد التصحيح وتحديث الأرصدة.');
    }

    public function cancelCorrectionRequest(
        FinancialMovementCorrectionWorkflow $workflow,
        int $requestId
    ): void {
        if (!CollectionSetting::adminCan('correct')) {
            abort(403);
        }

        PaymentCorrectionRequest::query()
            ->where('order_id', $this->order->id)
            ->whereKey($requestId)
            ->firstOrFail();
        $workflow->cancel($requestId, auth('admin')->user());
        $this->alert('success', 'تم إلغاء طلب التصحيح دون تعديل الحركة.');
    }

    private function assertCorrectionMovement(string $movementType, int $movementId): void
    {
        if ($movementType === 'collection') {
            $exists = Payment::query()
                ->where('payable_id', $this->order->id)
                ->whereIn('payable_type', [
                    $this->order->getMorphClass(),
                    MerchantOrder::class,
                    'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
                ])
                ->whereKey($movementId)
                ->whereIn('status', ['paid', 'success'])
                ->exists();
        } else {
            abort(404);
        }

        abort_unless($exists, 404);
    }

    public function render()
    {
        if (is_string($this->mark)) {
            $this->mark = MerchantOrderMarks::tryFrom($this->mark) ?? MerchantOrderMarks::WHITE;
        }
        $this->marks = collect(MerchantOrderMarks::cases());

        $order = MerchantOrder::with([
            'settlement.driver',
            'settlement.corrections.admin',
            'refundpayment',
        ])->findOrFail($this->order->id);
        $paymentLedger = app(OrderPaymentLedger::class)->forOrder($order);
        $correctionRequests = PaymentCorrectionRequest::with(['requestedBy', 'approvedBy', 'cancelledBy', 'settlement'])
            ->where('order_id', $order->id)
            ->where('movement_type', 'collection')
            ->latest('id')
            ->get();
        $correctionPreview = $this->correctionMovementType && $this->correctionMovementId
            ? app(FinancialMovementCorrectionWorkflow::class)->preview(
                $order->id,
                $this->correctionMovementType,
                (int) $this->correctionMovementId,
                $this->correctionAmount
            )
            : null;

        return view('admin::livewire.order.table.read', [
            'order' => $order,
            'orderItems' => $this->orderItems,
            'orderUser' => $this->orderUser,
            'address' => $this->address,
            'driver' => $this->driver,
            'payment' => $paymentLedger['payment'],
            'paymentLedger' => $paymentLedger,
            'correctionRequests' => $correctionRequests,
            'correctionPreview' => $correctionPreview,
        ]);
    }
    public function updateNote(): void
    {
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $orderAction = new OrderAction($orderRepo);
        $order = DB::transaction(
            fn () => $orderAction->updateAdminNote($this->order->id, $this->admin_note)
        );
        if (!$order) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    public function updateMark(): void
    {
        $markValue = $this->mark instanceof MerchantOrderMarks ? $this->mark->value : $this->mark;
        $mark = is_string($markValue) ? MerchantOrderMarks::tryFrom($markValue) : null;
        if (!$mark) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $orderAction = new OrderAction($orderRepo);
        $order = DB::transaction(
            fn () => $orderAction->updateMark($this->order->id, $mark->value)
        );
        if (!$order) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->mark = $mark;
        $this->marks = collect(MerchantOrderMarks::cases());
        $this->emit('refresh');
    }
}
