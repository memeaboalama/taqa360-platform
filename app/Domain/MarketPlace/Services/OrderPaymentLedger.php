<?php

namespace App\Domain\MarketPlace\Services;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\PaymentCorrectionRequest;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use Carbon\Carbon;
use Domain\AppBase\Models\Payment;

class OrderPaymentLedger
{
    public function forOrder(MerchantOrder $order): array
    {
        $payments = Payment::query()
            ->where('payable_id', $order->id)
            ->whereIn('payable_type', [
                $order->getMorphClass(),
                MerchantOrder::class,
                'App\\Domain\\MarketPlace\\Models\\MerchantOrder',
            ])
            ->orderBy('id')
            ->get();
        $settlement = $order->settlement()->with('driver')->first();
        $refunds = $order->refundpayment()
            ->with(['settlement', 'requestedBy', 'executedBy'])
            ->orderBy('id')
            ->get();
        $executedRefunds = $refunds->filter(
            fn ($refund): bool => $refund->isExecuted()
        );
        $corrections = PaymentCorrectionRequest::query()
            ->with(['approvedBy', 'settlement'])
            ->where('order_id', $order->id)
            ->whereIn('movement_type', ['collection', 'settlement'])
            ->where('status', 'approved')
            ->orderBy('id')
            ->get();
        $confirmedPayments = $payments->filter(
            fn (Payment $record): bool => self::isConfirmedPayment($order, $record)
        )->groupBy('payment_id')
            ->map(fn ($records) => $records->last())
            ->values();
        $payment = $confirmedPayments->last() ?? $payments->last();
        $latestCollection = $confirmedPayments->last();
        $hasConfirmedPayment = $confirmedPayments->isNotEmpty();
        $rawCollected = $confirmedPayments->sum(
            fn (Payment $record): float => $this->halalasToSar($record->captured ?: $record->amount)
        );
        $due = (float) ($order->total_after_discount > 0
            ? $order->total_after_discount
            : $order->total_after_vat);
        $collected = $rawCollected + (float) $corrections
            ->where('movement_type', 'collection')
            ->sum('adjustment_amount');
        $paymentStatus = $hasConfirmedPayment
            ? self::paymentStatusForCollection($due, $collected)
            : MerchantOrder::resolvePaymentStatus($payment, 'unpaid', $order->payment_method);
        $isPaid = $paymentStatus === 'paid';
        $refunded = (float) $executedRefunds->sum('amount');
        $pendingRefundAmount = (float) $refunds
            ->where('status', 'pending')
            ->sum('amount');
        $refundStatus = $pendingRefundAmount > 0
            ? 'pending'
            : ($refunded > 0
                ? ($refunded + 0.009 >= $collected ? 'refunded' : 'partially_refunded')
                : 'none');
        $deliveryStatus = $order->status instanceof MerchantOrderStatuses
            ? $order->status
            : MerchantOrderStatuses::tryFrom((string) $order->status);

        $movements = [];
        foreach ($confirmedPayments as $confirmedPayment) {
            $movementAmount = $this->halalasToSar($confirmedPayment->captured ?: $confirmedPayment->amount);
            if ($movementAmount <= 0) {
                continue;
            }
            $movements[] = [
                'type' => 'collection',
                'record_id' => $confirmedPayment->id,
                'number' => $confirmedPayment->payment_id,
                'amount' => $movementAmount,
                'method' => $this->paymentMethodLabel($order, $confirmedPayment),
                'collector' => $this->collectorName($order, $confirmedPayment, $settlement),
                'occurred_at' => $this->dateLabel($confirmedPayment->capturedAt ?: $confirmedPayment->created_at),
                'settlement_number' => $settlement ? 'STL-' . $settlement->id : null,
                'settlement_status' => $settlement?->status,
                'details' => $confirmedPayment->description ?: $confirmedPayment->source_message,
            ];
        }

        foreach ($refunds as $refund) {
            $refundExecuted = $refund->isExecuted();
            $movements[] = [
                'type' => 'refund',
                'record_id' => $refund->id,
                'number' => $refund->refund_payment_id,
                'amount' => $refundExecuted ? -(float) $refund->amount : 0,
                'requested_amount' => (float) $refund->amount,
                'refund_status' => $refund->status ?: 'executed',
                'method' => $refund->refund_method === 'wallet'
                    ? __('ecommerce::ecommerce.refund_method_wallet')
                    : ($refund->type ?: __('ecommerce::ecommerce.refund')),
                'collector' => $refund->executedBy?->name
                    ?: $refund->requestedBy?->name
                    ?: __('ecommerce::ecommerce.admin_collector'),
                'occurred_at' => $this->dateLabel($refund->executed_at ?: $refund->created_at),
                'settlement_number' => $refund->settlement_id
                    ? 'STL-' . $refund->settlement_id
                    : null,
                'settlement_status' => $refund->settlement?->status,
                'details' => trim(implode(' · ', array_filter([
                    $refund->note,
                    $refund->reference,
                    $refund->funded_by
                        ? __('ecommerce::ecommerce.refund_funded_by_' . $refund->funded_by)
                        : null,
                ]))),
            ];
        }

        foreach ($corrections as $correction) {
            $movements[] = [
                'type' => 'correction',
                'record_id' => $correction->id,
                'number' => 'COR-' . $correction->id,
                'amount' => (float) $correction->adjustment_amount,
                'method' => $this->paymentMethodLabel($order, $payment),
                'collector' => $correction->approvedBy?->name ?: __('ecommerce::ecommerce.admin_collector'),
                'occurred_at' => $this->dateLabel($correction->approved_at),
                'settlement_number' => $correction->settlement_id
                    ? 'STL-' . $correction->settlement_id
                    : null,
                'settlement_status' => $correction->settlement?->status,
                'details' => sprintf(
                    '%s-%s: %s',
                    strtoupper($correction->movement_type),
                    $correction->movement_id,
                    $correction->reason
                ),
            ];
        }

        $custody = '—';
        if ($collected > 0 && MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
            if ($settlement?->status === 'approved') {
                $custody = $settlement->recipient === 'merchant'
                    ? __('ecommerce::ecommerce.merchant')
                    : __('ecommerce::ecommerce.platform');
            } elseif ($settlement) {
                $custody = __('ecommerce::ecommerce.funds_held_by_driver');
            } else {
                $custody = __('ecommerce::ecommerce.funds_held_by_driver');
            }
        } elseif ($collected > 0) {
            $custody = $payment->source_company
                ?: $payment->source_type
                ?: __('ecommerce::ecommerce.payment_provider');
        }

        return [
            'payment' => $payment,
            'settlement' => $settlement,
            'payment_status' => $paymentStatus,
            'payment_label' => MerchantOrder::resolvePaymentLabel($paymentStatus, $order->payment_method),
            'delivery_status' => $deliveryStatus?->value ?? (string) $order->status,
            'delivery_status_label' => $deliveryStatus?->trans() ?? (string) $order->status,
            'settlement_status' => !$settlement || in_array($settlement->status, ['returned', 'rejected'], true)
                ? 'not_submitted'
                : ($settlement->status === 'approved' ? 'approved' : 'pending_review'),
            'settlement_number' => $settlement ? 'STL-' . $settlement->id : null,
            'collection_date' => $latestCollection
                ? $this->dateLabel($latestCollection->capturedAt ?: $latestCollection->created_at)
                : null,
            'refund_status' => $refundStatus,
            'pending_refund_amount' => round($pendingRefundAmount, 2),
            'payment_method' => $this->paymentMethodLabel($order, $payment),
            ...self::summarize($due, $collected, $refunded),
            'custody' => $custody,
            'movements' => $movements,
        ];
    }

