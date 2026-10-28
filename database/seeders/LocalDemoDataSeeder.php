<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Models\AdMerchantContent;
use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Models\Offer;
use Domain\AppBase\Models\AdCategoryContent;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * LocalDemoDataSeeder
 * -------------------
 * Local-only additive demo dataset for inspecting the store at
 * http://127.0.0.1:8000/ with a fully populated home page.
 *
 * EVERY write uses firstOrCreate keyed on a natural unique key, so
 * re-running never duplicates, never updates and never deletes any
 * existing row. No existing data, model, route or view is touched.
 *
 * Run with:
 *   php artisan db:seed --class=LocalDemoDataSeeder
 */
class LocalDemoDataSeeder extends Seeder
{
    /** @var array<string,string> relative storage path => source file under public/assets/ecommerce/images */
    private array $imageMap = [
        'upload/demo/hero/hero-1.jpg'     => 'ecommerce/slider/1.jpg',
        'upload/demo/hero/hero-2.jpg'     => 'ecommerce/slider/2.jpg',
        'upload/demo/hero/hero-3.webp'    => 'slider/4.webp',
        'upload/demo/banners/promo-1.png' => 'ecommerce/banner-1.png',
        'upload/demo/banners/promo-2.png' => 'ecommerce/banner-2.png',
        'upload/demo/banners/wide.jpg'    => 'Long banner.jpg',
        'upload/demo/banners/side-1.jpg'  => 'side-banner.jpg',
        'upload/demo/banners/side-2.jpg'  => 'side-a-banner-1.jpg',
        'upload/demo/news/news-1.jpg'     => 'ecommerce/Blog/1.jpg',
        'upload/demo/news/news-2.jpg'     => 'ecommerce/Blog/2.jpg',
        'upload/demo/news/news-3.jpg'     => 'ecommerce/Blog/3.jpg',
        'upload/demo/brands/brand-1.png'  => 'ecommerce/brand/1.png',
        'upload/demo/brands/brand-2.png'  => 'ecommerce/brand/2.png',
        'upload/demo/brands/brand-3.png'  => 'ecommerce/brand/3.png',
        'upload/demo/brands/brand-4.png'  => 'ecommerce/brand/4.png',
        'upload/demo/brands/brand-5.png'  => 'ecommerce/brand/5.png',
        'upload/demo/products/p1.jpg'     => 'pro/1.jpg',
        'upload/demo/products/p2.jpg'     => 'pro/2.jpg',
        'upload/demo/products/p3.jpg'     => 'pro/3.jpg',
        'upload/demo/products/p4.jpg'     => 'pro/9.jpg',
        'upload/demo/products/p5.jpg'     => 'pro/11.jpg',
        'upload/demo/products/p6.jpg'     => 'pro/20.jpg',
        'upload/demo/products/p7.jpg'     => 'pro/27.jpg',
        'upload/demo/products/p8.jpg'     => 'pro/b1.jpg',
        'upload/demo/products/p9.jpg'     => 'pro/b2.jpg',
        'upload/demo/products/p10.jpg'    => 'pro/b3.jpg',
        'upload/demo/products/p11.jpg'    => 'pro/b4.jpg',
        'upload/demo/products/p12.jpg'    => 'pro/c1.jpg',
        'upload/demo/products/p13.jpg'    => 'ecommerce/Product/1.jpg',
        'upload/demo/products/p14.jpg'    => 'ecommerce/Product/2.jpg',
        'upload/demo/products/p15.jpg'    => 'ecommerce/Product/3.jpg',
        'upload/demo/products/p16.jpg'    => 'ecommerce/Product/4.jpg',
        'upload/demo/products/p17.jpg'    => 'ecommerce/Product/5.jpg',
        'upload/demo/products/p18.jpg'    => 'solar/products/1.jpg',
        'upload/demo/products/p19.jpg'    => 'solar/products/2.jpg',
        'upload/demo/products/p20.jpg'    => 'solar/products/3.jpg',
        'upload/demo/stores/store-1.jpg'  => 'pro/b1.jpg',
        'upload/demo/stores/store-2.jpg'  => 'pro/b2.jpg',
        'upload/demo/stores/store-3.jpg'  => 'pro/b3.jpg',
        'upload/demo/stores/store-4.jpg'  => 'pro/b4.jpg',
    ];

