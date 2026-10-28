<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\Promocode;
use App\Support\Enum\Promocode\PromocodeStatus;
use Illuminate\Database\Seeder;

/**
 * StoreCouponsSeeder
 * ------------------
 * The special-offers page reads its voucher cards from `promocodes` (and falls
 * back to `offers`). Both tables are empty after a local rebuild, so the
 * "exclusive coupons" strip renders nothing.
 *
 * Reuses the existing Promocode model and PromocodeStatus enum. No new tables.
 *
 * Idempotent: matched on the unique `code` column, so re-running updates the
 * existing rows instead of inserting duplicates.
 *
 * Run with:
 *   php artisan db:seed --class=StoreCouponsSeeder
 */
class StoreCouponsSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['code' => 'WELCOME10', 'type' => 'percent', 'discount_value' => 10, 'min_order_value' => 100, 'max_discount' => 200, 'usages_left' => 500, 'hours' => null],
            ['code' => 'SAVE25',    'type' => 'percent', 'discount_value' => 25, 'min_order_value' => 250, 'max_discount' => 500, 'usages_left' => 300, 'hours' => 72],
            ['code' => 'HALF50',    'type' => 'percent', 'discount_value' => 50, 'min_order_value' => 400, 'max_discount' => 800, 'usages_left' => 120, 'hours' => 24],
            ['code' => 'FLAT100',   'type' => 'flat',    'discount_value' => 100, 'min_order_value' => 500, 'max_discount' => null, 'usages_left' => 80,  'hours' => 168],
            ['code' => 'EXPIRED5',  'type' => 'percent', 'discount_value' => 5,  'min_order_value' => null, 'max_discount' => null, 'usages_left' => 0,   'hours' => -48],
        ];

        $created = 0;
        $updated = 0;

        foreach ($coupons as $coupon) {
            $payload = [
                'usages_left'     => $coupon['usages_left'],
                'min_order_value' => $coupon['min_order_value'],
                'max_discount'    => $coupon['max_discount'],
                'type'            => $coupon['type'],
                'discount_value'  => $coupon['discount_value'],
                'source'          => 'Coupon',
                'status'          => $coupon['hours'] < 0
                    ? PromocodeStatus::INACTIVE
                    : PromocodeStatus::ACTIVE,
                'expired_at'      => $coupon['hours'] === null
                    ? null
                    : now()->addHours($coupon['hours']),
            ];

            $existing = Promocode::where('code', $coupon['code'])->first();

            if ($existing) {
                $existing->update($payload);
                $updated++;
                continue;
            }

            Promocode::create(['code' => $coupon['code']] + $payload);
            $created++;
        }

        $this->command->info("Coupons: $created created, $updated refreshed.");
    }
}