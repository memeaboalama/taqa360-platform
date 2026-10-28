<?php

namespace Database\Seeders\ecommerce;

use Domain\MarketPlace\Models\Catalog\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sub_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [

            // 1. Solar Energy Equipment
            1 => [
                ['Solar Panels', 'ألواح شمسية'],
                ['Solar Inverters', 'محولات شمسية'],
                ['Solar Batteries', 'بطاريات شمسية'],
                ['Solar Chargers', 'شواحن شمسية'],
                ['Mounting Structures', 'هياكل تركيب'],
            ],

            // 2. Wind Power Equipment
            2 => [
                ['Wind Turbines', 'توربينات الرياح'],
                ['Wind Controllers', 'منظمات الرياح'],
                ['Wind Generator Blades', 'شفـرات مولدات الرياح'],
                ['Wind Towers', 'أبراج الرياح'],
                ['Wind Sensors', 'حساسات سرعة الرياح'],
            ],

            // 3. Environment
            3 => [
                ['Recycling Equipment', 'معدات إعادة التدوير'],
                ['Air Quality Devices', 'أجهزة جودة الهواء'],
                ['Water Treatment', 'معالجة المياه'],
                ['Environmental Meters', 'أجهزة قياس بيئي'],
            ],

            // 4. Safety Equipment
            4 => [
                ['Fire Extinguishers', 'طفايات حريق'],
                ['Safety Helmets', 'خوذ سلامة'],
                ['Protective Gloves', 'قفازات حماية'],
                ['Emergency Kits', 'حقائب طوارئ'],
                ['Safety Signs', 'علامات السلامة'],
            ],

            // 5. Wells
            5 => [
                ['Water Pumps', 'مضخات مياه'],
                ['Well Motors', 'موتورات آبار'],
                ['Control Panels', 'لوحات تحكم آبار'],
                ['Pipes', 'أنابيب'],
                ['Filtration Systems', 'أنظمة ترشيح'],
            ],

            // 6. Electricity
            6 => [
                ['Breakers', 'قواطع كهربائية'],
                ['Switches', 'مفاتيح كهربائية'],
                ['Distribution Boards', 'لوحات توزيع'],
                ['Transformers', 'محولات كهربائية'],
                ['Smart Meters', 'عدادات ذكية'],
            ],

            // 7. Building Materials
            7 => [
                ['Cement', 'اسمنت'],
                ['Bricks', 'طوب'],
                ['Steel', 'حديد'],
                ['Wood', 'خشب'],
                ['Tiles', 'بلاط'],
            ],

            // 8. Batteries
            8 => [
                ['Lithium Batteries', 'بطاريات ليثيوم'],
                ['Lead Acid Batteries', 'بطاريات رصاص'],
                ['Car Batteries', 'بطاريات سيارات'],
                ['Dry Batteries', 'بطاريات جافة'],
            ],

            // 9. Cables
            9 => [
                ['Electrical Cables', 'كابلات كهربائية'],
                ['Solar Cables', 'كابلات شمسية'],
                ['Communication Cables', 'كابلات اتصالات'],
                ['Fiber Optic Cables', 'ألياف بصرية'],
            ],

            // 10. Converters
            10 => [
                ['DC to AC Inverters', 'محولات DC إلى AC'],
                ['Voltage Stabilizers', 'مثبتات جهد'],
                ['Power Supplies', 'مزودات طاقة'],
                ['Charge Controllers', 'منظمات شحن'],
            ],
        ];

        foreach ($data as $categoryId => $subs) {
            foreach ($subs as $sub) {
                Subcategory::create([
                    'title' => ['en' => $sub[0], 'ar' => $sub[1]],
                    'slug' => strtolower(str_replace(' ', '-', $sub[0])),
                    'description' =>  $sub[0].'|'. $sub[1],
                    'category_id' => $categoryId,
                    'image' => null,
                    'is_active' => 1,
                ]);
            }
        }
    }
}
