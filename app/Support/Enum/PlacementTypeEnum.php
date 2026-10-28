<?php

namespace App\Support\Enum;

enum PlacementTypeEnum: string
{
    case SLIDER = 'slider';
    case STATIC_IMAGE = 'static_image';
    case GRID_BANNERS = 'grid_banners';
    case VIDEO_BANNER = 'video_banner';
    case CAROUSEL = 'carousel';
    case HTML_WIDGET = 'html_widget';
    case PRODUCT_TABS = 'product_tabs';
    case PRODUCT_SLIDER = 'product_slider';
    case PRODUCT_SIDEBAR = 'product_sidebar';
    case MERCHANT_SLIDER = 'merchant_slider';
    case HEADER = 'header';
    case FOOTER = 'footer';

    public static function values(): array
    {
        return array_map(fn($case): string => $case->value, self::cases());
    }

    public function label(): string
    {
        return match($this) {
            self::SLIDER => __('admin::lang.Hero Slider'),
            self::STATIC_IMAGE => __('admin::lang.Static Banner'),
            self::GRID_BANNERS => __('admin::lang.Grid Banners'),
            self::VIDEO_BANNER => __('admin::lang.Video Banner'),
            self::CAROUSEL => __('admin::lang.Carousel'),
            self::HTML_WIDGET => __('admin::lang.HTML Widget'),
            self::PRODUCT_TABS => __('admin::lang.Product Tabs Section'),
            self::PRODUCT_SLIDER => __('admin::lang.Product Slider Section'),
            self::PRODUCT_SIDEBAR => __('admin::lang.Sidebar Product Widget'),
            self::MERCHANT_SLIDER => __('admin::lang.Merchant Slider'),
            self::HEADER => __('admin::lang.Header Content'),
            self::FOOTER => __('admin::lang.Footer Content'),
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::SLIDER => 'bi-images',
            self::STATIC_IMAGE => 'bi-image',
            self::GRID_BANNERS => 'bi-grid-3x3',
            self::VIDEO_BANNER => 'bi-camera-video',
            self::CAROUSEL => 'bi-arrow-left-right',
            self::HTML_WIDGET => 'bi-code-square',
            self::PRODUCT_TABS => 'bi-collection',
            self::PRODUCT_SLIDER => 'bi-images',
            self::PRODUCT_SIDEBAR => 'bi-layout-sidebar',
            self::MERCHANT_SLIDER => 'bi-shop',
            self::HEADER => 'bi-window-sidebar',
            self::FOOTER => 'bi-layout-three-columns',
        };
    }

    public function supportsMultipleItems(): bool
    {
        return in_array($this, [
            self::SLIDER,
            self::GRID_BANNERS,
            self::CAROUSEL,
            self::PRODUCT_TABS,
            self::PRODUCT_SLIDER,
            self::PRODUCT_SIDEBAR,
            self::MERCHANT_SLIDER,
        ]);
    }

    public function supportsVideo(): bool
    {
        return $this === self::VIDEO_BANNER;
    }

    public function isProductSection(): bool
    {
        return in_array($this, [
            self::PRODUCT_TABS,
            self::PRODUCT_SLIDER,
            self::PRODUCT_SIDEBAR,
        ]);
    }

    public function isCategorySection(): bool
    {
        return $this === self::CAROUSEL;
    }

    public function isMerchantSection(): bool
    {
        return $this === self::MERCHANT_SLIDER;
    }

    public function requiresImageDimensions(): bool
    {
        return in_array($this, [
            self::SLIDER,
            self::STATIC_IMAGE,
            self::GRID_BANNERS,
            self::VIDEO_BANNER,
            self::VIDEO_BANNER,
            self::HEADER,
            self::FOOTER,
        ]);
    }
}