    public function run(): void
    {
        $this->publishDemoImages();

        $page = $this->ensureHomePage();
        $placements = $this->ensurePlacements($page);

        $merchants = $this->ensureMerchants();
        $this->ensureBranches($merchants);
        $products = $this->ensureProducts($merchants);

        $this->ensureBanners($placements);
        $this->ensureCategoryContents($placements);
        $this->ensureProductContents($placements, $products);
        $this->ensureMerchantContents($placements, $merchants);
        $this->ensureOffers();

        $this->command->info('Local demo data seeded (additive only).');
    }

    /** Copy bundled asset images into public/storage and file-disk so /storage/* URLs resolve. */
    private function publishDemoImages(): void
    {
        $sources = public_path('assets/ecommerce/images');
        $targets = array_filter([
            public_path('storage'),
            realpath(base_path('../file-disk')) ?: null,
        ]);

        $copied = 0;
        $skipped = 0;
        $failed = 0;

        $pairs = [];
        foreach ($this->imageMap as $destRelative => $sourceRelative) {
            $pairs[$destRelative] = $sources . '/' . $sourceRelative;
        }
        foreach ($this->repairTargets() as $destRelative) {
            $pairs[$destRelative] = $sources . '/pro/1.jpg';
        }

        foreach ($pairs as $destRelative => $source) {
            if (!is_file($source)) {
                $this->command->warn("Missing source image: {$source}");
                continue;
            }

            foreach ($targets as $root) {
                $destination = $root . '/' . $destRelative;

                if (is_file($destination)) {
                    $skipped++;
                    continue;
                }

                try {
                    File::ensureDirectoryExists(dirname($destination));
                    if (copy($source, $destination)) {
                        $copied++;
                    } else {
                        $failed++;
                    }
                } catch (\Throwable $e) {
                    $failed++;
                    $this->command->warn("Could not copy to {$destination}: {$e->getMessage()}");
                }
            }
        }

        $this->command->info("Demo images: {$copied} copied, {$skipped} already present, {$failed} failed.");
    }

    /** Filesystem paths already referenced by pre-existing DB rows. */
    private function repairTargets(): array
    {
        $targets = [
            // existing item id=1 image
            'upload/products/aKMpsrAs04iE7eOeeRr81aWIIPvyZydZwUKKcqa0.webp',
        ];

        $strip = [url('/') . '/storage/', config('filesystems.disks.fileDisk.url', '')];

        foreach (Category::query()->pluck('image') as $image) {
            if (!$image) {
                continue;
            }
            $relative = str_replace($strip, '', $image);
            if ($relative !== '' && !str_contains($relative, '://')) {
                $targets[] = $relative;
            }
        }

        return $targets;
    }

    private function ensureHomePage(): AdPage
    {
        return AdPage::firstOrCreate(
            ['key' => 'home'],
            [
                'name' => ['ar' => 'الصفحة الرئيسية للمتجر', 'en' => 'Ecommerce Home'],
                'description' => ['ar' => 'الصفحة الرئيسية للمتجر الإلكتروني', 'en' => 'Main landing page of the ecommerce store'],
                'status' => 'active',
                'sort_order' => 1,
            ]
        );
    }

