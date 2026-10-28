<?php

namespace Application\Driver\Livewire\Home\Order\Modal;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Carbon\Carbon;
use Livewire\Component;

class Show extends Component
{
    public $listeners = ['showOrderDetails'];

    public array $order = [];
    public array $items = [];

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
            'subtotal' => $order->subtotal,
            'delivery_fee' => $order->delivery_fee,
            'vat' => $order->vat,
            'discount' => $order->discount,
            'total_after_vat' => $order->total_after_vat,
            'delivery_distance' => $order->delivery_distance,
            'payment_method' => $order->payment_method,
            'payment_label' => $order->payment_label,
            'note' => $order->note,
            'order_type' => $order->type_service_name(),
            'schedulable' => $order->schedulable,
            'date' => $order->date ? Carbon::parse($order->date)->format('Y-m-d H:i') : null,
            'from' => $order->from ? Carbon::parse($order->from)->format('H:i') : null,
            'to' => $order->to ? Carbon::parse($order->to)->format('H:i') : null,
        ];

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

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('driver::livewire.home.order.modal.show');
    }
}