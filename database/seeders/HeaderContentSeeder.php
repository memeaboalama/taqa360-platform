<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdPage;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;

class HeaderContentSeeder extends Seeder
{
    public function run(): void
    {
        $page = AdPage::where('key', 'home')->first();

        if ($page) {
            $placement = AdPlacement::create([
                'page_id' => $page->id,
                'name' => [
                    'en' => 'Header Content',
                    'ar' => 'محتوى رأس الصفحة',
                ],
                'key' => 'site_header',
                'type' => PlacementTypeEnum::HEADER,
                'location' => 'header',
                'status' => StatusEnum::ACTIVE,
                'sort_order' => 0,
                'settings' => [
                    'max_items' => 1,
                    'display' => [
                        'devices' => [
                            'desktop' => [
                                'enabled' => true,
                                'width' => 200,
                                'height' => 80,
                            ],
                            'mobile' => [
                                'enabled' => true,
                                'width' => 150,
                                'height' => 60,
                            ],
                        ],
                    ],
                ],
            ]);


            if ($placement) {
                $exists = AdBanner::where('placement_id', $placement->id)->exists();

                if (!$exists) {
                    AdBanner::create([
                        'placement_id' => $placement->id,
                        'name' => 'Default Header Content',
                        'title' => [
                            'en' => 'TAQA',
                            'ar' => 'طاقة',
                        ],
                        'subtitle' => [
                            'en' => 'Welcome to TAQA!',
                            'ar' => 'مرحباً بكم في متجر طاقة!',
                        ],
                        'content' => [
                            'images' => [
                                'desktop' => null,
                                'mobile' => null,
                                'favicon' => null,
                            ],
                            'offer_text' => [
                                'en' => 'Special Offer! Get 50% Off',
                                'ar' => 'عرض خاص! احصل على خصم 50%',
                            ],
                            'offer_link' => '#',
                        ],
                        'status' => BannerStatusEnum::ACTIVE,
                        'priority' => 0,
                        'start_date' => now(),
                        'created_by' => 1,
                    ]);
                }
            }
        }

    }
}