    private function ensurePlacements(AdPage $page): array
    {
        $definitions = [
            'home_hero_slider' => ['سلايدر الواجهة الرئيسية', 'Main Hero Slider', 'header', 'slider', 1],
            'home_featured_grid' => ['شبكة البانرات الترويجية', 'Promo Banners Grid', 'content_top', 'grid_banners', 2],
            'home_categories_carousel' => ['سلايدر الأقسام', 'Categories Carousel', 'content_top', 'carousel', 3],
            'home_product_tabs' => ['مجموعات المنتجات', 'Product Collections', 'content_middle', 'product_tabs', 5],
            'home_exclusive_slider' => ['عروض حصرية', 'Exclusive Deals', 'content_bottom', 'product_slider', 6],
            'home_new_arrivals' => ['وصل حديثاً', 'New Arrivals', 'content_middle', 'product_slider', 7],
            'large_banner' => ['بانر ترويجي وسطي', 'Mid-Page Promo', 'content_middle', 'static_image', 8],
            'home_merchant_slider' => ['سلايدر المتاجر', 'Merchant Slider', 'content_bottom', 'merchant_slider', 9],
            'recent_news' => ['أخبار حديثة', 'Recent News', 'content_bottom', 'slider', 10],
            'client_brands' => ['علامات تجارية', 'Our Brands', 'content_bottom', 'slider', 11],
            'sidebar_banner' => ['البانر الجانبي الأول', 'Sidebar Banner 1', 'sidebar', 'static_image', 12],
            'sidebar_banner_2' => ['البانر الجانبي الثاني', 'Sidebar Banner 2', 'sidebar', 'static_image', 13],
            'sidebar_new_products' => ['منتجات جديدة (جانبي)', 'New Products (Sidebar)', 'sidebar', 'product_sidebar', 14],
            'sidebar_recently_viewed' => ['شوهد مؤخراً (جانبي)', 'Recently Viewed (Sidebar)', 'sidebar', 'product_sidebar', 15],
            'sidebar_services' => ['خدمات القائمة الجانبية', 'Sidebar Services', 'sidebar', 'grid_banners', 16],
            'footer_main_section' => ['فوتر الموقع', 'Site Footer', 'footer', 'footer', 17],
        ];

        $placements = [];

        foreach ($definitions as $key => [$ar, $en, $location, $type, $sort]) {
            $placements[$key] = AdPlacement::firstOrCreate(
                ['key' => $key],
                [
                    'page_id' => $page->id,
                    'name' => ['ar' => $ar, 'en' => $en],
                    'location' => $location,
                    'type' => $type,
                    'status' => 'active',
                    'sort_order' => $sort,
                    'settings' => $key === 'home_product_tabs'
                        ? [
                            'groups' => [
                                ['key' => 'featured', 'title' => ['ar' => 'منتجات مميزة', 'en' => 'Featured Products'], 'enabled' => true, 'query' => ['type' => 'manual']],
                                ['key' => 'latest', 'title' => ['ar' => 'وصل حديثاً', 'en' => 'New Arrivals'], 'enabled' => true, 'query' => ['type' => 'latest', 'limit' => 12]],
                                ['key' => 'best_selling', 'title' => ['ar' => 'الأكثر مبيعاً', 'en' => 'Best Selling'], 'enabled' => true, 'query' => ['type' => 'best_selling', 'limit' => 12]],
                            ],
                            'display' => ['show_tabs' => true, 'default_tab' => 'featured'],
                            'slider_config' => ['max_items' => 12],
                        ]
                        : [
                            'slider_config' => ['max_items' => 12, 'loop' => true, 'autoplay' => true],
                        ],
                ]
            );
        }

        return $placements;
    }

    /** @return array<int,Merchant> */
    private function ensureMerchants(): array
    {
        $definitions = [
            [
                'email' => 'demo.solar@demo.local',
                'company_name' => ['ar' => 'متجر الطاقة الشمسية', 'en' => 'Solar Energy Store'],
                'user_name' => 'متجر الطاقة الشمسية',
                'description' => ['ar' => 'متجر متخصص في الألواح الشمسية والبطاريات والمحولات.', 'en' => 'Specialised store for solar panels, batteries and inverters.'],
            ],
            [
                'email' => 'demo.wind@demo.local',
                'company_name' => ['ar' => 'متجر الرياح والمعدات الكهربائية', 'en' => 'Wind & Electrical Equipment Store'],
                'user_name' => 'متجر الرياح والمعدات الكهربائية',
                'description' => ['ar' => 'توربينات الرياح والمولدات ومضخات المياه.', 'en' => 'Wind turbines, generators and water pumps.'],
            ],
            [
                'email' => 'demo.safety@demo.local',
                'company_name' => ['ar' => 'متجر السلامة ومواد البناء', 'en' => 'Safety & Building Materials Store'],
                'user_name' => 'متجر السلامة ومواد البناء',
                'description' => ['ar' => 'معدات السلامة والمواد العازلة ومواد البناء.', 'en' => 'Safety gear, insulation and building materials.'],
            ],
        ];

        $merchants = [];

        foreach ($definitions as $definition) {
            $merchants[] = Merchant::firstOrCreate(
                ['email' => $definition['email']],
                [
                    'company_name' => $definition['company_name'],
                    'user_name' => $definition['user_name'],
                    'name' => $definition['company_name']['ar'],
                    'description' => $definition['description'],
                    'phone' => '5000000' . count($merchants),
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'country_id' => 1,
                    'region_id' => 1,
                    'city_id' => 1,
                ]
            );
        }

        // include the pre-existing store so the slider and /merchants look complete
        $existing = Merchant::find(9);
        if ($existing) {
            $merchants[] = $existing;
        }

        return $merchants;
    }

