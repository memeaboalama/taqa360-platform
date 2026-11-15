<?php

namespace Application\Driver\Livewire\Home\Order\Modal;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Show extends Component
{
    use LivewireAlert, WithFileUploads;

    public $listeners = ['showOrderDetails'];

    public array $order = [];
    public array $items = [];
    public $settlementMethod;
    public $settlementReference;
    public $settlementProof;

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
            'delivery_distance' => $order->delivery_distance,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_label' => $order->payment_label,
            'collection_config' => $order->collection_config ?? [],
            'settlement_status' => $order->settlement?->status,
            'settlement_method' => $order->settlement?->method,
            'note' => $order->note,
            'order_type' => $order->type_service_name(),
            'schedulable' => $order->schedulable,
            'date' => $order->date ? Carbon::parse($order->date)->format('Y-m-d H:i') : null,
            'from' => $order->from ? Carbon::parse($order->from)->format('H:i') : null,
            'to' => $order->to ? Carbon::parse($order->to)->format('H:i') : null,
        ];
        $this->settlementMethod = $this->order['collection_config']['enabled_methods'][0] ?? null;
        $this->settlementReference = null;
        $this->settlementProof = null;

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
        $this->order['payment_status'] = $order->payment_status;
        $this->order['payment_label'] = $order->payment_label;
        $this->order['settlement_status'] = $order->settlement?->status;
        $this->order['settlement_method'] = $order->settlement?->method;
    }

    public function submitSettlement(): void
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
        if ($order->settlement()->exists()) {
            $this->alert('warning', 'تم تسجيل التوريد لهذا الطلب مسبقًا.');
            return;
        }

        $amount = round((float) $order->total_after_vat, 2);
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

        $recorded = DB::transaction(function () use ($order, $driverId, $config, $methodData, $validated, $proofPath): bool {
            $lockedOrder = MerchantOrder::where('driver_id', $driverId)
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ($lockedOrder->settlement()->exists()
                || $lockedOrder->status !== MerchantOrderStatuses::PREVIOUS
                || !MerchantOrder::isCashOnDeliveryMethod($lockedOrder->payment_method)) {
                return false;
            }

            $amount = round((float) $lockedOrder->total_after_vat, 2);
            if ($amount <= 0) {
                return false;
            }

            $lockedOrder->settlement()->create([
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
            ]);

            $timestamp = now()->toIso8601String();
            $amountInHalalas = (int) round($amount * 100);
            $amountFormat = number_format($amount, 2) . ' SAR';
            $lockedOrder->payment()->updateOrCreate(
                ['payment_id' => 'COD-' . ($lockedOrder->code ?: $lockedOrder->id)],
                [
                    'status' => 'paid',
                    'amount' => $amountInHalalas,
                    'currency' => 'SAR',
                    'captured' => $amountInHalalas,
                    'capturedAt' => $timestamp,
                    'description' => 'Cash collection recorded by driver at delivery.',
                    'amountFormat' => $amountFormat,
                    'capturedFormat' => $amountFormat,
                    'source_type' => 'cash',
                    'source_message' => MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE,
                    'user_id' => $lockedOrder->user_id,
                    'channel' => $lockedOrder->channel,
                    'createdAt' => $timestamp,
                    'updatedAt' => $timestamp,
                ]
            );

            return true;
        });

        if (!$recorded) {
            if ($proofPath) {
                Storage::disk('settlement_proofs')->delete($proofPath);
            }
            $this->alert('warning', 'تغيرت حالة الطلب أو تم تسجيل التوريد مسبقًا.');
            return;
        }

        $this->settlementProof = null;
        $this->settlementReference = null;
        $this->order['settlement_status'] = 'pending_review';
        $this->order['settlement_method'] = $this->settlementMethod;
        $this->order['payment_status'] = 'paid';
        $this->order['payment_label'] = MerchantOrder::resolvePaymentLabel('paid', $this->order['payment_method']);
        $this->alert('success', 'تم تسجيل التحصيل وإرسال التوريد للمراجعة.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('driver::livewire.home.order.modal.show');
    }
}