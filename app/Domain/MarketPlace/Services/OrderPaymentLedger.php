<?php

namespace App\Domain\MarketPlace\Services;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\OrderSettlement;
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
        $refunds = $order->refundpayment()->orderBy('id')->get();
        $confirmedPayments = $payments->filter(
            fn (Payment $record): bool => self::isConfirmedPayment($order, $record)
        );
        $payment = $confirmedPayments->last() ?? $payments->last();
        $isPaid = $confirmedPayments->isNotEmpty();
        $paymentStatus = $isPaid
            ? 'paid'
            : MerchantOrder::resolvePaymentStatus($payment, 'unpaid', $order->payment_method);
        $collected = $confirmedPayments->sum(
            fn (Payment $record): float => $this->halalasToSar($record->captured ?: $record->amount)
        );
        $refunded = (float) $refunds->sum('amount');
        $due = (float) ($order->total_after_discount > 0
            ? $order->total_after_discount
            : $order->total_after_vat);

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
            $movements[] = [
                'type' => 'refund',
                'record_id' => $refund->id,
                'number' => $refund->refund_payment_id,
                'amount' => -(float) $refund->amount,
                'method' => $refund->type ?: __('ecommerce::ecommerce.refund'),
                'collector' => __('ecommerce::ecommerce.admin_collector'),
                'occurred_at' => $this->dateLabel($refund->created_at),
                'settlement_number' => $settlement ? 'STL-' . $settlement->id : null,
                'settlement_status' => $settlement?->status,
                'details' => $refund->note,
            ];
        }

        $custody = '—';
        if ($isPaid && MerchantOrder::isCashOnDeliveryMethod($order->payment_method)) {
            if ($settlement?->status === 'approved') {
                $custody = $settlement->recipient === 'merchant'
                    ? __('ecommerce::ecommerce.merchant')
                    : __('ecommerce::ecommerce.platform');
            } elseif ($settlement) {
                $custody = $settlement->driver?->name
                    ?: __('ecommerce::ecommerce.driver');
            } else {
                $custody = $payment->source_name
                    ?: __('ecommerce::ecommerce.admin_collector');
            }
        } elseif ($isPaid) {
            $custody = $payment->source_company
                ?: $payment->source_type
                ?: __('ecommerce::ecommerce.payment_provider');
        }

        return [
            'payment' => $payment,
            'settlement' => $settlement,
            'payment_status' => $paymentStatus,
            'payment_label' => MerchantOrder::resolvePaymentLabel($paymentStatus, $order->payment_method),
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