    public static function summarize(float $due, float $collected, float $refunded): array
    {
        return [
            'due' => round(max($due, 0), 2),
            'collected' => round(max($collected, 0), 2),
            'remaining' => round(max($due - $collected, 0), 2),
            'refunded' => round(max($refunded, 0), 2),
        ];
    }

    public static function isConfirmedPayment(MerchantOrder $order, Payment $payment): bool
    {
        return MerchantOrder::resolvePaymentStatus($payment, 'unpaid', $order->payment_method) === 'paid';
    }

    public static function paymentStatusForCollection(float $due, float $collected): string
    {
        if ($collected <= 0) {
            return 'unpaid';
        }

        return $collected + 0.009 >= $due ? 'paid' : 'partially_paid';
    }

    private function halalasToSar(mixed $value): float
    {
        return round((float) ($value ?? 0) / 100, 2);
    }

    private function paymentMethodLabel(MerchantOrder $order, ?Payment $payment): string
    {
        if (MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
            return __('ecommerce::ecommerce.cod');
        }

        return $payment?->source_company
            ?: $payment?->source_type
            ?: (string) ($order->payment_method ?: '—');
    }

    private function collectorName(MerchantOrder $order, Payment $payment, ?OrderSettlement $settlement): string
    {
        if ($settlement?->driver?->name) {
            return $settlement->driver->name;
        }

        if (MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
            return $payment->source_name ?: __('ecommerce::ecommerce.admin_collector');
        }

        return $payment->source_name
            ?: $payment->source_company
            ?: __('ecommerce::ecommerce.payment_provider');
    }

    private function dateLabel(mixed $value): string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i') : '—';
    }
}
