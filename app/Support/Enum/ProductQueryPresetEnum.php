<?php

namespace App\Support\Enum;

enum ProductQueryPresetEnum: string
{
    case LATEST = 'latest';
    case BEST_SELLING = 'best_selling';
    case FEATURED = 'featured';
    case ON_SALE = 'on_sale';
    case HIGHEST_RATED = 'highest_rated';
    case PRICE_LOW_HIGH = 'price_low_high';
    case PRICE_HIGH_LOW = 'price_high_low';
    case MOST_VIEWED = 'most_viewed';
    case TRENDING = 'trending';

    public function label(): string
    {
        return match($this) {
            self::LATEST => __('admin::lang.Latest Products'),
            self::BEST_SELLING => __('admin::lang.Best Selling'),
            self::FEATURED => __('admin::lang.Featured Products'),
            self::ON_SALE => __('admin::lang.On Sale'),
            self::HIGHEST_RATED => __('admin::lang.Highest Rated'),
            self::PRICE_LOW_HIGH => __('admin::lang.Price Low to High'),
            self::PRICE_HIGH_LOW => __('admin::lang.Price High to Low'),
            self::MOST_VIEWED => __('admin::lang.Most Viewed'),
            self::TRENDING => __('admin::lang.Trending Now'),
        };
    }

    public function description(): string
    {
        return match($this) {
            self::LATEST => __('admin::lang.Shows newest products added to the store'),
            self::BEST_SELLING => __('admin::lang.Products with highest sales'),
            self::FEATURED => __('admin::lang.Manually featured products'),
            self::ON_SALE => __('admin::lang.Products currently on discount'),
            self::HIGHEST_RATED => __('admin::lang.Top rated products by customers'),
            self::PRICE_LOW_HIGH => __('admin::lang.Sort by price ascending'),
            self::PRICE_HIGH_LOW => __('admin::lang.Sort by price descending'),
            self::MOST_VIEWED => __('admin::lang.Most viewed products'),
            self::TRENDING => __('admin::lang.Currently trending products'),
        };
    }
}
