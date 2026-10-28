<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Models\AdBanner;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\BannerStatusEnum;

class FooterPlacementSeeder extends Seeder
{
    public function run()
    {
        $homePage = AdPage::where('key', 'home')->first();

        if (!$homePage) {
            $this->command->error('Ad Pages not found. Please run AdPagesSeeder first.');
            return;
        }

        $placement = AdPlacement::updateOrCreate(
            ['key' => 'footer_main_section'],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'فوتر الموقع',
                    'en' => 'Site Footer',
                ),
                'location' => 'footer',
                'type' => PlacementTypeEnum::FOOTER,
                'settings' => [],
                'status' => 'active',
                'sort_order' => 10,
                'meta' => NULL,
            ]
        );

        AdPlacement::where('key', 'footer_main_links')->delete();


        $banner = AdBanner::where('placement_id', $placement->id)->first();

        if (!$banner) {
            $contentData = [
                'logo_is_image_desktop' => true,
                'description' => [
                    'en' => 'We provide the best educational solutions for students and teachers. Join us today!',
                    'ar' => 'نحن نقدم أفضل الحلول التعليمية للطلاب والمعلمين. انضم إلينا اليوم!',
                ],
                'copyright' => [
                    'en' => '© 2024 Edu Solutions. All rights reserved.',
                    'ar' => '©',
                ],
                'contact' => [
                    'phone' => '+966 50 123 4567',
                    'email' => 'info@edusolutions.com',
                    'address' => [
                        'en' => 'Riyadh, Saudi Arabia',
                        'ar' => 'الرياض، المملكة العربية السعودية',
                    ]
                ],
                'social' => [
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'instagram' => 'https://instagram.com',
                    'whatsapp' => 'https://wa.me/966501234567',
                ],
                'links' => [
                    'terms' => url('/terms'),
                    'privacy' => url('/privacy'),
                    'contact' => url('/contact'),
                ]
            ];

            AdBanner::create([
                'placement_id' => $placement->id,
                'name' => 'Footer Content',
                'status' => BannerStatusEnum::ACTIVE,
                'priority' => 1,
                'title' => ['en' => 'Footer', 'ar' => 'الفوتر'],
                'content' => $contentData,
            ]);

            $this->command->info('Default Footer Content created.');
        }

        $this->command->info('Footer placement seeded successfully.');
    }
}
