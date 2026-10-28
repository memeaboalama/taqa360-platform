<?php

namespace Database\Seeders;

use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * StoreItemsSeeder
 * ---------------
 * The project ships no seeder for the `items` table, so the store pages have
 * nothing to show. This one fills it using the models that already exist
 * (Merchant / Category / Subcategory) — no new tables, models or relations.
 *
 * Idempotent: every row is matched on (merchant_id, title). Re-running it
 * updates nothing and creates no duplicates.
 *
 * Run with:
 *   php artisan db:seed --class=StoreItemsSeeder
 */
class StoreItemsSeeder extends Seeder
{
    /** Percentage off + expiry used for the "special offers" page. */
    private const OFFER_TEMPLATES = [
        ['pct' => 50, 'hours' => 24],
        ['pct' => 35, 'hours' => null],
        ['pct' => 25, 'hours' => 72],
        ['pct' => 15, 'hours' => null],
        ['pct' => 60, 'hours' => 12],
    ];

    /**
     * Bilingual product names keyed by the store they belong to.
     * The first four stores get a richer catalogue so the special-offers
     * filters (10-30 / 30-50 / 50+ and flash) all have something to match.
     */
    private function catalogue(): array
    {
        return [
            1 => [
                ['ar' => 'لوحة شمسية 450 واط', 'en' => '450W Solar Panel', 'price' => 320.00],
                ['ar' => 'منظم طاقة 3000 واط', 'en' => '3000W Power Inverter', 'price' => 780.00],
                ['ar' => 'بطارية ليثيوم 12 فولت', 'en' => '12V Lithium Battery', 'price' => 540.00],
                ['ar' => 'كوابل شمسية 6 مم', 'en' => '6mm Solar Cable (10m)', 'price' => 95.00],
                ['ar' => 'حامل ألواح ألمنيوم', 'en' => 'Aluminium Panel Mount', 'price' => 150.00],
                ['ar' => 'منظم شحن 20 أمبير', 'en' => '20A Charge Controller', 'price' => 210.00],
                ['ar' => 'بطارية عيارية 150Ah', 'en' => '150Ah Deep Cycle Battery', 'price' => 890.00],
                ['ar' => 'م inverter موجة جيبية', 'en' => 'Pure Sine Wave Inverter', 'price' => 1250.00],
            ],
            2 => [
                ['ar' => 'مروحة هوائية 400 مل', 'en' => '400ml Wind Water Pump', 'price' => 260.00],
                ['ar' => 'عنفة رياح صغيرة', 'en' => 'Mini Wind Turbine Kit', 'price' => 640.00],
                ['ar' => 'مولد كهربائي 2kW', 'en' => '2kW Diesel Generator', 'price' => 1450.00],
                ['ar' => 'خزان مياه 500 لتر', 'en' => '500L Water Tank', 'price' => 180.00],
            ],
            3 => [
                ['ar' => 'معدات سلامة للورش', 'en' => 'Workshop Safety Kit', 'price' => 320.00],
                ['ar' => 'قفازات حماية حرارية', 'en' => 'Heat Resistant Gloves', 'price' => 75.00],
                ['ar' => 'نظارة واقية من الغبار', 'en' => 'Anti Dust Goggles', 'price' => 45.00],
            ],
            4 => [
                ['ar' => 'مواد بناء عازلة', 'en' => 'Insulation Building Material', 'price' => 220.00],
                ['ar' => 'ألواح جبس بورد', 'en' => 'Gypsum Plaster Boards', 'price' => 165.00],
                ['ar' => 'أسمنت مقاوم للماء', 'en' => 'Waterproof Cement (25kg)', 'price' => 58.00],
            ],
        ];
    }

    public function run(): void
    {
        $merchants = Merchant::where('status', StatusEnum::ACTIVE->value)
            ->orderBy('id')
            ->pluck('id')
            ->all();

        if (empty($merchants)) {
            $this->command->warn('No active merchants found. Run MerchantSeeder first.');
            return;
        }

        $categories = Category::orderBy('id')->get();
        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Run the ecommerce CategorySeeder first.');
            return;
        }

        $subcategories = Subcategory::orderBy('id')->pluck('id')->all();

        $catalogue = $this->catalogue();
        $defaultProducts = [
            ['ar' => 'طقم أدوات مدرسية متكامل', 'en' => 'Complete School Supplies Set', 'price' => 49.00],
            ['ar' => 'وسيلة تعليمية تفاعلية', 'en' => 'Interactive Learning Kit', 'price' => 125.00],
            ['ar' => 'مجموعة تجارب تعليمية', 'en' => 'Educational Experiment Set', 'price' => 89.00],
        ];
        $created = 0;
        $skipped = 0;
        $offered = 0;
        $index = 0;

        foreach ($merchants as $merchantId) {
            $products = $catalogue[$merchantId] ?? $defaultProducts;

            foreach ($products as $product) {
                // Item::$title and Item::$description are Spatie translatable,
                // so they are written as ['en' => ..., 'ar' => ...].
                $title = ['ar' => $product['ar'], 'en' => $product['en']];

                $existing = Item::where('merchant_id', $merchantId)
                    ->where('title->en', $product['en'])
                    ->first();

                if ($existing) {
                    $skipped++;
                    continue;
                }

                $category = $categories[$index % $categories->count()];

                // Every third product joins the special-offers page so the
                // discount-range filters have real rows to work with.
                $template = ($index % 3 === 0) ? self::OFFER_TEMPLATES[$index % count(self::OFFER_TEMPLATES)] : null;

                Item::create([
                    'title'            => $title,
                    'description'      => [
                        'ar' => $product['ar'] . ' — وصف تفصيلي للمنتج.',
                        'en' => $product['en'] . ' — detailed product description.',
                    ],
                    'status'           => StatusEnum::ACTIVE->value,
                    'default_price'    => $product['price'],
                    'sale_price'       => $template ? round($product['price'] * (1 - $template['pct'] / 100), 2) : null,
                    'is_on_sale'       => (bool) $template,
                    'sale_expires_at'  => $template && $template['hours']
                        ? now()->addHours($template['hours'])
                        : null,
                    'stock'            => random_int(5, 60),
                    'calories'         => 0,
                    'preparing_time'   => 0,
                    'merchant_id'      => $merchantId,
                    'main_category_id' => $category->id,
                    'sub_category_id'  => $subcategories ? $subcategories[$index % count($subcategories)] : null,
                    'check_price'      => 0,
                ]);

                $created++;
                $template ? $offered++ : 0;
                $index++;
            }
        }

        $this->command->info(sprintf(
            'Items: %d created, %d already existed (%d of them are special offers).',
            $created,
            $skipped,
            $offered
        ));
    }
}