<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\AdMerchantContent;
use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Models\AdPlacement;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantSliderSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $homePage = AdPage::where('key', 'home')->first();

            if (!$homePage) {
                $this->command->error('Home page not found. Please run AdPagesSeeder first.');
                DB::rollBack();
                return;
            }

            $merchantPlacement = AdPlacement::updateOrCreate(
                [
                    'key' => 'home_merchant_slider',
                    'page_id' => $homePage->id,
                ],
                [
                    'name' => [
                        'en' => 'Merchant Slider',
                        'ar' => 'سلايدر المتاجر'
                    ],
                    'location' => 'content_middle',
                    'type' => PlacementTypeEnum::MERCHANT_SLIDER,
                    'status' => StatusEnum::ACTIVE,
                    'sort_order' => 70,
                    'settings' => [
                        'display' => [
                            'devices' => [
                                'desktop' => ['enabled' => true, 'width' => 200, 'height' => 200],
                                'tablet' => ['enabled' => true, 'width' => 180, 'height' => 180],
                                'mobile' => ['enabled' => true, 'width' => 150, 'height' => 150],
                            ],
                            'responsive' => true,
                        ],
                        'slider_config' => [
                            'max_items' => 10,
                            'autoplay' => true,
                            'autoplay_speed' => 3000,
                            'transition_speed' => 600,
                            'loop' => true,
                            'show_arrows' => false,
                            'show_dots' => true,
                            'pause_on_hover' => true,
                            'swipe_enabled' => true,
                        ],
                        'actions' => [
                            'click_enabled' => true,
                            'allowed_types' => ['url', 'merchant'],
                        ],
                    ],
                ]
            );

            $this->command->info("Merchant slider placement created with ID: {$merchantPlacement->id}");

            $merchants = Merchant::where('status', 'active')
                ->limit(10)
                ->get();

            if ($merchants->isEmpty()) {
                $this->command->warn('No active merchants found in database. Skipping merchant content creation.');
                DB::commit();
                return;
            }

            $this->command->info("Found {$merchants->count()} active merchants");

            AdMerchantContent::where('placement_id', $merchantPlacement->id)->delete();

            foreach ($merchants as $index => $merchant) {
                AdMerchantContent::create([
                    'placement_id' => $merchantPlacement->id,
                    'merchant_id' => $merchant->id,
                    'status' => StatusEnum::ACTIVE,
                    'priority' => $index + 1,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                ]);
            }

            $this->command->info('Created ' . $merchants->count() . ' merchant slider items');

            DB::commit();
            $this->command->info('✅ Merchant slider seeded successfully with real merchants!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error seeding merchant slider: ' . $e->getMessage());
            throw $e;
        }
    }
}
