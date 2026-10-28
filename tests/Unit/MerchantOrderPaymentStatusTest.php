<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Domain\AppBase\Models\Payment;
use Tests\TestCase;

class MerchantOrderPaymentStatusTest extends TestCase
{
    public function test_it_prefers_real_payment_status_and_uses_amount_as_fallback(): void
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

        $this->assertSame('paid', MerchantOrder::resolvePaymentStatus($paidPayment));
        $this->assertSame('unpaid', MerchantOrder::resolvePaymentStatus($unpaidPayment));
        $this->assertSame('paid', MerchantOrder::resolvePaymentStatus($amountOnlyPayment));
        $this->assertSame('paid', MerchantOrder::resolvePaymentStatus(null, 'unpaid', 'COD'));
        $this->assertSame('paid', MerchantOrder::resolvePaymentStatus(null, 'unpaid', 'cash_on_delivery'));
    }
}
