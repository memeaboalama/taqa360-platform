<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdProductContent;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\ProductContentStatusEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdContentSeeder extends Seeder
{
    public function run()
    {
        $products = Item::where('status', 'active')->inRandomOrder()->take(50)->get();

        if ($products->isEmpty()) {
            $this->command->info('No active products found. Skipping content seeding.');
            return;
        }

        $this->seedProductContent($products);
        $this->seedBannerContent($products);
    }

    private function seedProductContent($products)
    {
        $placements = [
            'home_product_tabs' => ['featured', 'latest', 'best_selling'],
            'home_new_arrivals' => [null],
            'home_exclusive_collection' => [null],
            'home_exclusive_slider' => [null],
            'sidebar_new_products' => [null],
            'sidebar_recently_viewed' => [null],
        ];

        foreach ($placements as $key => $groups) {
            $placement = AdPlacement::where('key', $key)->first();

            if (!$placement) {
                continue;
            }


            foreach ($groups as $groupKey) {
                $selectedProducts = $products->random(min(6, $products->count()));

                foreach ($selectedProducts as $index => $product) {
                    AdProductContent::firstOrCreate(
                        [
                            'placement_id' => $placement->id,
                            'product_id' => $product->id,
                            'group_key' => $groupKey,
                        ],
                        [
                            'status' => ProductContentStatusEnum::ACTIVE,
                            'priority' => $index + 1,
                            'start_date' => now()->subDay(),
                            'end_date' => now()->addYear(),
                        ]
                    );
                }
            }
        }
    }

    private function seedBannerContent($products)
    {
        $bannerPlacements = [
            'home_featured_grid' => 4,
        ];

        foreach ($bannerPlacements as $key => $count) {
            $placement = AdPlacement::where('key', $key)->first();

            if (!$placement) {
                continue;
            }

            $selectedProducts = $products->random(min($count, $products->count()));

            foreach ($selectedProducts as $index => $product) {
                AdBanner::firstOrCreate(
                    [
                        'placement_id' => $placement->id,
                        'name' => "Generated Product Banner " . $product->id,
                    ],
                    [
                        'title' => ['en' => $product->title, 'ar' => $product->title],
                        'subtitle' => ['en' => 'Shop Now', 'ar' => 'تسوق الآن'],
                        'content' => [
                            'images' => [
                                'desktop' => 'assets/images/ecommerce/Product/1.jpg',
                                'mobile' => 'assets/images/ecommerce/Product/1.jpg',
                            ],
                            'alt_text' => ['en' => $product->title, 'ar' => $product->title],
                        ],
                        'click_action' => [
                            'type' => 'product',
                            'id' => $product->id,
                            'url' => route('product.show', $product->id),
                        ],
                        'status' => BannerStatusEnum::ACTIVE,
                        'priority' => $index + 1,
                        'start_date' => now()->subDay(),
                        'end_date' => now()->addYear(),
                    ]
                );
            }
        }
    }
}
