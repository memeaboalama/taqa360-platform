<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\AppBase\Models\AdPage;

class AdPagesSeeder extends Seeder
{
    public function run()
    {
        $records = [
            [
                'name' => array(
                    'ar' => 'الصفحة الرئيسية للمتجر',
                    'en' => 'Ecommerce Home',
                ),
                'key' => 'home',
                'description' => array(
                    'ar' => 'الصفحة الرئيسية للمتجر الإلكتروني',
                    'en' => 'Main landing page of the ecommerce store',
                ),
                'status' => 'active',
                'sort_order' => 1,
                'meta' => NULL,
            ],
            [
                'name' => array(
                    'ar' => 'صفحة الأقسام',
                    'en' => 'Category Page',
                ),
                'key' => 'category',
                'description' => array(
                    'ar' => 'قائمة أقسام المنتجات',
                    'en' => 'Product category listing',
                ),
                'status' => 'active',
                'sort_order' => 2,
                'meta' => NULL,
            ],
            [
                'name' => array(
                    'ar' => 'تفاصيل المنتج',
                    'en' => 'Product Details',
                ),
                'key' => 'product_detail',
                'description' => array(
                    'ar' => 'عرض منتج واحد',
                    'en' => 'Single product view',
                ),
                'status' => 'active',
                'sort_order' => 3,
                'meta' => NULL,
            ],
            [
                'name' => array(
                    'ar' => 'نجاح الدفع',
                    'en' => 'Checkout Success',
                ),
                'key' => 'checkout_success',
                'description' => array(
                    'ar' => 'صفحة الشكر',
                    'en' => 'Thank you page',
                ),
                'status' => 'active',
                'sort_order' => 4,
                'meta' => NULL,
            ],
        ];

        foreach ($records as $record) {
            AdPage::updateOrCreate(['key' => $record['key']], $record);
        }
    }
}