    private function ensureBranches(array $merchants): void
    {
        foreach ($merchants as $index => $merchant) {
            MerchantBranch::firstOrCreate(
                ['merchant_id' => $merchant->id],
                [
                    'title' => ['ar' => 'الفرع الرئيسي', 'en' => 'Main Branch'],
                    'description' => ['ar' => 'الفرع الرئيسي للمتجر', 'en' => 'Main store branch'],
                    'type' => 'fixed',
                    'isActive' => 'active',
                    'status' => 1,
                    'region_id' => 1,
                    'city_id' => 1,
                    'package_id' => 0,
                    'latitude' => '24.7136',
                    'longitude' => '46.6753',
                    'address_name' => ['ar' => 'الرياض', 'en' => 'Riyadh'],
                    'cover_image' => 'upload/demo/stores/store-' . (($index % 4) + 1) . '.jpg',
                ]
            );
        }
    }

    /** @return array<int,Item> */
    private function ensureProducts(array $merchants): array
    {
        $byEmail = [];
        foreach ($merchants as $merchant) {
            $byEmail[$merchant->email ?? ('id-' . $merchant->id)] = $merchant;
        }

        $catalogues = [
            'demo.solar@demo.local' => [
                ['ar' => 'لوحة شمسية 450 واط', 'en' => '450W Solar Panel', 'price' => 320.00, 'sale' => 256.00, 'cat' => 1],
                ['ar' => 'منظم شحن شمسي 20 أمبير', 'en' => '20A Solar Charge Controller', 'price' => 210.00, 'cat' => 1],
                ['ar' => 'بطارية ليثيوم 12 فولت 100 أمبير', 'en' => '12V 100Ah Lithium Battery', 'price' => 540.00, 'sale' => 432.00, 'cat' => 8],
                ['ar' => 'كابل شمسي نحاسي 6 مم (10 م)', 'en' => '6mm Solar Copper Cable (10m)', 'price' => 95.00, 'cat' => 9],
                ['ar' => 'محول DC/AC 3000 واط', 'en' => '3000W DC/AC Inverter', 'price' => 780.00, 'cat' => 10],
                ['ar' => 'حامل ألواح ألمنيوم قابل للإمالة', 'en' => 'Adjustable Aluminium Panel Mount', 'price' => 150.00, 'cat' => 1],
                ['ar' => 'بطارية عميقة 150 أمبير', 'en' => '150Ah Deep Cycle Battery', 'price' => 890.00, 'sale' => 712.00, 'cat' => 8],
                ['ar' => 'محول موجة نقية 5000 واط', 'en' => '5000W Pure Sine Inverter', 'price' => 1250.00, 'cat' => 10],
            ],
            'demo.wind@demo.local' => [
                ['ar' => 'عنفة رياح منزلية 1 كيلوواط', 'en' => '1kW Home Wind Turbine', 'price' => 640.00, 'sale' => 512.00, 'cat' => 2],
                ['ar' => 'مضخة مياه بالطاقة الشمسية 300 واط', 'en' => '300W Solar Water Pump', 'price' => 420.00, 'cat' => 5],
                ['ar' => 'مولد ديزل 2 كيلوواط', 'en' => '2kW Diesel Generator', 'price' => 1450.00, 'cat' => 6],
                ['ar' => 'خزان مياه 500 لتر', 'en' => '500L Water Tank', 'price' => 180.00, 'cat' => 5],
                ['ar' => 'لوحة توزيع كهربائية 12 دائرة', 'en' => '12-Way Electrical Distribution Board', 'price' => 260.00, 'cat' => 6],
                ['ar' => 'عداد ذكي كهربائي 3 طور', 'en' => '3-Phase Smart Electricity Meter', 'price' => 340.00, 'sale' => 272.00, 'cat' => 6],
                ['ar' => 'طرمبة غاطسة 1.5 كيلوواط', 'en' => '1.5kW Submersible Pump', 'price' => 520.00, 'cat' => 5],
            ],
            'demo.safety@demo.local' => [
                ['ar' => 'طقم سلامة ورشة كامل', 'en' => 'Complete Workshop Safety Kit', 'price' => 320.00, 'sale' => 256.00, 'cat' => 4],
                ['ar' => 'قفازات مقاومة للحرارة', 'en' => 'Heat Resistant Gloves', 'price' => 75.00, 'cat' => 4],
                ['ar' => 'نظارات واقية من الغبار', 'en' => 'Anti-Dust Safety Goggles', 'price' => 45.00, 'cat' => 4],
                ['ar' => 'كاشف تسرب غاز محمول', 'en' => 'Portable Gas Leak Detector', 'price' => 290.00, 'cat' => 3],
                ['ar' => 'ألواح جبس مقاومة للرطوبة', 'en' => 'Moisture-Resistant Gypsum Boards', 'price' => 165.00, 'cat' => 7],
                ['ar' => 'عازل حراري للأسقف', 'en' => 'Ceiling Thermal Insulation', 'price' => 220.00, 'cat' => 7],
                ['ar' => 'أسمنت مقاوم للماء 25 كجم', 'en' => 'Waterproof Cement 25kg', 'price' => 58.00, 'sale' => 46.00, 'cat' => 7],
            ],
            'id-9' => [
                ['ar' => 'لوحة شمسية 250 واط مطوية', 'en' => '250W Foldable Solar Panel', 'price' => 275.00, 'cat' => 1],
                ['ar' => 'بطارية شمسية محمولة 500 واط', 'en' => '500W Portable Solar Battery', 'price' => 460.00, 'sale' => 368.00, 'cat' => 8],
                ['ar' => 'مجموعة إنارة طاقة شمسية 8 قطع', 'en' => '8-Piece Solar Lighting Kit', 'price' => 190.00, 'cat' => 6],
                ['ar' => 'محول جهد 12 فولت إلى 220 فولت', 'en' => '12V to 220V Voltage Converter', 'price' => 120.00, 'cat' => 10],
            ],
        ];

        $products = [];
        $imageIndex = 0;

        foreach ($merchants as $merchant) {
            $emailKey = $merchant->email ?? ('id-' . $merchant->id);
            $entries = $catalogues[$emailKey] ?? [];

            foreach ($entries as $entry) {
                $imageIndex++;
                $product = Item::firstOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'title->en' => $entry['en'],
                    ],
                    [
                        'title' => ['ar' => $entry['ar'], 'en' => $entry['en']],
                        'description' => [
                            'ar' => $entry['ar'] . ' — منتج عالي الجودة بضمان شامل وشحن سريع.',
                            'en' => $entry['en'] . ' — high quality product with full warranty and fast shipping.',
                        ],
                        'status' => 'active',
                        'default_price' => $entry['price'],
                        'sale_price' => $entry['sale'] ?? null,
                        'is_on_sale' => isset($entry['sale']),
                        'sale_expires_at' => isset($entry['sale']) ? now()->addMonths(3) : null,
                        'stock' => 10 + ($imageIndex * 3) % 40,
                        'calories' => 0,
                        'preparing_time' => 0,
                        'main_category_id' => $entry['cat'],
                        'image' => 'upload/demo/products/p' . ((($imageIndex - 1) % 20) + 1) . '.jpg',
                    ]
                );

                $products[] = $product;
            }
        }

        return $products;
    }

    private function ensureBanners(array $placements): void
    {
        $this->seedBannerGroup($placements['home_hero_slider'], [
            [
                'name' => 'Demo Hero Slide 1',
                'title' => ['ar' => 'طاقة نظيفة لمستقبل أفضل', 'en' => 'Clean Energy for a Better Future'],
                'subtitle' => ['ar' => 'أفضل معدات الطاقة المتجددة بأسعار منافسة', 'en' => 'Top renewable energy equipment at competitive prices'],
                'image' => 'upload/demo/hero/hero-1.jpg',
                'action' => ['type' => 'url', 'url' => route('categories.index')],
            ],
            [
                'name' => 'Demo Hero Slide 2',
                'title' => ['ar' => 'عروض حصرية على الألواح الشمسية', 'en' => 'Exclusive Solar Panel Deals'],
                'subtitle' => ['ar' => 'خصومات تصل إلى 20% على باقات الألواح', 'en' => 'Up to 20% off solar panel bundles'],
                'image' => 'upload/demo/hero/hero-2.jpg',
                'action' => ['type' => 'url', 'url' => route('offers.index')],
            ],
            [
                'name' => 'Demo Hero Slide 3',
                'title' => ['ar' => 'كل ما تحتاجه لمشروعك الطاقي', 'en' => 'Everything Your Energy Project Needs'],
                'subtitle' => ['ar' => 'بطاريات ومحولات وكابلات من علامات عالمية', 'en' => 'Batteries, inverters and cables from global brands'],
                'image' => 'upload/demo/hero/hero-3.webp',
                'action' => ['type' => 'url', 'url' => route('merchants.index')],
            ],
        ]);

        $this->seedBannerGroup($placements['home_featured_grid'], [
            [
                'name' => 'Demo Promo Banner 1',
                'title' => ['ar' => 'خصم 20% على الألواح الشمسية', 'en' => '20% Off Solar Panels'],
                'subtitle' => ['ar' => 'لفترة محدودة', 'en' => 'Limited time only'],
                'image' => 'upload/demo/banners/promo-1.png',
                'action' => ['type' => 'url', 'url' => route('offers.index')],
            ],
            [
                'name' => 'Demo Promo Banner 2',
                'title' => ['ar' => 'بطاريات عالية الكفاءة', 'en' => 'High-Efficiency Batteries'],
                'subtitle' => ['ar' => 'عمر أطول وأداء أ穩定', 'en' => 'Longer life, stable performance'],
                'image' => 'upload/demo/banners/promo-2.png',
                'action' => ['type' => 'url', 'url' => route('categories.index')],
            ],
            [
                'name' => 'Demo Promo Banner 3',
                'title' => ['ar' => 'معدات السلامة للمحترفين', 'en' => 'Safety Gear for Professionals'],
                'subtitle' => ['ar' => 'حماية موثوقة لفريقك', 'en' => 'Reliable protection for your team'],
                'image' => 'upload/demo/banners/side-1.jpg',
                'action' => ['type' => 'url', 'url' => route('categories.index')],
            ],
        ]);

        $this->seedBannerGroup($placements['large_banner'], [
            [
                'name' => 'Demo Wide Banner',
                'title' => ['ar' => 'شحن مجاني للطلبات فوق 200 ريال', 'en' => 'Free Shipping on Orders Over 200 SAR'],
                'subtitle' => ['ar' => 'توصيل سريع لجميع مناطق المملكة', 'en' => 'Fast delivery across the Kingdom'],
                'image' => 'upload/demo/banners/wide.jpg',
                'action' => ['type' => 'url', 'url' => route('categories.index')],
            ],
        ]);

        $this->seedBannerGroup($placements['sidebar_banner'], [
            [
                'name' => 'Demo Sidebar Banner 1',
                'title' => ['ar' => 'الأكثر مبيعاً', 'en' => 'Best Sellers'],
                'subtitle' => ['ar' => 'اكتشف منتجاتنا الأعلى تقييماً', 'en' => 'Discover our top-rated products'],
                'image' => 'upload/demo/banners/side-1.jpg',
                'action' => ['type' => 'url', 'url' => route('categories.index')],
            ],
        ]);

        $this->seedBannerGroup($placements['sidebar_banner_2'], [
            [
                'name' => 'Demo Sidebar Banner 2',
                'title' => ['ar' => 'وصل حديثاً', 'en' => 'New Arrivals'],
                'subtitle' => ['ar' => 'أحدث المنتجات في المتجر', 'en' => 'The latest products in store'],
                'image' => 'upload/demo/banners/side-2.jpg',
                'action' => ['type' => 'url', 'url' => route('categories.index')],
            ],
        ]);

        $this->seedBannerGroup($placements['recent_news'], [
            [
                'name' => 'Demo News 1',
                'title' => ['ar' => 'دليلك لاختيار اللوحة الشمسية المناسبة', 'en' => 'Guide to Choosing the Right Solar Panel'],
                'subtitle' => ['ar' => 'نصائح عملية قبل الشراء', 'en' => 'Practical tips before you buy'],
                'image' => 'upload/demo/news/news-1.jpg',
                'action' => ['type' => 'url', 'url' => '#'],
            ],
            [
                'name' => 'Demo News 2',
                'title' => ['ar' => 'مستقبل طاقة الرياح في المنطقة', 'en' => 'The Future of Wind Energy in the Region'],
                'subtitle' => ['ar' => 'محطات ومشاريع رائدة', 'en' => 'Leading plants and projects'],
                'image' => 'upload/demo/news/news-2.jpg',
                'action' => ['type' => 'url', 'url' => '#'],
            ],
            [
                'name' => 'Demo News 3',
                'title' => ['ar' => 'كيف تخزن طاقتك بكفاءة', 'en' => 'How to Store Your Energy Efficiently'],
                'subtitle' => ['ar' => 'بطاريات ومحولات بمزايا ذكية', 'en' => 'Smart batteries and inverters'],
                'image' => 'upload/demo/news/news-3.jpg',
                'action' => ['type' => 'url', 'url' => '#'],
            ],
        ]);

        $brands = ['SMA', 'Growatt', 'Victron', 'Jinko', 'Huawei'];
        $brandBanners = [];
        foreach ($brands as $index => $brand) {
            $brandBanners[] = [
                'name' => 'Demo Brand ' . $brand,
                'title' => ['ar' => $brand, 'en' => $brand],
                'subtitle' => ['ar' => '', 'en' => ''],
                'image' => 'upload/demo/brands/brand-' . ($index + 1) . '.png',
                'action' => ['type' => 'none'],
            ];
        }
        $this->seedBannerGroup($placements['client_brands'], $brandBanners);

        $this->seedBannerGroup($placements['sidebar_services'], [
            [
                'name' => 'Demo Sidebar Service 1',
                'title' => ['ar' => 'تركيب احترافي', 'en' => 'Professional Installation'],
                'subtitle' => ['ar' => 'فنيون معتمدون', 'en' => 'Certified technicians'],
                'image' => 'upload/demo/banners/side-2.jpg',
                'action' => ['type' => 'none'],
            ],
            [
                'name' => 'Demo Sidebar Service 2',
                'title' => ['ar' => 'دعم فني 24/7', 'en' => '24/7 Support'],
                'subtitle' => ['ar' => 'نحن هنا لخدمتكم', 'en' => 'We are here for you'],
                'image' => 'upload/demo/banners/side-1.jpg',
                'action' => ['type' => 'none'],
            ],
        ]);
    }

    private function seedBannerGroup(AdPlacement $placement, array $banners): void
    {
        foreach ($banners as $index => $banner) {
            AdBanner::firstOrCreate(
                ['placement_id' => $placement->id, 'name' => $banner['name']],
                [
                    'title' => $banner['title'],
                    'subtitle' => $banner['subtitle'],
                    'content' => [
                        'images' => [
                            'desktop' => $banner['image'],
                            'mobile' => $banner['image'],
                        ],
                        'alt_text' => $banner['title'],
                    ],
                    'click_action' => $banner['action'],
                    'status' => 'active',
                    'priority' => $index + 1,
                    'start_date' => now()->subDay(),
                    'end_date' => now()->addYear(),
                ]
            );
        }
    }

    private function ensureCategoryContents(array $placements): void
    {
        $placement = $placements['home_categories_carousel'];

        Category::query()->orderBy('id')->get()->each(function (Category $category, int $index) use ($placement) {
            AdCategoryContent::firstOrCreate(
                ['placement_id' => $placement->id, 'category_id' => $category->id],
                [
                    'priority' => $index + 1,
                    'status' => 'active',
                ]
            );
        });
    }

    private function ensureProductContents(array $placements, array $products): void
    {
        if (empty($products)) {
            return;
        }

        $assignments = [
            'home_product_tabs' => [
                ['group' => 'featured', 'items' => array_slice($products, 0, 8)],
                ['group' => 'latest', 'items' => array_slice($products, 8, 6)],
                ['group' => 'best_selling', 'items' => array_slice($products, 14, 6)],
            ],
            'home_exclusive_slider' => [
                ['group' => null, 'items' => array_values(array_filter($products, fn ($p) => $p->is_on_sale))],
            ],
            'home_new_arrivals' => [
                ['group' => null, 'items' => array_slice($products, -6)],
            ],
            'sidebar_new_products' => [
                ['group' => null, 'items' => array_slice($products, 0, 6)],
            ],
            'sidebar_recently_viewed' => [
                ['group' => null, 'items' => array_slice($products, 6, 6)],
            ],
        ];

        foreach ($assignments as $placementKey => $groups) {
            $placement = $placements[$placementKey];

            foreach ($groups as $group) {
                foreach ($group['items'] as $index => $product) {
                    AdProductContent::firstOrCreate(
                        [
                            'placement_id' => $placement->id,
                            'product_id' => $product->id,
                            'group_key' => $group['group'],
                        ],
                        [
                            'status' => 'active',
                            'priority' => $index + 1,
                            'start_date' => now()->subDay(),
                            'end_date' => now()->addYear(),
                        ]
                    );
                }
            }
        }
    }

    private function ensureMerchantContents(array $placements, array $merchants): void
    {
        $placement = $placements['home_merchant_slider'];

        foreach ($merchants as $index => $merchant) {
            AdMerchantContent::firstOrCreate(
                ['placement_id' => $placement->id, 'merchant_id' => $merchant->id],
                [
                    'status' => 'active',
                    'priority' => $index + 1,
                    'start_date' => now()->subDay(),
                    'end_date' => now()->addYear(),
                ]
            );
        }
    }

    private function ensureOffers(): void
    {
        $offers = [
            [
                'name' => 'TAQA20',
                'discount_value' => 20,
                'min_order_value' => 100,
                'note' => 'خصم 20% على الطلبات فوق 100 ريال',
            ],
            [
                'name' => 'SOLAR15',
                'discount_value' => 15,
                'min_order_value' => 200,
                'note' => 'خصم 15% على معدات الطاقة الشمسية',
            ],
            [
                'name' => 'WIND10',
                'discount_value' => 10,
                'min_order_value' => 150,
                'note' => 'خصم 10% على معدات طاقة الرياح',
            ],
            [
                'name' => 'SAFETY25',
                'discount_value' => 25,
                'min_order_value' => 50,
                'note' => 'خصم 25% على معدات السلامة',
            ],
        ];

        foreach ($offers as $offer) {
            Offer::firstOrCreate(
                ['name' => $offer['name']],
                [
                    'type' => 'percent',
                    'type_discount' => 'order',
                    'discount_value' => $offer['discount_value'],
                    'min_order_value' => $offer['min_order_value'],
                    'max_discount' => 200,
                    'usages_left' => 100,
                    'limit_per_user' => 1,
                    'period' => 1,
                    'note' => $offer['note'],
                    'status' => 'active',
                    'is_custom' => 0,
                    'expired_at' => now()->addMonths(6),
                ]
            );
        }
    }
}
