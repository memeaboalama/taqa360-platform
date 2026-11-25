<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\AppBase\Models\AdMerchantContent;
use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\CustomerTestimonial;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdMerchantContentRepository;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\FetchCategoryAction;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Enum\ActiveEnum;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Item;
use Domain\AppBase\Models\AdCategoryContent;
use Domain\AppBase\Repositories\AdCategoryContentRepository;
use Domain\MarketPlace\Models\Catalog\Category;
use App\Support\Enum\BannerStatusEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FetchHomeDataAction
{
    public function __construct(
        private AdPlacementRepository $adPlacementRepository,
        private AdBannerRepository $adBannerRepository,
        private ItemRepository $itemRepository,
        private FetchCategoryAction $fetchCategoryAction
    ) {}

    public function handle(): array
    {
        if (app()->isLocal()) {
            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');
            Cache::forget('home_page_data_' . app()->getLocale());
            Cache::forget('home_page_data_storefront_v7_ar');
            Cache::forget('home_page_data_storefront_v7_en');
            Cache::forget('home_page_data_storefront_v7_' . app()->getLocale());
        }

        $data = Cache::remember('home_page_data_storefront_v7_' . app()->getLocale(), 1800, function() {
            $page = AdPage::where('key', 'home')->first();

            if (!$page) {
                $data = $this->getEmptyData();
                $data['footerData'] = $this->getFooterData(null, 'footer_main_section');

                return $this->fillWithFallbacks($data);
            }

            $placements = $this->adPlacementRepository->builder(order: 'asc', orderCol: 'sort_order')
                ->where('page_id', $page->id)
                ->where('status', 'active')
                ->with(['activeBanners' => function($query) {
                    $query->orderBy('priority', 'asc');
                }])
                ->with(['activeProductContents' => function($query) {
                    $query->with(['product' => fn ($productQuery) => $productQuery->withReviewSummary()])
                        ->orderBy('priority', 'asc');
                }])
                ->get();

            $data = [];

            foreach ($placements as $placement) {
                $isProductPlacement = in_array($placement->type->value, ['product_tabs', 'product_slider', 'product_sidebar']);

                if ($isProductPlacement) {
                    $products = $placement->activeProductContents->pluck('product')->filter();
                } else {
                    $products = collect();
                }

                switch ($placement->key) {
                    case 'home_product_tabs':
                        $data['tabGroups'] = $this->organizeTabGroupsFromContents($placement);
                        $data['featuredProducts'] = $this->getGroupProductsFromContents($placement, 'featured');
                        $data['newProducts'] = $this->getGroupProductsFromContents($placement, 'latest');
                        $data['bestSellers'] = $this->getGroupProductsFromContents($placement, 'best_selling');
                        break;

                    case 'home_exclusive_slider':
                    case 'home_exclusive_collection':
                        $data['exclusiveProducts'] = $products->values();
                        break;

                    case 'sidebar_new_products':
                        $data['newProductsSidebar'] = $products->take(9);
                        break;

                    case 'sidebar_recently_viewed':
                        $data['recentlyViewed'] = $products->take(9);
                        break;

                    case 'sidebar_services':
                        $data['services'] = $placement->activeBanners;
                        break;

                    case 'home_categories_carousel':
                        $categoryRepo = new AdCategoryContentRepository(new AdCategoryContent());
                        $contents = $categoryRepo->builder(order:'asc', orderCol: 'priority')
                            ->where('placement_id', $placement->id)
                            ->where('status', BannerStatusEnum::ACTIVE->value)
                            ->with('category')
                            ->get();

                        if ($contents->isNotEmpty()) {
                            $data['categories'] = $contents->map(fn($content) => $content->category)->filter();
                        }
                        break;

                    case 'home_new_arrivals':
                        $data['sectionNewArrivals'] = $products->take(6);
                        break;
                }
            }

            $mainSliderPlacement = $this->getPlacement($page->id, 'home_hero_slider');
            $data['mainSlider'] = $this->getBannersFromPlacement($mainSliderPlacement);
            $data['mainSliderSettings'] = $mainSliderPlacement ? ($mainSliderPlacement->settings['slider_config'] ?? []) : [];
            $backgroundPath = $mainSliderPlacement?->settings['background_image'] ?? null;
            $data['mainSliderBackground'] = $backgroundPath && Storage::disk('fileDisk')->exists($backgroundPath)
                ? Storage::disk('fileDisk')->url($backgroundPath)
                : null;
            $data['largeBanner'] = $this->getBanners($page->id, 'large_banner');
            $data['sidebarBanner'] = $this->getBanners($page->id, 'sidebar_banner');
            $data['sidebarBanner2'] = $this->getBanners($page->id, 'sidebar_banner_2');
            $data['productBanners'] = $this->getBanners($page->id, 'home_featured_grid');
            $data['recentNews'] = $this->getBanners($page->id, 'recent_news');
            $data['clientBrands'] = $this->getBanners($page->id, 'client_brands');
            $data['merchantSlider'] = $this->getMerchants($page->id, 'home_merchant_slider');
            $data['footerData'] = $this->getFooterData($page->id, 'footer_main_section');
            $data['testimonials'] = $this->getTestimonials();

            if (!isset($data['categories']) || (is_countable($data['categories']) && count($data['categories']) === 0)) {
                $data['categories'] = $this->fetchCategoryAction->handle();
            }

            return $this->fillWithFallbacks($data);
        });

        return $this->filterUnavailableCachedProducts($data);
    }

    public function getStoreFooterData(): ?array
    {
        $page = AdPage::where('key', 'home')->first();

        return $this->getFooterData($page?->id, 'footer_main_section');
    }

    public function getProductCollection(string $section): \Illuminate\Support\Collection
    {
        $sections = [
            'best-sellers' => [
                'placement_keys' => ['home_product_tabs'],
                'group_key' => 'best_selling',
                'fallback_order' => 'random',
            ],
            'featured-products' => [
                'placement_keys' => ['home_product_tabs'],
                'group_key' => 'featured',
                'fallback_order' => 'latest',
            ],
            'new-products' => [
                'placement_keys' => ['home_product_tabs'],
                'group_key' => 'latest',
                'fallback_order' => 'latest',
            ],
            'exclusive-products' => [
                'placement_keys' => ['home_exclusive_slider', 'home_exclusive_collection'],
                'group_key' => null,
                'fallback_order' => 'random',
            ],
        ];
        $definition = $sections[$section] ?? null;

        if (!$definition) {
            throw new \InvalidArgumentException('Unsupported home product section.');
        }

        $homePage = AdPage::query()->where('key', 'home')->first();
        if ($homePage) {
            $placements = AdPlacement::query()
                ->where('page_id', $homePage->id)
                ->where('status', 'active')
                ->whereIn('key', $definition['placement_keys'])
                ->with(['activeProductContents' => function ($query) use ($definition) {
                    if ($definition['group_key'] !== null) {
                        $query->where('group_key', $definition['group_key']);
                    }

                    $query->with(['product' => fn ($productQuery) => $productQuery->withReviewSummary()])
                        ->orderBy('priority')
                        ->orderBy('id');
                }])
                ->orderBy('sort_order')
                ->get();

            $products = $placements
                ->flatMap(fn (AdPlacement $placement) => $placement->activeProductContents->pluck('product'))
                ->filter()
                ->unique('id')
                ->values();

            if ($products->isNotEmpty()) {
                return $products;
            }
        }

        $query = $this->itemRepository
            ->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
            ->with('merchant');

        return ($definition['fallback_order'] === 'latest'
            ? $query->latest()
            : $query->inRandomOrder())
            ->get();
    }

    private function filterUnavailableCachedProducts(array $data): array
    {
        $productKeys = [
            'featuredProducts',
            'newProducts',
            'bestSellers',
            'exclusiveProducts',
            'newProductsSidebar',
            'recentlyViewed',
            'sectionNewArrivals',
            'suggestedProducts',
        ];
        $products = collect();

        foreach ($productKeys as $key) {
            if (($data[$key] ?? null) instanceof \Illuminate\Support\Collection) {
                $products = $products->merge($data[$key]);
            }
        }

        foreach ($data['tabGroups'] ?? [] as $groupProducts) {
            if ($groupProducts instanceof \Illuminate\Support\Collection) {
                $products = $products->merge($groupProducts);
            }
        }

        $productIds = $products->pluck('id')->filter()->unique()->values();
        if ($productIds->isEmpty()) {
            return $data;
        }

        $freshProductsById = Item::query()
            ->whereIn('id', $productIds)
            ->where('status', ActiveEnum::ACTIVE->value)
            ->availableForStorefront()
            ->withReviewSummary()
            ->get()
            ->keyBy('id');

        foreach ($productKeys as $key) {
            if (($data[$key] ?? null) instanceof \Illuminate\Support\Collection) {
                $data[$key] = $data[$key]
                    ->map(fn ($product) => $freshProductsById->get($product->id))
                    ->filter()
                    ->values();
            }
        }

        foreach ($data['tabGroups'] ?? [] as $key => $groupProducts) {
            if ($groupProducts instanceof \Illuminate\Support\Collection) {
                $data['tabGroups'][$key] = $groupProducts
                    ->map(fn ($product) => $freshProductsById->get($product->id))
                    ->filter()
                    ->values();
            }
        }

        return $data;
    }

    private function getGroupProductsFromContents($placement, $groupKey, ?int $limit = null)
    {
        $products = $placement->activeProductContents
            ->filter(fn($content) => $content->group_key === $groupKey)
            ->pluck('product')
            ->filter()
            ->values();

        return $limit === null ? $products : $products->take($limit)->values();
    }

    private function organizeTabGroupsFromContents($placement)
    {
        $groups = [];
        foreach ($placement->settings['groups'] ?? [] as $group) {
            $groups[$group['key']] = $this->getGroupProductsFromContents($placement, $group['key'], 12);
        }
        return $groups;
    }

    private function getPlacement($pageId, $key)
    {
        return $this->adPlacementRepository->builder()
            ->where('page_id', $pageId)
            ->where('key', $key)
            ->where('status', 'active')
            ->first();
    }

    private function getBannersFromPlacement($placement)
    {
        if (!$placement) {
            return collect();
        }

        $limit = $placement->settings['slider_config']['max_items']
            ?? $placement->settings['max_items']
            ?? null;

        return $this->adBannerRepository->builder(order:'asc', orderCol: 'priority')
            ->where('placement_id', $placement->id)
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->when($limit, fn($q) => $q->limit($limit))
            ->get();
    }

    private function getBanners($pageId, $key)
    {
        $placement = $this->getPlacement($pageId, $key);
        return $this->getBannersFromPlacement($placement);
    }

    private function getMerchants($pageId, $key)
    {
        $placement = $this->adPlacementRepository->builder()
            ->where('page_id', $pageId)
            ->where('key', $key)
            ->first();

        if (!$placement) {
            return collect();
        }

        $limit = $placement->settings['slider_config']['max_items']
            ?? $placement->settings['max_items']
            ?? null;

        $merchantContentRepo = new AdMerchantContentRepository(new AdMerchantContent());

        return $merchantContentRepo->builder(order:'asc', orderCol: 'priority')
            ->where('placement_id', $placement->id)
            ->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->whereHas('merchant', function ($query) {
                $query->where('status', 'active')
                    ->whereHas('branches', fn ($branchQuery) => $branchQuery->where('status', 1))
                    ->whereHas('items', fn($itemQuery) => $itemQuery->where('status', ActiveEnum::ACTIVE->value)->availableForStorefront());
            })
            ->with(['merchant' => function($q) {
                $q->withCount([
                    'branches',
                    'orders',
                    'items' => fn($itemQuery) => $itemQuery->where('status', ActiveEnum::ACTIVE->value)->availableForStorefront(),
                    'orders as reviews_count' => function ($query) {
                        $query->where('rate', '!=', 0);
                    },
                ]);
                $q->with(['branches:id,merchant_id,cover_image']);
                $q->with(['productCategories' => function($q) {
                    $q->select('categories.id', 'categories.title')
                        ->whereHas('items', fn($itemQuery) => $itemQuery->where('status', ActiveEnum::ACTIVE->value)->availableForStorefront())
                        ->limit(3);
                }]);
            }])
            ->when($limit, fn($q) => $q->limit($limit))
            ->get()
            ->pluck('merchant')
            ->filter();
    }

    private function getFooterData(?int $pageId, string $key): ?array
    {
        $placement = $pageId === null
            ? null
            : $this->adPlacementRepository->builder()
                ->where('page_id', $pageId)
                ->where('key', $key)
                ->first();

        if (!$placement) {
            $placementQuery = $this->adPlacementRepository->builder()
                ->where('type', PlacementTypeEnum::FOOTER->value)
                ->where('status', StatusEnum::ACTIVE->value);

            if ($pageId !== null) {
                $placementQuery->where('page_id', $pageId);
            }

            $placement = $placementQuery->first();
        }

        if (!$placement && $pageId !== null) {
            $placement = $this->adPlacementRepository->builder()
                ->where('type', PlacementTypeEnum::FOOTER->value)
                ->where('status', StatusEnum::ACTIVE->value)
                ->first();
        }

        if (!$placement) {
            return null;
        }

        $banner = $this->adBannerRepository->builder()
            ->where('placement_id', $placement->id)
            ->where('status', 'active')
            ->first();

        if (!$banner) {
            return null;
        }

        $content = $banner->content ?? [];

        $logoPath = $banner->image_desktop
            ?? data_get($content, 'images.desktop')
            ?? data_get($content, 'logo');

        if ($logoPath && filter_var($logoPath, FILTER_VALIDATE_URL)) {
            $content['logo'] = $logoPath;
        } elseif ($logoPath && Storage::disk('fileDisk')->exists($logoPath)) {
            $content['logo'] = Storage::disk('fileDisk')->url($logoPath)
                . '?v=' . Storage::disk('fileDisk')->lastModified($logoPath);
        } elseif ($logoPath && file_exists(public_path($logoPath))) {
            $content['logo'] = asset($logoPath);
        } else {
            unset($content['logo']);
        }

        return $content;
    }

    private function fillWithFallbacks($data): array
    {
        if (!isset($data['featuredProducts']) || $data['featuredProducts']->isEmpty()) {
            $data['featuredProducts'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->latest()->take(8)->get();
        }

        if (!isset($data['newProducts']) || $data['newProducts']->isEmpty()) {
            $data['newProducts'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->latest()->take(6)->get();
        }

        if (!isset($data['bestSellers']) || $data['bestSellers']->isEmpty()) {
            $data['bestSellers'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->inRandomOrder()->take(6)->get();
        }

        if (!isset($data['exclusiveProducts']) || $data['exclusiveProducts']->isEmpty()) {
            $data['exclusiveProducts'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->inRandomOrder()->take(6)->get();
        }

        if (!isset($data['newProductsSidebar']) || $data['newProductsSidebar']->isEmpty()) {
            $data['newProductsSidebar'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->latest()->take(9)->get();
        }

        if (!isset($data['recentlyViewed']) || $data['recentlyViewed']->isEmpty()) {
            $data['recentlyViewed'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->inRandomOrder()->take(9)->get();
        }

        if (!isset($data['sectionNewArrivals']) || $data['sectionNewArrivals']->isEmpty()) {
            $data['sectionNewArrivals'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->latest()->take(6)->get();
        }

        $data['suggestedProducts'] = $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
            ->inRandomOrder()->take(6)->get();

        $data['categories'] = $data['categories'] ?? collect();
        $data['services'] = $data['services'] ?? collect();
        $data['mainSlider'] = $data['mainSlider'] ?? collect();
        $data['mainSliderBackground'] = $data['mainSliderBackground'] ?? null;
        $data['largeBanner'] = $data['largeBanner'] ?? collect();
        $data['sidebarBanner'] = $data['sidebarBanner'] ?? collect();
        $data['sidebarBanner2'] = $data['sidebarBanner2'] ?? collect();
        $data['productBanners'] = $data['productBanners'] ?? collect();
        $data['recentNews'] = $data['recentNews'] ?? collect();
        $data['clientBrands'] = $data['clientBrands'] ?? collect();
        $data['merchantSlider'] = $data['merchantSlider'] ?? collect();
        $data['testimonials'] = $data['testimonials'] ?? [];

        if (!isset($data['mainSlider']) || (is_countable($data['mainSlider']) && count($data['mainSlider']) === 0) || (method_exists($data['mainSlider'], 'isEmpty') && $data['mainSlider']->isEmpty())) {
            $data['mainSlider'] = $this->getDefaultHeroSlider();
        }

        if ($data['productBanners']->isEmpty()) {
            $data['productBanners'] = $this->getDefaultBanners('grid', 2);
        }

        return $data;
    }

    private function getDefaultBanners($type, $count)
    {
        $banners = collect();
        for ($i = 1; $i <= $count; $i++) {
            $banners->push((object)[
                'desktop_image_url' => "https://via.placeholder.com/1200x400/0066cc/ffffff?text=Banner+{$i}",
                'mobile_image_url' => "https://via.placeholder.com/600x300/0066cc/ffffff?text=Banner+{$i}",
                'localized_title' => "Default Banner {$i}",
                'localized_subtitle' => "Click to explore our products",
                'click_url' => '#',
            ]);
        }
        return $banners;
    }

    private function getTestimonials(): array
    {
        return CustomerTestimonial::query()
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (CustomerTestimonial $testimonial): array => [
                'name' => $testimonial->name,
                'position' => $testimonial->position ?? '',
                'image' => $testimonial->image && !\Illuminate\Support\Str::startsWith($testimonial->image, ['http://', 'https://'])
                    ? Storage::disk('fileDisk')->url($testimonial->image)
                    : $testimonial->image,
                'content' => $testimonial->content,
            ])
            ->all();
    }

    private function getDefaultHeroSlider(): \Illuminate\Support\Collection
    {
        $locale = app()->getLocale();
        $isAr = in_array($locale, ['ar', 'he']);

        $slides = [
            [
                'title' => [
                    'ar' => 'كل ما تحتاجه - لمستقبل طاقة أفضل',
                    'en' => 'Everything you need for a better energy future',
                ],
                'subtitle' => [
                    'ar' => 'اكتشف منظومة الألواح الشمسية ومعدات الطاقة بتقنية عالية الجودة بأفضل الأسعار والضمان',
                    'en' => 'Discover high-quality solar panel systems and energy equipment with the best warranty',
                ],
                'desktop_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'tablet_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'mobile_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'click_url' => '#products',
            ],
            [
                'title' => [
                    'ar' => 'حلول الطاقة المتجددة والمستدامة',
                    'en' => 'Renewable & Sustainable Energy Solutions',
                ],
                'subtitle' => [
                    'ar' => 'أنظمة شمسية متطورة وكفاءة عالية وتوفير مستمر للطاقة لمنزلك ومنشأتك',
                    'en' => 'Advanced solar systems with high efficiency and continuous savings for your home & facility',
                ],
                'desktop_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'tablet_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'mobile_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'click_url' => '#products',
            ],
            [
                'title' => [
                    'ar' => 'خدمات واستشارات هندسية متخصصة',
                    'en' => 'Specialized Engineering Services',
                ],
                'subtitle' => [
                    'ar' => 'فريق معتمد يقدم لك خدمات التصميم والتوريد والتركيب والصيانة بأعلى المعايير',
                    'en' => 'Certified team offering design, supply, installation and maintenance with top standards',
                ],
                'desktop_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'tablet_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'mobile_image_url' => asset('assets/ecommerce/images/slider/4.webp'),
                'click_url' => '#products',
            ],
        ];

        return collect($slides)->map(function ($item) use ($locale, $isAr) {
            $localizedTitle = $item['title'][$locale] ?? ($isAr ? $item['title']['ar'] : $item['title']['en']);
            $localizedSubtitle = $item['subtitle'][$locale] ?? ($isAr ? $item['subtitle']['ar'] : $item['subtitle']['en']);

            return (object) [
                'localized_title' => $localizedTitle,
                'localized_subtitle' => $localizedSubtitle,
                'desktop_image_url' => $item['desktop_image_url'],
                'tablet_image_url' => $item['tablet_image_url'],
                'mobile_image_url' => $item['mobile_image_url'],
                'click_url' => $item['click_url'],
            ];
        });
    }

    private function getEmptyData(): array
    {
        return [
            'featuredProducts' => collect(),
            'newProducts' => collect(),
            'bestSellers' => collect(),
            'exclusiveProducts' => collect(),
            'newProductsSidebar' => collect(),
            'recentlyViewed' => collect(),
            'suggestedProducts' => collect(),
            'categories' => collect(),
            'services' => collect(),
            'mainSlider' => $this->getDefaultHeroSlider(),
            'mainSliderSettings' => [],
            'mainSliderBackground' => null,
            'largeBanner' => collect(),
            'sidebarBanner' => collect(),
            'sidebarBanner2' => collect(),
            'productBanners' => collect(),
            'recentNews' => collect(),
            'clientBrands' => collect(),
            'merchantSlider' => collect(),
            'footerData' => null,
            'testimonials' => [],
            'tabGroups' => [],
            'sectionNewArrivals' => collect(),
        ];
    }

    private function getFallbackData(): array
    {
        return $this->fillWithFallbacks($this->getEmptyData());
    }
}
