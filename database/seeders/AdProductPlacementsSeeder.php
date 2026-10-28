<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\AdPage;
use Illuminate\Database\Seeder;
use App\Domain\AppBase\Models\AdPlacement;

class AdProductPlacementsSeeder extends Seeder
{
    public function run()
    {
        $homePage = AdPage::where('key', 'home')->first();

        if (!$homePage) {
            $this->command->error('Ecommerce Page not found. Please run AdPagesSeeder first.');
            return;
        }

        $records = [
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'مجموعات المنتجات',
                    'en' => 'Product Collections',
                ),
                'key' => 'home_product_tabs',
                'location' => 'content_middle',
                'type' => 'product_tabs',
                'settings' => array(
                    'groups' =>
                    array(
                        0 =>
                        array(
                            'key' => 'featured',
                            'query' =>
                            array(
                                'type' => 'manual',
                            ),
                            'title' =>
                            array(
                                'ar' => 'منتجات مميزة',
                                'en' => 'Featured Products',
                            ),
                            'enabled' => true,
                        ),
                        1 =>
                        array(
                            'key' => 'latest',
                            'query' =>
                            array(
                                'type' => 'latest',
                                'limit' => 12,
                            ),
                            'title' =>
                            array(
                                'ar' => 'وصل حديثاً',
                                'en' => 'New Arrivals',
                            ),
                            'enabled' => true,
                        ),
                        2 =>
                        array(
                            'key' => 'best_selling',
                            'query' =>
                            array(
                                'type' => 'best_selling',
                                'limit' => 12,
                            ),
                            'title' =>
                            array(
                                'ar' => 'الأكثر مبيعاً',
                                'en' => 'Best Selling',
                            ),
                            'enabled' => true,
                        ),
                    ),
                    'display' =>
                    array(
                        'show_tabs' => true,
                        'default_tab' => 'featured',
                    ),
                    'slider_config' =>
                    array(
                        'max_items' => 12,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 5,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'عروض حصرية',
                    'en' => 'Exclusive Deals',
                ),
                'key' => 'home_exclusive_slider',
                'location' => 'content_bottom',
                'type' => 'product_slider',
                'settings' => array(
                    'slider_config' =>
                    array(
                        'loop' => true,
                        'autoplay' => true,
                        'max_items' => 10,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 6,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'منتجات جديدة (جانبي)',
                    'en' => 'New Products (Sidebar)',
                ),
                'key' => 'sidebar_new_products',
                'location' => 'sidebar',
                'type' => 'product_sidebar',
                'settings' => array(
                    'max_items' => 9,
                ),
                'status' => 'active',
                'sort_order' => 10,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'شوهد مؤخراً (جانبي)',
                    'en' => 'Result Viewed (Sidebar)',
                ),
                'key' => 'sidebar_recently_viewed',
                'location' => 'sidebar',
                'type' => 'product_sidebar',
                'settings' => array(
                    'max_items' => 9,
                ),
                'status' => 'active',
                'sort_order' => 11,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'مجموعات حصرية',
                    'en' => 'Exclusive Collections',
                ),
                'key' => 'home_exclusive_collection',
                'location' => 'content_bottom',
                'type' => 'product_slider',
                'settings' => array(
                    'slider_config' =>
                    array(
                        'loop' => true,
                        'autoplay' => true,
                        'max_items' => 10,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 8,
                'meta' => NULL,
            ],
            [
                'page_id' => $homePage->id,
                'name' => array(
                    'ar' => 'قسم وصل حديثاً',
                    'en' => 'قسم وصل حديثاً',
                ),
                'key' => 'home_new_arrivals',
                'location' => 'content_middle',
                'type' => 'product_slider',
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
                                'width' => 1920,
                                'height' => 600,
                                'enabled' => true,
                            ),
                        ),
                        'responsive' => true,
                    ),
                ),
                'status' => 'active',
                'sort_order' => 0,
                'meta' => NULL,
            ],
        ];

        foreach ($records as $record) {
            AdPlacement::updateOrCreate(['key' => $record['key']], $record);
        }
    }
}
