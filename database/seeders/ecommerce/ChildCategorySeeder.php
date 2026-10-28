<?php

namespace Database\Seeders\ecommerce;

use Illuminate\Database\Seeder;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChildCategorySeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('child_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $childrenMap = [
            'Solar Panels' => [
                ['Monocrystalline', 'أحادية البلورة'],
                ['Polycrystalline', 'متعددة البلورة'],
                ['Thin Film', 'أغشية رقيقة'],
                ['Bifacial', 'ثنائية الوجه'],
            ],
            'Solar Inverters' => [
                ['Hybrid Inverters', 'محولات هجينة'],
                ['Off-Grid Inverters', 'محولات خارج الشبكة'],
                ['On-Grid Inverters', 'محولات متصلة بالشبكة'],
                ['Micro Inverters', 'محولات دقيقة'],
            ],
            'Solar Batteries' => [
                ['Gel Deep Cycle', 'جل عميقة الدورة'],
                ['Tubular Batteries', 'بطاريات أنبوبية'],
                ['LiFePO4', 'ليثيوم فوسفات الحديد'],
                ['Lead Carbon', 'رصاص كربوني'],
            ],
            'Solar Chargers' => [
                ['MPPT Controllers', 'منظمات MPPT'],
                ['PWM Controllers', 'منظمات PWM'],
            ],
            'Mounting Structures' => [
                ['Roof Mounts', 'تركيبات الأسطح'],
                ['Ground Mounts', 'تركيبات أرضية'],
                ['Solar Trackers', 'متتبعات شمسية'],
            ],

            'Wind Turbines' => [
                ['Horizontal Axis', 'توربينات محور أفقي'],
                ['Vertical Axis', 'توربينات محور عمودي'],
            ],
            'Wind Controllers' => [
                ['Wind Charge Controller', 'منظم شحن رياح'],
                ['Dump Load', 'حمل تفريغ'],
            ],
            'Wind Generator Blades' => [
                ['Fiberglass Blades', 'شفرات فايبر جلاس'],
                ['Carbon Fiber Blades', 'شفرات ألياف الكربون'],
            ],
            'Wind Towers' => [
                ['Guyed Towers', 'أبراج مثبتة بكوابل'],
                ['Lattice Towers', 'أبراج شبكية'],
            ],
            'Wind Sensors' => [
                ['Anemometers', 'مقياس سرعة الرياح'],
                ['Wind Vanes', 'مؤشر اتجاه الرياح'],
            ],

            'Recycling Equipment' => [
                ['Shredders', 'فرامات'],
                ['Balers', 'مكابس'],
            ],
            'Air Quality Devices' => [
                ['Air Purifiers', 'منقيات هواء'],
                ['CO2 Sensors', 'حساسات غازات'],
            ],
            'Water Treatment' => [
                ['RO Systems', 'أنظمة تناضح عكسي'],
                ['Water Softeners', 'أجهزة إزالة العسر'],
            ],
            'Environmental Meters' => [
                ['PH Meters', 'مقياس حموضة'],
                ['TDS Meters', 'مقياس أملاح'],
            ],

            'Fire Extinguishers' => [
                ['CO2', 'ثاني أكسيد الكربون'],
                ['Dry Powder', 'بودرة جافة'],
                ['Foam', 'رغوة'],
            ],
            'Safety Helmets' => [
                ['Safety Helmet', 'خوذة سلامة'],
                ['Bump Cap', 'قبعة حماية خفيفة'],
            ],
            'Protective Gloves' => [
                ['Leather Gloves', 'قفازات جلدية'],
                ['Chemical Gloves', 'قفازات كيميائية'],
                ['Electrical Gloves', 'قفازات عزل كهربائي'],
            ],
            'Emergency Kits' => [
                ['First Aid Box', 'صندوق إسعافات'],
                ['Eyewash Station', 'محطة غسل عيون'],
            ],
            'Safety Signs' => [
                ['Warning Signs', 'لوحات تحذيرية'],
                ['Prohibition Signs', 'لوحات منع'],
            ],

            'Water Pumps' => [
                ['Submersible Pumps', 'مضخات غاطسة'],
                ['Surface Pumps', 'مضخات سطحية'],
                ['Solar Pumps', 'مضخات شمسية'],
            ],
            'Well Motors' => [
                ['Submersible Motors', 'موتورات غاطسة'],
                ['Rewindable Motors', 'موتورات قابلة للف'],
            ],
            'Control Panels' => [
                ['Star Delta Panel', 'لوحة ستار دلتا'],
                ['Soft Starter', 'بادئ حركة ناعم'],
                ['VFD Drive', 'مغير سرعة VFD'],
            ],
            'Pipes' => [
                ['UPVC Pipes', 'أنابيب UPVC'],
                ['Galvanized Pipes', 'أنابيب مجلفنة'],
                ['HDPE Pipes', 'أنابيب بولي إيثيلين'],
            ],
            'Filtration Systems' => [
                ['Sand Filters', 'فلاتر رملية'],
                ['Disc Filters', 'فلاتر قرصية'],
            ],

            'Breakers' => [
                ['MCB', 'قاطع MCB'],
                ['MCCB', 'قاطع مقولب MCCB'],
                ['RCBO', 'قاطع حماية RCBO'],
            ],
            'Switches' => [
                ['Isolator', 'قاطع عزل'],
                ['Changeover Switch', 'مفتاح قلاب'],
            ],
            'Distribution Boards' => [
                ['Main DB', 'لوحة توزيع رئيسية'],
                ['Sub DB', 'لوحة توزيع فرعية'],
            ],
            'Transformers' => [
                ['Step Up', 'رافع للجهد'],
                ['Step Down', 'خافض للجهد'],
            ],
            'Smart Meters' => [
                ['Single Phase Meter', 'عداد 1 فاز'],
                ['Three Phase Meter', 'عداد 3 فاز'],
            ],

            'Cement' => [
                ['Portland Cement', 'اسمنت بورتلاندي'],
                ['White Cement', 'اسمنت أبيض'],
            ],
            'Bricks' => [
                ['Red Clay Bricks', 'طوب أحمر'],
                ['Concrete Blocks', 'بلوك اسمنتي'],
            ],
            'Steel' => [
                ['Rebar', 'حديد تسليح'],
                ['Steel Angles', 'زوايا حديد'],
            ],
            'Wood' => [
                ['Plywood', 'خشب معاكس'],
                ['MDF', 'خشب MDF'],
            ],
            'Tiles' => [
                ['Ceramic', 'سيراميك'],
                ['Porcelain', 'بورسلان'],
                ['Granite', 'جرانيت'],
            ],

            'Lithium Batteries' => [
                ['Li-Ion Cells', 'خلايا ليثيوم أيون'],
                ['Wall Mount Battery', 'بطارية جدارية'],
            ],
            'Lead Acid Batteries' => [
                ['Flooded Battery', 'بطارية سائلة'],
                ['VRLA Battery', 'بطارية مغلقة'],
            ],
            'Car Batteries' => [
                ['MF Battery', 'بطارية بدون صيانة'],
                ['Dry Charge', 'شحن جاف'],
            ],
            'Dry Batteries' => [
                ['Alkaline', 'قلوية'],
                ['Rechargeable', 'قابلة للشحن'],
            ],

            'Electrical Cables' => [
                ['Copper Cables', 'كابلات نحاسية'],
                ['Aluminum Cables', 'كابلات ألمنيوم'],
                ['Armored Cables', 'كابلات مسلحة'],
            ],
            'Solar Cables' => [
                ['4mm DC Cable', 'كابل شمسي 4مم'],
                ['6mm DC Cable', 'كابل شمسي 6مم'],
            ],
            'Communication Cables' => [
                ['Cat6 Cable', 'كابل شبكة Cat6'],
                ['Fiber Optic', 'ألياف بصرية'],
            ],
            'Fiber Optic Cables' => [
                ['Single Mode', 'ألياف أحادية'],
                ['Multi Mode', 'ألياف متعددة'],
            ],

            'DC to AC Inverters' => [
                ['Pure Sine Wave', 'موجة جيبية نقية'],
                ['Modified Sine Wave', 'موجة معدلة'],
            ],
            'Voltage Stabilizers' => [
                ['Servo Motor', 'مثبت سيرفو'],
                ['Relay Type', 'مثبت ريليه'],
            ],
            'Power Supplies' => [
                ['SMPS', 'باور سبلاي SMPS'],
                ['UPS', 'مانع انقطاع UPS'],
            ],
            'Charge Controllers' => [
                ['MPPT', 'منظم MPPT'],
                ['PWM', 'منظم PWM'],
            ],
        ];

        $subCategories = Subcategory::all();

        foreach ($subCategories as $sub) {

            $rawTitle = $sub->getAttributes()['title'] ?? '';
            $titleEn = '';

            if (is_string($rawTitle) && str_contains($rawTitle, '{')) {
                $decoded = json_decode($rawTitle, true);
                $titleEn = $decoded['en'] ?? '';
            } else {
                $titleEn = $sub->title;
            }

            $titleEn = trim($titleEn);

            if (!empty($titleEn) && isset($childrenMap[$titleEn])) {

                foreach ($childrenMap[$titleEn] as $child) {
                    $childEn = $child[0];
                    $childAr = $child[1];

                    ChildCategory::create([
                        'subcategory_id' => $sub->id,
                        'title' => ['en' => $childEn, 'ar' => $childAr],
                        'description' => ['en' => $childEn, 'ar' => $childAr],
                        'image' => null,
                        'is_active' => 1,
                    ]);
                }

                $this->command->info("Inserted children for: $titleEn");
            }
        }
    }
}
