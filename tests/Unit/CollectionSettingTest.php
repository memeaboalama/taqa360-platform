<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\OrderSettlement;
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
}
