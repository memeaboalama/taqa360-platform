<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Domain\AppBase\Models\Payment;
use Tests\TestCase;

class MerchantOrderPaymentStatusTest extends TestCase
{
    public function test_it_requires_a_successful_payment_status_instead_of_inferring_it_from_amount(): void
    {
        $paidPayment = new Payment([
            'status' => 'paid',
            'amount' => 28800,
        ]);

        $unpaidPayment = new Payment([
            'status' => 'unpaid',
            'amount' => 0,
        ]);

        $amountOnlyPayment = new Payment([
            'status' => null,
            'amount' => 28800,
        ]);

        $codCollectedPayment = new Payment([
            'status' => 'paid',
            'amount' => 28800,
            'source_message' => MerchantOrder::COD_COLLECTION_CONFIRMATION_MESSAGE,
        ]);
        $driverCollectedPayment = new Payment([
            'status' => 'paid',
            'amount' => 28800,
            'source_message' => 'Collected by driver at delivery.',
        ]);
        $failedPayment = new Payment(['status' => 'failed']);

        $this->assertSame('paid', MerchantOrder::resolvePaymentStatus($paidPayment));
        $this->assertSame('unpaid', MerchantOrder::resolvePaymentStatus($unpaidPayment));
        $this->assertSame('unpaid', MerchantOrder::resolvePaymentStatus($amountOnlyPayment));
        $this->assertSame('pending', MerchantOrder::resolvePaymentStatus(null, 'unpaid', 'COD'));
        $this->assertSame('pending', MerchantOrder::resolvePaymentStatus(null, 'unpaid', 'cash_on_delivery'));
        $this->assertSame('pending', MerchantOrder::resolvePaymentStatus($paidPayment, 'unpaid', 'COD'));
        $this->assertSame('paid', MerchantOrder::resolvePaymentStatus($codCollectedPayment, 'unpaid', 'COD'));
        $this->assertSame('pending', MerchantOrder::resolvePaymentStatus($driverCollectedPayment, 'unpaid', 'COD'));
        $this->assertSame('failed', MerchantOrder::resolvePaymentStatus($failedPayment, 'unpaid', 'COD'));
        $this->assertSame('pending', MerchantOrder::resolvePaymentStatus($unpaidPayment, 'unpaid', 'COD'));
        $this->assertSame('pending', MerchantOrder::resolvePaymentStatus($amountOnlyPayment, 'unpaid', 'COD'));
    }

    public function test_it_shows_the_paid_label_after_cod_collection(): void
    {
        $this->assertSame(
            __('ecommerce::ecommerce.paid'),
            MerchantOrder::resolvePaymentLabel('paid', 'COD')
        );
        $this->assertSame(
            __('ecommerce::ecommerce.cod_payment_selected'),
            MerchantOrder::resolvePaymentLabel('pending', 'COD')
        );
        $this->assertSame(
            __('ecommerce::ecommerce.payment_pending'),
            MerchantOrder::resolvePaymentLabel('pending', 'card')
        );
    }
}
