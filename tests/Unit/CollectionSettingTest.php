<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Services\OrderPaymentLedger;
use App\Domain\MarketPlace\Services\DriverCustodyLedger;
use App\Domain\MarketPlace\Services\SettlementWorkflow;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Domain\AppBase\Models\Payment;
use Tests\TestCase;

class CollectionSettingTest extends TestCase
{
    public function test_order_snapshot_contains_only_enabled_methods_and_their_details(): void
    {
        $setting = new CollectionSetting([
            'recipient' => 'platform',
            'enabled_methods' => ['cash', 'transfer'],
            'methods' => [
                'cash' => [
                    'recipient_name' => 'صندوق المنصة',
                    'instructions' => 'تسليم نقدي',
                ],
                'transfer' => [
                    'recipient_name' => 'حساب المنصة',
                    'instructions' => 'أدخل رقم المرجع',
                    'account_data' => 'SA0000000000000000',
                ],
                'deposit' => [
                    'recipient_name' => 'غير مفعّل',
                    'instructions' => 'غير مفعّل',
                ],
            ],
        ]);

        $snapshot = $setting->snapshot();

        $this->assertSame(['cash', 'transfer'], $snapshot['enabled_methods']);
        $this->assertArrayNotHasKey('deposit', $snapshot['methods']);
        $this->assertSame('SA0000000000000000', $snapshot['methods']['transfer']['account_data']);
    }

    public function test_bank_remittance_requires_a_reference_and_proof(): void
    {
        $this->assertFalse(OrderSettlement::requiresProof('cash'));
        $this->assertTrue(OrderSettlement::requiresProof('transfer'));
        $this->assertTrue(OrderSettlement::requiresProof('deposit'));
    }

    public function test_payment_ledger_keeps_due_outstanding_when_no_collection_exists(): void
    {
        $summary = OrderPaymentLedger::summarize(125.50, 0, 0);

        $this->assertSame(125.50, $summary['due']);
        $this->assertSame(0.0, $summary['collected']);
        $this->assertSame(125.50, $summary['remaining']);
        $this->assertSame(0.0, $summary['refunded']);
    }

    public function test_payment_ledger_preserves_collection_and_refund_as_separate_totals(): void
    {
        $summary = OrderPaymentLedger::summarize(100, 100, 25);

        $this->assertSame(100.0, $summary['collected']);
        $this->assertSame(0.0, $summary['remaining']);
        $this->assertSame(25.0, $summary['refunded']);
    }

    public function test_cod_payment_requires_driver_collection_confirmation(): void
    {
        $order = new MerchantOrder(['payment_method' => 'cod']);
        $payment = new Payment(['status' => 'paid']);

        $this->assertFalse(OrderPaymentLedger::isConfirmedPayment($order, $payment));

        $payment->source_message = MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE;
        $this->assertTrue(OrderPaymentLedger::isConfirmedPayment($order, $payment));
    }

    public function test_pending_driver_settlement_does_not_reduce_custody(): void
    {
        $balance = DriverCustodyLedger::calculateBalance(136.80, 0, 0, 0);

        $this->assertSame(136.80, $balance);
    }

    public function test_only_approved_settlement_and_driver_funded_refund_reduce_custody(): void
    {
        $balance = DriverCustodyLedger::calculateBalance(136.80, 50.00, -5.00, 10.00);

        $this->assertSame(71.80, $balance);
    }

    public function test_settlement_resubmission_reuses_only_returned_workflow_state(): void
    {
        $this->assertTrue(SettlementWorkflow::canTransition(null, 'pending_review'));
        $this->assertTrue(SettlementWorkflow::canTransition('returned', 'pending_review'));
        $this->assertFalse(SettlementWorkflow::canTransition('rejected', 'pending_review'));
        $this->assertFalse(SettlementWorkflow::canTransition('approved', 'pending_review'));
        $this->assertTrue(SettlementWorkflow::canTransition('pending_review', 'approved'));
        $this->assertTrue(SettlementWorkflow::canTransition('reviewed', 'approved'));
    }

    public function test_settlement_approval_requires_all_amounts_to_match_in_minor_units(): void
    {
        $this->assertTrue(SettlementWorkflow::amountsMatch(13680, 13680, 13680, 13680));
        $this->assertFalse(SettlementWorkflow::amountsMatch(13680, 13680, 13679, 13680));
        $this->assertFalse(SettlementWorkflow::amountsMatch(13680, 13680, 13680, 5000));
        $this->assertFalse(SettlementWorkflow::amountsMatch(0, 0, 0, 0));
    }
}
