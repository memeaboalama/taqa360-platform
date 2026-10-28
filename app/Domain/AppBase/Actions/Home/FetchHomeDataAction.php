<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\AppBase\Models\AdMerchantContent;
use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Domain\AppBase\Repositories\AdMerchantContentRepository;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\FetchCategoryAction;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Enum\ActiveEnum;
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
            Cache::forget('home_page_data_' . app()->getLocale());
        }

        return Cache::remember('home_page_data_' . app()->getLocale(), 1800, function() {
            $page = AdPage::where('key', 'home')->first();

            if (!$page) {
                return $this->fillWithFallbacks($this->getEmptyData());
            }

            $placements = $this->adPlacementRepository->builder(order: 'asc', orderCol: 'sort_order')
                ->where('page_id', $page->id)
                ->where('status', 'active')
                ->with(['activeBanners' => function($query) {
                    $query->orderBy('priority', 'asc');
                }])
                ->with(['activeProductContents' => function($query) {
                    $query->with('product')->orderBy('priority', 'asc');
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
                        $data['featuredProducts'] = $this->getGroupProductsFromContents($placement, 'featured', 8);
                        $data['newProducts'] = $this->getGroupProductsFromContents($placement, 'latest', 6);
                        $data['bestSellers'] = $this->getGroupProductsFromContents($placement, 'best_selling', 6);
                        break;

                    case 'home_exclusive_slider':
                    case 'home_exclusive_collection':
                        $data['exclusiveProducts'] = $products->take(6);
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
    }

    private function getGroupProductsFromContents($placement, $groupKey, $limit)
    {
        return $placement->activeProductContents
            ->filter(fn($content) => $content->group_key === $groupKey)
            ->pluck('product')
            ->filter()
            ->take($limit);
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
                    ->whereHas('items', fn($itemQuery) => $itemQuery->where('status', ActiveEnum::ACTIVE->value));
            })
            ->with(['merchant' => function($q) {
                $q->withCount([
                    'branches',
                    'orders',
                    'items' => fn($itemQuery) => $itemQuery->where('status', ActiveEnum::ACTIVE->value),
                    'orders as reviews_count' => function ($query) {
                        $query->where('rate', '!=', 0);
                    },
                ]);
                $q->with(['branches:id,merchant_id,cover_image']);
                $q->with(['productCategories' => function($q) {
                    $q->select('categories.id', 'categories.title')
                        ->whereHas('items', fn($itemQuery) => $itemQuery->where('status', ActiveEnum::ACTIVE->value))
                        ->limit(3);
                }]);
            }])
            ->when($limit, fn($q) => $q->limit($limit))
            ->get()
            ->pluck('merchant')
            ->filter();
    }

    private function getFooterData($pageId, $key)
    {
        $placement = $this->adPlacementRepository->builder()
            ->where('page_id', $pageId)
            ->where('key', $key)
            ->first();

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
            $content['logo'] = Storage::disk('fileDisk')->url($logoPath);
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
            $data['featuredProducts'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->latest()->take(8)->get();
        }

        if (!isset($data['newProducts']) || $data['newProducts']->isEmpty()) {
            $data['newProducts'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->latest()->take(6)->get();
        }

        if (!isset($data['bestSellers']) || $data['bestSellers']->isEmpty()) {
            $data['bestSellers'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->inRandomOrder()->take(6)->get();
        }

        if (!isset($data['exclusiveProducts']) || $data['exclusiveProducts']->isEmpty()) {
            $data['exclusiveProducts'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->inRandomOrder()->take(6)->get();
        }

        if (!isset($data['newProductsSidebar']) || $data['newProductsSidebar']->isEmpty()) {
            $data['newProductsSidebar'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->latest()->take(9)->get();
        }

        if (!isset($data['recentlyViewed']) || $data['recentlyViewed']->isEmpty()) {
            $data['recentlyViewed'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value])
                ->inRandomOrder()->take(9)->get();
        }

        if (!isset($data['sectionNewArrivals']) || $data['sectionNewArrivals']->isEmpty()) {
            $data['sectionNewArrivals'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->latest()->take(6)->get();
        }

        $data['suggestedProducts'] = $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value])
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
        return [
            [
                'name' => 'Ethen Mark',
                'position' => 'Director',
                'image' => 'assets/images/testimonial/1.png',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna'
            ],
            [
                'name' => 'Suzen Desoza',
                'position' => 'Director',
                'image' => 'assets/images/testimonial/2.png',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna'
            ],
            [
                'name' => 'John Deo',
                'position' => 'Director',
                'image' => 'assets/images/testimonial/3.png',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.'
            ]
        ];
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
            'mainSlider' => collect(),
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
