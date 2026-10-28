<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdPage;

class AdBannerPlacementsSeeder extends Seeder
{
    public function run()
    {
        $homePage = AdPage::where('key', 'home')->first();

        if (!$homePage) {
            $this->command->error('Ad Pages not found. Please run AdPagesSeeder first.');
            return;
        }

        $records = [
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'سلايدر الواجهة الرئيسية',
                    'en' => 'Main Hero Slider',
                ),
                'key' => 'home_hero_slider',
                'location' => 'header',
                'type' => 'slider',
                'settings' => array(
                    'actions' =>
                    array(
                        'allowed_types' =>
                        array(
                            0 => 'url',
                            1 => 'product',
                            2 => 'category',
                        ),
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 768,
                                'height' => 300,
                                'enabled' => true,
                            ),
                            'tablet' =>
                            array(
                                'width' => 1024,
                                'height' => 400,
                                'enabled' => true,
                            ),
                            'desktop' =>
                            array(
                                'width' => 1095,
                                'height' => 562,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                    'slider_config' =>
                    array(
                        'loop' => true,
                        'autoplay' => true,
                        'show_dots' => true,
                        'show_arrows' => true,
                        'autoplay_speed' => 5000,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 1,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'شبكة بانرات المنتجات',
                    'en' => 'Product Banners Grid',
                ),
                'key' => 'home_featured_grid',
                'location' => 'content_top',
                'type' => 'grid_banners',
                'settings' => array(
                    'actions' =>
                    array(
                        'allowed_types' =>
                        array(
                            0 => 'url',
                            1 => 'product',
                            2 => 'category',
                            3 => 'merchant',
                            4 => 'none',
                        ),
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 768,
                                'height' => 300,
                                'enabled' => true,
                            ),
                            'tablet' =>
                            array(
                                'width' => 1024,
                                'height' => 400,
                                'enabled' => true,
                            ),
                            'desktop' =>
                            array(
                                'width' => 565,
                                'height' => 320,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                    'grid_config' =>
                    array(
                        'gap' => 20,
                        'columns' =>
                        array(
                            'mobile' => 1,
                            'tablet' => 2,
                            'desktop' => '2',
                        ),
                        'max_items' => 8,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 2,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'خدمات القائمة الجانبية',
                    'en' => 'Sidebar Services',
                ),
                'key' => 'sidebar_services',
                'location' => 'sidebar',
                'type' => 'grid_banners',
                'settings' => [],
                'status' => 'active',
                'sort_order' => 12,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'سلايدر الفئات',
                    'en' => 'Categories Carousel',
                ),
                'key' => 'home_categories_carousel',
                'location' => 'content_top',
                'type' => 'carousel',
                'settings' => array(
                    'slider_config' =>
                    array(
                        'loop' => true,
                        'autoplay' => false,
                        'max_items' => 12,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 3,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'البانر الجانبي الاول',
                    'en' => 'البانر الجانبي الاول',
                ),
                'key' => 'sidebar_banner',
                'location' => 'sidebar',
                'type' => 'static_image',
                'settings' => array(
                    'actions' =>
                    array(
                        'allowed_types' =>
                        array(
                            0 => 'url',
                            1 => 'product',
                            2 => 'category',
                            3 => 'merchant',
                            4 => 'none',
                        ),
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 768,
                                'height' => 300,
                                'enabled' => true,
                            ),
                            'tablet' =>
                            array(
                                'width' => 1024,
                                'height' => 400,
                                'enabled' => true,
                            ),
                            'desktop' =>
                            array(
                                'width' => 350,
                                'height' => 400,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 1,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'البانر الجانبي الثاني',
                    'en' => 'Sidebar Banner 2',
                ),
                'key' => 'sidebar_banner_2',
                'location' => 'sidebar',
                'type' => 'static_image',
                'settings' => array(
                    'actions' =>
                    array(
                        'allowed_types' =>
                        array(
                            0 => 'url',
                            1 => 'product',
                            2 => 'category',
                            3 => 'merchant',
                            4 => 'none',
                        ),
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 400,
                                'height' => 300,
                                'enabled' => true,
                            ),
                            'tablet' =>
                            array(
                                'width' => 400,
                                'height' => 300,
                                'enabled' => true,
                            ),
                            'desktop' =>
                            array(
                                'width' => 350,
                                'height' => 400,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 2,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'بانر ترويجي وسطي',
                    'en' => 'Mid-Page Promo',
                ),
                'key' => 'large_banner',
                'location' => 'content_middle',
                'type' => 'static_image',
                'settings' => array(
                    'actions' =>
                    array(
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 350,
                                'height' => 100,
                                'enabled' => true,
                            ),
                            'desktop' =>
                            array(
                                'width' => 1160,
                                'height' => 200,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 3,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'شريط العلامات التجارية',
                    'en' => 'Top Brands Carousel',
                ),
                'key' => 'home_brands_carousel',
                'location' => 'footer',
                'type' => 'carousel',
                'settings' => array(
                    'actions' =>
                    array(
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'desktop' =>
                            array(
                                'width' => 125,
                                'height' => 125,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                    'slider_config' =>
                    array(
                        'loop' => true,
                        'autoplay' => true,
                        'items_per_view' =>
                        array(
                            'mobile' => 2,
                            'tablet' => 4,
                            'desktop' => 6,
                        ),
                    ),
                ),
                'status' => 'active',
                'sort_order' => 4,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'خدماتنا',
                    'en' => 'Our Services',
                ),
                'key' => 'services_section',
                'location' => 'content_middle',
                'type' => 'grid_banners',
                'settings' => array(
                    'actions' =>
                    array(
                        'allowed_types' =>
                        array(
                            0 => 'none',
                        ),
                        'click_enabled' => false,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 768,
                                'height' => 300,
                                'enabled' => false,
                            ),
                            'tablet' =>
                            array(
                                'width' => 1024,
                                'height' => 400,
                                'enabled' => false,
                            ),
                            'desktop' =>
                            array(
                                'width' => 50,
                                'height' => 50,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                    'grid_config' =>
                    array(
                        'gap' => 15,
                        'columns' =>
                        array(
                            'mobile' => 1,
                            'tablet' => 1,
                            'desktop' => 1,
                        ),
                        'max_items' => 4,
                    ),
                ),
                'status' => 'inactive',
                'sort_order' => 5,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'أخبار حديثة',
                    'en' => 'Recent News',
                ),
                'key' => 'recent_news',
                'location' => 'content_bottom',
                'type' => 'slider',
                'settings' => array(
                    'actions' =>
                    array(
                        'allowed_types' =>
                        array(
                            0 => 'url',
                        ),
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 350,
                                'height' => 300,
                                'enabled' => true,
                            ),
                            'tablet' =>
                            array(
                                'width' => 400,
                                'height' => 300,
                                'enabled' => true,
                            ),
                            'desktop' =>
                            array(
                                'width' => 400,
                                'height' => 300,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                    'slider_config' =>
                    array(
                        'loop' => true,
                        'autoplay' => true,
                        'max_items' => 6,
                        'show_dots' => true,
                        'show_arrows' => true,
                        'autoplay_speed' => 5000,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 7,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'عملائنا',
                    'en' => 'Our Clients',
                ),
                'key' => 'client_brands',
                'location' => 'content_bottom',
                'type' => 'slider',
                'settings' => array(
                    'actions' =>
                    array(
                        'allowed_types' =>
                        array(
                            0 => 'url',
                            1 => 'merchant',
                        ),
                        'click_enabled' => true,
                    ),
                    'display' =>
                    array(
                        'devices' =>
                        array(
                            'mobile' =>
                            array(
                                'width' => 120,
                                'height' => 60,
                                'enabled' => true,
                            ),
                            'tablet' =>
                            array(
                                'width' => 150,
                                'height' => 80,
                                'enabled' => true,
                            ),
                            'desktop' =>
                            array(
                                'width' => '125',
                                'height' => '125',
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                    'slider_config' =>
                    array(
                        'loop' => true,
                        'autoplay' => true,
                        'max_items' => 10,
                        'show_dots' => false,
                        'show_arrows' => false,
                        'swipe_enabled' => true,
                        'autoplay_speed' => 3000,
                        'pause_on_hover' => true,
                        'transition_speed' => 800,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 8,
                'meta' => NULL,
            ]
        ];

        foreach ($records as $record) {
            AdPlacement::updateOrCreate(['key' => $record['key']], $record);
        }
    }
}
