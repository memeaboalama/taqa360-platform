<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * SpecialOfferSeeder
 * -------------------
 * Seeds 5 random items with realistic sale data so the
 * /special-offers page is immediately testable.
 *
 * Run with:
 *   php artisan db:seed --class=SpecialOfferSeeder
 *
 * Rollback (clear offer data only):
 *   php artisan db:seed --class=SpecialOfferSeeder --reset
 */
class SpecialOfferSeeder extends Seeder
{
    /**
     * Discount configurations to cycle through.
     */
    private array $offerTemplates = [
        ['pct' => 50, 'expires_hours' => 24],   // flash deal
        ['pct' => 35, 'expires_hours' => null],  // unlimited
        ['pct' => 25, 'expires_hours' => 72],
        ['pct' => 15, 'expires_hours' => null],
        ['pct' => 60, 'expires_hours' => 12],    // big flash
    ];

    public function run(): void
    {
        // ── If the table is completely empty, insert demo items first ──────
        if (DB::table('items')->count() === 0) {
            $this->seedDemoItems();
            $this->command->info('Inserted demo items into items table.');
        }

        // Fetch up to 5 items eligible for a sale price
        $items = DB::table('items')
            ->whereNotNull('default_price')
            ->where('default_price', '>', 0)
            ->where(function ($q) {
                $q->whereNull('sale_price')->orWhere('is_on_sale', 0);
            })
            ->limit(5)
            ->get();

        if ($items->isEmpty()) {
            // All existing items already have sale prices — just re-tag 5 of them
            $items = DB::table('items')
                ->whereNotNull('default_price')
                ->where('default_price', '>', 0)
                ->limit(5)
                ->get();
        }

        if ($items->isEmpty()) {
            $this->command->warn('No items found in the items table. Please add products first.');
            return;
        }

        foreach ($items as $index => $item) {
            $template = $this->offerTemplates[$index % count($this->offerTemplates)];

            $salePrice  = round($item->default_price * (1 - $template['pct'] / 100), 2);
            $expiresAt  = $template['expires_hours']
                ? now()->addHours($template['expires_hours'])->toDateTimeString()
                : null;

            DB::table('items')->where('id', $item->id)->update([
                'sale_price'      => $salePrice,
                'is_on_sale'      => true,
                'sale_expires_at' => $expiresAt,
            ]);

            $this->command->info(sprintf(
                '✅  Item #%d — "%s" | Original: %s | Sale: %s (%d%% off)%s',
                $item->id,
                mb_substr($item->title ?? 'Untitled', 0, 40),
                $item->default_price,
                $salePrice,
                $template['pct'],
                $expiresAt ? " | Expires: {$expiresAt}" : ''
            ));
        }

        $this->command->info("\n🎉 Done! Visit /special-offers to see the results.");
    }

    /**
     * Insert demo items when the table is empty (dev/testing only).
     */
    private function seedDemoItems(): void
    {
        $now = now()->toDateTimeString();

        // Check required foreign keys first
        $merchantId  = DB::table('merchants')->value('id');
        $categoryId  = DB::table('categories')->value('id');

        $demoItems = [
            ['title' => json_encode(['ar' => 'كورس برمجة Python',   'en' => 'Python Programming Course']),   'default_price' => 299.00],
            ['title' => json_encode(['ar' => 'كورس تصميم UI/UX',    'en' => 'UI/UX Design Course']),         'default_price' => 199.00],
            ['title' => json_encode(['ar' => 'باقة تعلم Laravel',    'en' => 'Laravel Mastery Bundle']),      'default_price' => 499.00],
            ['title' => json_encode(['ar' => 'كورس أمن المعلومات',  'en' => 'Cybersecurity Fundamentals']),  'default_price' => 350.00],
            ['title' => json_encode(['ar' => 'كورس الذكاء الاصطناعي', 'en' => 'AI & Machine Learning']),   'default_price' => 599.00],
        ];

        foreach ($demoItems as $item) {
            DB::table('items')->insert([
                'title'         => $item['title'],
                'default_price' => $item['default_price'],
                'status'        => 'active',
                'merchant_id'   => $merchantId,
                'category_id'   => $categoryId,
                'stock'         => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
