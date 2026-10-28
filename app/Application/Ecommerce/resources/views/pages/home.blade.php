@extends('ecommerce::layout.layout')

@section('title', __('ecommerce::ecommerce.home'))

@push('head')
@foreach($clientBrands ?? [] as $brand)
<link rel="preload" as="image" href="{{ $brand->desktop_image_url }}">
@endforeach
@endpush

@section('content')

<div class="pt-3">
    <div class="container">
        <div class="row">
            @include('ecommerce::layout.sidebar')

            <div class="col-lg-9">

                <div class="home-slider slider-ecommerce">
                    <div class="slide_1 owl-carousel owl-theme"
                        data-loop="{{ isset($mainSliderSettings['loop']) && filter_var($mainSliderSettings['loop'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }}"
                        data-autoplay="{{ isset($mainSliderSettings['autoplay']) && filter_var($mainSliderSettings['autoplay'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }}"
                        data-autoplay-timeout="{{ $mainSliderSettings['autoplay_speed'] ?? 5000 }}"
                        data-smart-speed="{{ $mainSliderSettings['transition_speed'] ?? 1000 }}">
                        @forelse($mainSlider as $banner)
                        <div>
                            <img src="{{ $banner->desktop_image_url ?: asset('assets/ecommerce/images/slider/4.webp') }}"
                                class="bg-img" alt="{{ $banner->localized_title }}">
                            <div class="slider_content">
                                <div>
                                    <h1 data-animation-in="slideInDown" data-animation-out="animate-out slideOutUp">
                                        <span
                                            class="sec_one">{{ $banner->localized_title ?: __('ecommerce::ecommerce.best_machine_tools') }}</span>
                                    </h1>
                                    <h5 data-animation-in="slideInRight" data-animation-out="animate-out fadeOut"
                                        style="color: #000000">
                                        {{ $banner->localized_subtitle ?: __('ecommerce::ecommerce.merchant_bio') }}
                                    </h5>
                                    <a class="slider_btn theme_btn btn_5" data-animation-in="slideInUp"
                                        data-animation-out="animate-out slideOutDown"
                                        href="{{ $banner->click_url ?: '#' }}">
                                        @lang('ecommerce::ecommerce.shop_now')
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div>
                            <img src="{{ asset('assets/ecommerce/images/slider/4.webp') }}" class="bg-img">
                            <div class="slider_content">
                                <div>
                                    <h1 data-animation-in="slideInDown" data-animation-out="animate-out slideOutUp">
                                        <span class="sec_one">@lang('ecommerce::ecommerce.best_machine_tools')</span>
                                    </h1>
                                    <h5 data-animation-in="slideInRight" data-animation-out="animate-out fadeOut"
                                        style="color: #fff">
                                        @lang('ecommerce::ecommerce.merchant_bio')
                                    </h5>
                                    <a class="slider_btn theme_btn btn_5" data-animation-in="slideInUp"
                                        data-animation-out="animate-out slideOutDown" href="#">
                                        @lang('ecommerce::ecommerce.shop_now')
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="section-b-space pt-4 pb-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="title">
                                <h2>@lang('ecommerce::ecommerce.sidebar.categories')</h2>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="swiper swiper-cat-container">
                                <div class="swiper-wrapper">
                                    @foreach($categories as $category)
                                    <div class="swiper-slide">
                                        <a class="cat-card"
                                            href="{{ route('categories.index', ['main_category' => $category->id]) }}"
                                            up-target=".main-content" up-preload up-instant>
                                            <div class="cat-circle">
                                                <img src="{{ $category->image ?? asset('assets/ecommerce/images/categories/default.jpg') }}"
                                                    alt="{{ $category->name }}">
                                            </div>
                                            <!-- Dynamically translated title if available, otherwise fallback -->
                                            <h6>{{ $category->title }}</h6>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="pb-0 banner-section">
                    <div class="row">
                        @forelse($productBanners->take(2) as $banner)
                        <div class="col-lg-6">
                            <a href="{{ $banner->click_url ?: '#' }}">
                                <div class="collection-banner">
                                    <div class="img-part">
                                        <img src="{{ $banner->desktop_image_url ?: asset('assets/ecommerce/images/solar/products/3.jpg') }}"
                                            class="bg-img" alt="">
                                    </div>
                                    <div class="contain-banner">
                                        <div>
                                            <h3>{{ $banner->localized_title ?: __('ecommerce::ecommerce.cutter_machine') }}
                                            </h3>
                                            <p>{{ $banner->localized_subtitle ?: __('ecommerce::ecommerce.plant_power_cutter_machine') }}
                                            </p>
                                            <div class="slider_btn theme_btn btn_5">
                                                @lang('ecommerce::ecommerce.buy_now')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="col-lg-6">
                            <a href="#">
                                <div class="collection-banner">
                                    <div class="img-part">
                                        <img src="{{ asset('assets/ecommerce/images/solar/products/3.jpg') }}"
                                            class="bg-img" alt="">
                                    </div>
                                    <div class="contain-banner">
                                        <div>
                                            <h3>@lang('ecommerce::ecommerce.cutter_machine')</h3>
                                            <p>@lang('ecommerce::ecommerce.plant_power_cutter_machine')</p>
                                            <div class="slider_btn theme_btn btn_5">
                                                @lang('ecommerce::ecommerce.buy_now')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="#">
                                <div class="collection-banner">
                                    <div class="img-part">
                                        <img src="{{ asset('assets/ecommerce/images/solar/products/2.jpg') }}"
                                            class="bg-img" alt="">
                                    </div>
                                    <div class="contain-banner">
                                        <div>
                                            <h3>@lang('ecommerce::ecommerce.brandix')</h3>
                                            <p>@lang('ecommerce::ecommerce.head_drilling_machine')</p>
                                            <div class="slider_btn theme_btn btn_5">
                                                @lang('ecommerce::ecommerce.buy_now')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforelse
                    </div>
                </section>

                <section class="product_sec pb-0">
                    <div class="collection-product-wrapper">
                        <div class="row product-wrapper-grid f_product">
                            <div class="col-12">
                                <div class="title">
                                    <h2>@lang('ecommerce::ecommerce.featured_products')</h2>
                                </div>
                            </div>
                            <div class="col position-relative">
                                <div class="theme-tab">
                                    <ul class="tabs tab-title">
                                        <li
                                            class="{{ request()->routeIs('products.new-arrivals') || request()->routeIs('home') || request()->routeIs('index') ? 'current' : '' }}">
                                            <a href="{{ route('products.new-arrivals') }}" up-target=".theme-tab"
                                                up-preload>
                                                @lang('ecommerce::ecommerce.new_products')
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('products.featured') ? 'current' : '' }}">
                                            <a href="{{ route('products.featured') }}" up-target=".theme-tab"
                                                up-preload>
                                                @lang('ecommerce::ecommerce.featured_products')
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('products.best-sellers') ? 'current' : '' }}">
                                            <a href="{{ route('products.best-sellers') }}" up-target=".theme-tab"
                                                up-preload>
                                                @lang('ecommerce::ecommerce.best_sellers')
                                            </a>
                                        </li>
                                    </ul>
                                    @php
                                    // Determine which product set to pass to the partial based on the route
                                    $tabNew = null;
                                    $tabFeatured = null;
                                    $tabBest = null;

                                    if (request()->routeIs('products.featured')) {
                                    $tabFeatured = $featuredProducts ?? collect();
                                    } elseif (request()->routeIs('products.best-sellers')) {
                                    $tabBest = $bestSellers ?? collect();
                                    } else {
                                    // Default to New Arrivals for home/index or explicit new-arrivals route
                                    $tabNew = $newProducts ?? collect();
                                    }
                                    @endphp
                                    @include('ecommerce::pages.partials.tab-content', [
                                    'newProducts' => $tabNew,
                                    'featuredProducts' => $tabFeatured,
                                    'bestSellers' => $tabBest
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <section class=" pb-0">
                    <div class="border-theme add-banner br-5 main-title">
                        <div class="title">
                            <h2>@lang('ecommerce::ecommerce.exclusive_collections')</h2>
                        </div>
                        <div>
                            <div class="vert-midd theme-slider">
                                @foreach($exclusiveProducts as $product)
                                <div class="inner-slide">
                                    <div class="media d-flex">
                                        <div class="img-wrapper">
                                            <div class="front">
                                                <a href="{{ route('product.show', $product->id) }}"
                                                    up-target=".main-body">
                                                    <img src="{{ $product->image_path ?? asset('assets/images/ecommerce/Product/1.jpg') }}"
                                                        alt="{{ $product->title }}"
                                                        class="img-fluid product-image-fixed">
                                                </a>
                                                <div>
                                                    <a href="#"
                                                        data-quick-view="{{ route('quick-view', $product->id) }}"
                                                        class="add_hover add_search" title="@lang('ecommerce::ecommerce.quick_view')"
                                                        up-follow="false" tabindex="0">
                                                        <i class="ti-search" aria-hidden="true"></i>
                                                    </a>
                                                    {{-- Wishlist Button --}}
                                                    @include('ecommerce::partials.product.wishlist-btn', ['product' => $product])
                                                    @auth
                                                    <a href="{{ route('cart.add') }}" up-method="post"
                                                        up-params='{"item_id": "{{ $product->id }}", "qty": 1}'
                                                        up-target=".cart-header" up-scroll="false" title="@lang('ecommerce::ecommerce.add_to_cart')"
                                                        class="add_hover" tabindex="0">
                                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                                    </a>
                                                    @else
                                                    <a href="#"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#loginRequiredModal"
                                                        title="@lang('ecommerce::ecommerce.add_to_cart')"
                                                        class="add_hover" tabindex="0">
                                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                                    </a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media-body align-self-center">
                                            <div class="timer-product-desc">
                                                <div class="product-info">
                                                    <a href="{{ route('product.show', $product->id) }}"
                                                        up-target=".main-body">
                                                        <h6 class="mb-3 product-title-fixed">
                                                            {{ \Illuminate\Support\Str::limit($product->title, 30) }}
                                                        </h6>
                                                    </a>
                                                    <div class="rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i
                                                            class="fa fa-star{{ $i <= ($product->rate ?? 4) ? ' active' : '' }}"></i>
                                                            @endfor
                                                    </div>
                                                    <p class="pro-desc product-description-fixed">
                                                        {{ \Illuminate\Support\Str::limit($product->description ?? __('ecommerce::ecommerce.product.no_description'), 100) }}
                                                    </p>
                                                    <div class="prices">
                                                        <h4 class="pro-price">
                                                            {{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                                                        <h4 class="pro-price-del">
                                                            <del>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price * 1.5) }}</del>
                                                        </h4>
                                                    </div>
                                                    <a class="slider_btn theme_btn btn_5" data-animation-in="slideInUp"
                                                        data-animation-out="animate-out slideOutDown"
                                                        href="{{ route('product.show', $product->id) }}"
                                                        up-target=".main-body" up-preload>
                                                        @lang('ecommerce::ecommerce.shop_now')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <section class="home_1_team home_5_team pb-0">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="title">
                                    <h2>@lang('ecommerce::ecommerce.new_arrival')</h2>
                                </div>
                            </div>
                            <div class="col-12 collection-product-wrapper">
                                <div class="product-five product-m no-arrow product-wrapper-grid">
                                    @foreach($sectionNewArrivals->take(6) as $product)
                                    <div class="item">
                                        <div class="product-box product-card-fixed">
                                            <div class="img-wrapper">
                                                <div class="front">
                                                    <a href="{{ route('product.show', $product->id) }}"
                                                        up-target=".main-body">
                                                        <img src="{{ $product->image_path ?? asset('assets/images/ecommerce/Product/1.jpg') }}"
                                                            class="img-fluid product-image-fixed"
                                                            alt="{{ $product->title }}">
                                                    </a>
                                                    <div class="product-actions">
                                                        <a href="#"
                                                            data-quick-view="{{ route('quick-view', $product->id) }}"
                                                            class="add_hover add_search" title="@lang('ecommerce::ecommerce.quick_view')"
                                                            up-follow="false">
                                                            <i class="ti-search" aria-hidden="true"></i>
                                                        </a>
                                                        {{-- Wishlist Button --}}
                                                        @include('ecommerce::partials.product.wishlist-btn', ['product' => $product])
                                                        @auth
                                                        <a href="{{ route('cart.add') }}" title="@lang('ecommerce::ecommerce.add_to_cart')"
                                                            class="add_hover" up-method="post"
                                                            up-params='{"item_id": "{{ $product->id }}", "qty": 1}'
                                                            up-target=".cart-header" up-scroll="false">
                                                            <i class="ti-shopping-cart" aria-hidden="true"></i>
                                                        </a>
                                                        @else
                                                        <a href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#loginRequiredModal"
                                                            title="@lang('ecommerce::ecommerce.add_to_cart')"
                                                            class="add_hover" tabindex="0">
                                                            <i class="ti-shopping-cart" aria-hidden="true"></i>
                                                        </a>
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-detail product-detail-fixed">
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i
                                                        class="fa fa-star{{ $i <= ($product->rate ?? 4) ? ' active' : '' }}"></i>
                                                        @endfor
                                                </div>
                                                <a href="{{ route('product.show', $product->id) }}"
                                                    up-target=".main-body">
                                                    <h6 class="product-title-fixed">
                                                        {{ \Illuminate\Support\Str::limit($product->title, 35) }}
                                                    </h6>
                                                </a>
                                                <p class="product-description-fixed">
                                                    {{ \Illuminate\Support\Str::limit($product->description ?? __('ecommerce::ecommerce.product.no_description'), 80) }}
                                                </p>
                                                <h4 class="product-price">
                                                    {{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- Updated Wide Merchant Slider -->
                <!-- Premium Merchant Slider (3 Rows + Detailed) -->
                @if(isset($merchantSlider) && $merchantSlider->isNotEmpty())
                <section class="merchant-premium-section" style="padding-top: 50px; padding-bottom: 50px;">
                    <div class="container">
                        <div class="row mb-4">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h2 class="fw-bold m-0 section-title">@lang('ecommerce::ecommerce.merchants')</h2>
                                <!-- <a href="#" class="view-all-link">@lang('ecommerce::ecommerce.view_all') <i
                                                class="ti-arrow-left arrow-start ms-1"></i></a> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="horizontal-merchant-slider owl-carousel owl-theme">
                                    @foreach($merchantSlider as $merchant)
                                    @php
                                    $merchantName = is_array($merchant->company_name) ? ($merchant->company_name[app()->getLocale()] ?? $merchant->company_name['en'] ?? 'Merchant') : ($merchant->company_name ?? 'Merchant');
                                    $description = is_array($merchant->description) ? ($merchant->description[app()->getLocale()] ?? $merchant->description['en'] ?? '') : ($merchant->description ?? '');
                                    $logoImg = $merchant->logo ?? null;
                                    $coverImg = asset('assets/ecommerce/images/slider/4.webp');
                                    $defaultBranch = $merchant->branches ? $merchant->branches->firstWhere(function($branch) {
                                    return !empty($branch->cover_image);
                                    }) : null;
                                    if (!$defaultBranch) {
                                    $defaultBranch = $merchant->branches ? $merchant->branches->first() : null;
                                    }
                                    if ($defaultBranch && $defaultBranch->cover) {
                                    $coverImg = $defaultBranch->cover;
                                    }
                                    // Fallback if null
                                    if (!$logoImg) {
                                    $logoImg = asset('assets/ecommerce/images/logo.png');
                                    }

                                    $branchesCount = $merchant->branches_count ?? 0;
                                    $itemsCount = $merchant->items_count ?? 0;
                                    $reviewsCount = $merchant->reviews_count ?? 0;
                                    $rating = $merchant->rate ?? 5.0;
                                    $categories = $merchant->productCategories; // Using the new real-item relation
                                    @endphp
                                    <div class="item">
                                        <div class="premium-merchant-card">
                                            <div class="pm-cover">
                                                <img src="{{ $coverImg }}" alt="Cover">
                                                <div class="pm-overlay"></div>
                                                @if($merchant->status == 'active')
                                                <div class="pm-verified-badge" title="Verified Seller">
                                                    <i class="fa fa-check-circle"></i>
                                                    @lang('ecommerce::ecommerce.verified_seller')
                                                </div>
                                                @endif
                                            </div>
                                            <div class="pm-body">
                                                <div class="pm-avatar-wrapper">
                                                    <a href="{{ route('merchant.show', ['merchant' => $merchant->id]) }}"
                                                        class="pm-avatar">
                                                        @if($logoImg)
                                                        <img src="{{ $logoImg }}" alt="{{ $merchantName }}">
                                                        @else
                                                        <span>{{ mb_substr($merchantName, 0, 1) }}</span>
                                                        @endif
                                                    </a>
                                                </div>

                                                <div class="pm-info">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h4 class="pm-name">
                                                            <a
                                                                href="{{ route('merchant.show', ['merchant' => $merchant->id]) }}">{{ $merchantName }}</a>
                                                        </h4>
                                                        <div class="pm-rating">
                                                            <i class="fa fa-star text-warning"></i>
                                                            <span class="fw-bold">{{ number_format($rating, 1) }}</span>
                                                            <span class="text-muted ms-1">({{ $reviewsCount }})</span>
                                                        </div>
                                                    </div>

                                                    <!-- Tags / Categories -->
                                                    <!-- Tags / Categories (Horizontal Scroll) -->
                                                    <div class="pm-tags-container">
                                                        @if($categories->count() > 0)
                                                        @foreach($categories as $cat)
                                                        @php
                                                        $catTitle = $cat->title[app()->getLocale()] ?? $cat->title['en'] ?? $cat->title ?? '';
                                                        @endphp
                                                        @if(!empty($catTitle))
                                                        <span class="pm-tag">#{{ $catTitle }}</span>
                                                        @endif
                                                        @endforeach
                                                        @else
                                                        <span
                                                            class="pm-tag pm-tag-empty">#@lang('ecommerce::ecommerce.no_registered_category')</span>
                                                        @endif
                                                    </div>

                                                    <div class="pm-stats-row">
                                                        <span class="pm-stat"
                                                            title="@lang('ecommerce::ecommerce.branches')">
                                                            <i class="fa fa-building-o"></i> {{ $branchesCount }}
                                                            @lang('ecommerce::ecommerce.branches')
                                                        </span>
                                                        <span class="pm-stat"
                                                            title="@lang('ecommerce::ecommerce.productss')">
                                                            <i class="fa fa-cube"></i> {{ $itemsCount }}
                                                            @lang('ecommerce::ecommerce.productss')
                                                        </span>
                                                        <span class="pm-stat"
                                                            title="@lang('ecommerce::ecommerce.sales')">
                                                            <i class="fa fa-shopping-basket"></i>
                                                            {{ $merchant->orders_count ?? 0 }}
                                                            @lang('ecommerce::ecommerce.sales')
                                                        </span>
                                                    </div>

                                                    <a href="{{ route('merchant.show', ['merchant' => $merchant->id]) }}"
                                                        class="pm-btn-visit">
                                                        @lang('ecommerce::ecommerce.visit_store') <i
                                                            class="fa fa-angle-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <style>
                    .horizontal-merchant-slider .item {
                        padding: 15px;
                    }

                    .horizontal-merchant-slider.owl-carousel,
                    .horizontal-merchant-slider.owl-carousel.owl-loaded {
                        display: block;
                    }

                    .brand_slide_ecommerce.owl-carousel,
                    .brand_slide_ecommerce.owl-carousel.owl-loaded {
                        display: block;
                    }

                    .brand_slide_ecommerce {
                        display: block !important;
                    }

                    .brand_slide_ecommerce img {
                        display: block !important;
                        opacity: 1 !important;
                        visibility: visible !important;
                    }

                    .brand_slide_ecommerce .item {
                        padding: 15px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100px;
                    }

                    .brand_slide_ecommerce.brand-slider-single .owl-stage-outer {
                        display: flex;
                        justify-content: center;
                    }


                    .premium-merchant-card {
                        display: block;
                        background: #fff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
                        border: 1px solid #eef2f5;
                        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                        height: 100%;
                        position: relative;
                        display: flex;
                        flex-direction: column;
                    }

                    .premium-merchant-card:hover {
                        transform: translateY(-5px) scale(1.02);
                        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
                    }

                    .pm-cover {
                        height: 110px;
                        position: relative;
                        overflow: hidden;
                    }

                    .pm-cover img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transition: transform 0.5s ease;
                    }

                    .premium-merchant-card:hover .pm-cover img {
                        transform: scale(1.05);
                    }

                    .pm-overlay {
                        position: absolute;
                        inset: 0;
                        background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
                    }

                    .pm-verified-badge {
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background: rgba(255, 255, 255, 0.95);
                        padding: 4px 10px;
                        border-radius: 20px;
                        font-size: 0.7rem;
                        font-weight: 700;
                        color: #306FAA;
                        /* Requested Green */
                        display: flex;
                        align-items: center;
                        gap: 4px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        backdrop-filter: blur(4px);
                    }

                    .pm-verified-badge i {
                        font-size: 0.9rem;
                        color: #306FAA;
                    }

                    .pm-body {
                        padding: 0 20px 20px 20px;
                        position: relative;
                        flex-grow: 1;
                        /* Allow body to fill space */
                        display: flex;
                        flex-direction: column;
                    }

                    .pm-avatar-wrapper {
                        margin-top: -40px;
                        margin-bottom: 12px;
                        position: relative;
                        z-index: 2;
                        display: flex;
                        pointer-events: none;
                        pointer-events: auto;
                    }

                    .pm-avatar {
                        width: 75px;
                        height: 75px;
                        border-radius: 50%;
                        border: 4px solid #fff;
                        background: #fff;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: transform 0.3s ease;
                    }

                    .premium-merchant-card:hover .pm-avatar {
                        transform: scale(1.05);
                    }

                    .pm-avatar img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }

                    .pm-name a {
                        font-size: 1.1rem;
                        font-weight: 700;
                        color: #2b2b2b;
                        text-decoration: none;
                        transition: color 0.2s;
                    }

                    .pm-name a:hover {
                        color: var(--theme-color, #306FAA);
                    }

                    .pm-rating {
                        font-size: 0.8rem;
                        color: #f39c12;
                        background: #fff9ed;
                        padding: 3px 8px;
                        border-radius: 6px;
                        display: flex;
                        align-items: center;
                    }

                    /* Scrollable Tags Container */
                    .pm-tags-container {
                        display: flex;
                        flex-wrap: nowrap;
                        /* No wrapping */
                        gap: 8px;
                        margin-bottom: 12px;
                        overflow-x: auto;
                        /* Allow horizontal scroll */
                        padding-bottom: 5px;
                        /* Space for scrollbar if visible */
                        scrollbar-width: none;
                        /* Firefox: hide scrollbar */
                        -ms-overflow-style: none;
                        /* IE/Edge */
                    }

                    .pm-tags-container::-webkit-scrollbar {
                        display: none;
                        /* Chrome/Safari: hide scrollbar */
                    }

                    .pm-tag {
                        background: rgba(0, 0, 0, 0.03);
                        color: #3e3e3e;
                        /* Dark Text for readability */
                        padding: 4px 10px;
                        border-radius: 12px;
                        font-size: 0.75rem;
                        font-weight: 600;
                        border: 1px solid rgba(0, 0, 0, 0.05);
                        white-space: nowrap;
                        /* Prevent text break */
                        flex-shrink: 0;
                        /* Prevent shrinking */
                    }

                    .pm-tag:hover {
                        color: #306FAA;
                        border-color: #306FAA;
                    }

                    .pm-tag-empty {
                        color: #999;
                        font-style: italic;
                    }

                    .pm-desc {
                        font-size: 0.85rem;
                        color: #6c757d;
                        line-height: 1.5;
                        margin-bottom: 12px;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                        height: 40px;
                    }

                    .pm-stats-row {
                        display: flex;
                        gap: 15px;
                        margin-bottom: 15px;
                        border-top: 1px dashed #eee;
                        padding-top: 12px;
                    }

                    .pm-stat {
                        font-size: 0.8rem;
                        color: #666;
                        display: flex;
                        align-items: center;
                        gap: 5px;
                    }

                    .pm-name a:hover {
                        color: #306FAA;
                    }

                    .pm-stat i {
                        color: #306FAA;
                    }

                    .pm-btn-visit {
                        margin-top: auto;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 100%;
                        padding: 8px 0;
                        background: transparent;
                        border: 1px solid #306FAA;
                        /* Requested Green */
                        color: #306FAA;
                        border-radius: 8px;
                        font-size: 0.9rem;
                        font-weight: 600;
                        text-decoration: none;
                        transition: all 0.2s;
                    }

                    .pm-btn-visit:hover {
                        background: #306FAA;
                        color: #fff;
                    }

                    @if(app()->getLocale()=='ar') .pm-verified-badge {
                        right: auto;
                        left: 10px;
                    }

                    .pm-btn-visit i {
                        margin-right: 5px;
                    }

                    @else .pm-btn-visit i {
                        margin-left: 5px;
                    }

                    @endif
                </style>
                @endif

                <!-- Large Banner Section -->

                <!-- Large Banner Section -->
                @if(isset($largeBanner) && $largeBanner->isNotEmpty())
                <section class="pb-0">
                    <div class="container">
                        @php $lBanner = $largeBanner->first(); @endphp
                        <div>
                            <img src="{{ $lBanner->desktop_image_url}}" alt="" class="img-fluid">
                        </div>
                    </div>
                </section>

                {{-- <section class="home_1_news">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="title">
                                            <h2>@lang('ecommerce::ecommerce.recent_news')</h2>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="news_slide_ecommerce owl-carousel owl-theme">
                                            @foreach([1, 2, 3] as $news)
                                            <div class="item">
                                                <div class="news_sec">
                                                    <figure class="snip_con">
                                                        <img src="{{ asset('assets/images/ecommerce/Blog/' . $news . '.jpg') }}"
                alt="" class="img-fluid">
                </figure>
                <div class="text_p">
                    <div class="date_sec">
                        <div>
                            <h4>25</h4>
                            <h6>JULY</h6>
                        </div>
                    </div>
                    <h4>Sandford improvements</h4>
                    <p>
                        Lorem Ipsum is simply dummy text of the prin
                        and type setting industry. Lorem Ipsum has
                    </p>
                    <h6 class="readmore">
                        <a href="blog-details.html">Read More</a>
                    </h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
</div>
</section> --}}

<section class="brand_sec pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title">
                    <h2>@lang('ecommerce::ecommerce.our_client')</h2>
                </div>
            </div>
            <div class="col">
                <div class="brand_slide_ecommerce owl-carousel owl-theme @if(count($clientBrands) === 1) brand-slider-single @endif" dir="ltr">
                    @foreach($clientBrands as $brand)
                    <div class="item">
                        <a href="{{ $brand->click_url ?: '#' }}">
                            <img src="{{ $brand->desktop_image_url }}"
                                alt="{{ $brand->localized_title }}" fetchpriority="high" width="125" height="125">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    (function() {
        var checkJqueryMerchant = setInterval(function() {
            if (typeof $ !== 'undefined' && $.fn.owlCarousel) {
                clearInterval(checkJqueryMerchant);
                initMerchantSlider();
            }
        }, 100);

        function initMerchantSlider() {
            var slider = $(".horizontal-merchant-slider");
            // If already initialized, destroy and re-init or skip?
            // Unpoly might re-insert fragments, so safely init.
            if (slider.hasClass('owl-loaded')) return;

            slider.owlCarousel({
                loop: false,
                margin: 0, // Margin handled by padding in item to prevent clipping
                nav: false,
                dots: true,
                rtl: $('html').attr('dir') === 'rtl',
                responsive: {
                    0: {
                        items: 1
                    },
                    576: {
                        items: 1.5
                    },
                    768: {
                        items: 2
                    },
                    1200: {
                        items: 3
                    }
                }
            });
        }

        // Hook into Unpoly for page transitions
        if (window.up) {
            up.on('up:fragment:inserted', function(event) {
                const fragment = event.target;
                if (fragment && fragment.querySelector('.horizontal-merchant-slider')) {
                    initMerchantSlider();
                }
            });
        }
    })();

    (function() {
        function initBrandSliderIfNeeded() {
            var slider = $(".brand_slide_ecommerce");
            if (!slider.length) return;
            if (slider.hasClass('owl-loaded')) return;

            slider.owlCarousel({
                loop: slider.find('.item').length > 1,
                dots: false,
                margin: 50,
                items: 6,
                rtl: $('html').attr('dir') === 'rtl',
                responsive: {
                    0: { items: 1 },
                    481: { items: 2 },
                    600: { items: 2 },
                    768: { items: 3 },
                    992: { items: 4 },
                    1000: { items: 5 }
                }
            });
        }

        // Hook into Unpoly so the slider also initializes on client-side navigation
        // (the theme's script.js only runs on full page loads).
        if (window.up) {
            up.on('up:fragment:inserted', function(event) {
                const fragment = event.target;
                if (fragment && fragment.querySelector('.brand_slide_ecommerce')) {
                    initBrandSliderIfNeeded();
                }
            });
        }

        // Full page load: the theme's script.js usually handles it. Wait for it,
        // but fall back to our own init if it never happens (e.g. Unpoly inserts).
        var brandAttempts = 0;
        var brandTimer = setInterval(function() {
            if (typeof $ === 'undefined' || !$.fn.owlCarousel) return;
            var slider = $(".brand_slide_ecommerce");
            if (!slider.length) return;
            if (slider.hasClass('owl-loaded')) {
                clearInterval(brandTimer);
                return;
            }
            if (++brandAttempts >= 15) {
                clearInterval(brandTimer);
                initBrandSliderIfNeeded();
            }
        }, 100);
    })();



    if (window.up) {
        up.compiler('.products-main', function(element) {
            initializeMerchantFilters();
        });

        up.compiler('.merchant-page-header', function(element) {});
    }

    document.addEventListener("DOMContentLoaded", function() {
        var swiper = new Swiper(".swiper-cat-container", {
            spaceBetween: 10,

            grid: {
                rows: 2,
                fill: 'row'
            },

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },

            breakpoints: {
                0: {
                    slidesPerView: 3,
                    spaceBetween: 10,
                    grid: {
                        rows: 2,
                        fill: 'row'
                    }
                },
                576: {
                    slidesPerView: 4,
                    spaceBetween: 15,
                    grid: {
                        rows: 2,
                        fill: 'row'
                    }
                },
                992: {
                    slidesPerView: 5,
                    spaceBetween: 20,
                    grid: {
                        rows: 2,
                        fill: 'row'
                    }
                }
            }
        });
    });

    $(document).ready(function() {
        // Carousel: force RTL so the jQuery plugin slides in the correct direction
        $(".tq-slider-override").owlCarousel({
            loop: false,
            margin: 30,
            nav: true,
            dots: false,
            rtl: true,
            responsive: {
                0: {
                    items: 1.1,
                    margin: 10
                },
                768: {
                    items: 1.6
                },
                1200: {
                    items: 2 // Responsive breakpoint: do not change unless the layout is retested
                }
            }
        });
    });


    up.boot(() => {
        // Unpoly restores the page from its history cache, so we must force a fresh fetch
        up.on('up:history:restored', function(event) {
            // Only refresh the listing when the restored URL is the home page
            // Compare against the localized home URL (e.g. '/' or '/en/home')
            if (event.url === window.location.origin + '/' || window.location.pathname === '/') {

                console.log('Home page restored from history, refreshing content...');

                // Reload the listing fragment; the next parameter busts the cache
                up.reload('.col-lg-9', {
                    cache: false, // Always fetch fresh data instead of reusing the cache
                    focus: 'keep', // Do not move focus after the fragment swap
                    scroll: 'keep' // Do not scroll the page after the fragment swap
                });
            }
        });
    });

    // Compiler: revalidate the listing fragment after every unpoly navigation
    up.compiler('.col-lg-9', function(element) {
        // Only mark the listing as stale when it is the up-target of the navigation
        // Avoid an infinite loop: revalidate schedules a reload, so guard against re-entry
        up.revalidate('.col-lg-9');
    });
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style>
    .swiper-cat-container {
        width: 100%;
        height: auto;
        padding: 10px 5px 40px 5px;
    }

    .swiper-slide {
        height: auto;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .cat-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        width: 100%;
        cursor: pointer;
    }

    .cat-circle {
        width: 95px;
        height: 95px;
        background: #ffffff;
        border-radius: 50%;
        border: 2px solid #e6e6e6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        position: relative;
    }

    @media (min-width: 992px) {
        .cat-circle {
            width: 135px;
            height: 135px;
        }
    }

    .cat-circle img {
        width: 65% !important;
        height: auto !important;
        display: block !important;
        margin: 0 auto !important;
        filter: none;
        opacity: 1;
        transition: all 0.3s ease;
    }

    .cat-card:hover .cat-circle {
        border-color: #306FAA;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transform: translateY(-5px);
    }

    .cat-card:hover h6 {
        color: #306FAA;
    }

    .cat-card h6 {
        font-size: 14px;
        color: #333;
        font-weight: 700;
        margin: 0;
        text-align: center;
        transition: color 0.3s ease;
    }

    @media (min-width: 992px) {
        .cat-card h6 {
            font-size: 16px;
        }
    }

    .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #ccc;
        opacity: 0.5;
    }

    .swiper-pagination-bullet-active {
        background-color: #306FAA !important;
        opacity: 1;
        width: 20px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .product-title-fixed {
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 8px;
        font-size: 14px;
        line-height: 1.3;
    }

    .product-description-fixed {
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: 12px;
        color: #666;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    .product-box:hover .product-actions {
        opacity: 1;
    }

    .home-slider .bg-img {
        width: 100% !important;
        height: 450px !important;
        object-fit: cover !important;
    }

    .home-slider .slider_content {
        position: absolute;
        top: 50%;
        left: 5%;
        transform: translateY(-50%);
        color: #fff;
        text-align: start;
        z-index: 5;
    }

    .product-image-fixed {
        width: 250px;
        height: 250px;
        object-fit: cover;
        display: block;
        margin: 0 auto;
    }

    [dir="rtl"] .title h2,
    [dir="rtl"] .home-slider .slider_content {
        text-align: right;
    }

    [dir="rtl"] .home-slider .slider_content {
        left: auto;
        right: 5%;
    }

    /* Merchant Slider Styles */
    .merchant-slider-section {
        padding-top: 40px;
        padding-bottom: 20px;
    }

    .merchant-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .merchant-logo {
        width: 120px;
        height: 120px;
        object-fit: contain;
        background: #fff;
        border-radius: 50%;
        border: 2px solid #e6e6e6;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .merchant-name {
        font-size: 14px;
        color: #333;
        font-weight: 600;
        text-align: center;
        margin: 0;
        transition: color 0.3s ease;
    }

    .merchant-card:hover .merchant-logo {
        border-color: #306FAA;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .merchant-card:hover .merchant-name {
        color: #306FAA;
    }

    @media (min-width: 768px) {
        .merchant-logo {
            width: 150px;
            height: 150px;
        }

        .merchant-name {
            font-size: 16px;
        }
    }

    /* --- Ultra Wide Landscape UI --- */

    .tq-ultra-wide-section {
        padding: 40px 0 !important;
    }

    /* Ultra-wide store card - fixed height and flex layout */
    .tq-uw-card {
        display: flex !important;
        flex-direction: column !important;
        background: #ffffff !important;
        border: 1px solid #e2e2e2 !important;
        border-radius: 8px !important;
        /* Fixed height so all cards stay aligned in the grid */
        height: 230px !important;
        /* Breathing room between neighbouring cards */
        margin: 10px 5px !important;
        text-decoration: none !important;
        overflow: hidden !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .tq-uw-card:hover {
        border-color: #306FAA !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-4px) !important;
    }

    /* Store header (110px) */
    .tq-uw-header {
        height: 110px !important;
        position: relative !important;
        background-color: #f8f9fa !important;
    }

    .tq-uw-cover-img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    /* Overlapping avatar (Facebook style) */
    .tq-uw-avatar-wrap {
        position: absolute !important;
        bottom: -25px !important;
        left: 20px !important;
        z-index: 10 !important;
    }

    [dir="rtl"] .tq-uw-avatar-wrap {
        left: auto !important;
        right: 20px !important;
    }

    .tq-uw-avatar {
        width: 65px !important;
        height: 65px !important;
        background: #ffffff !important;
        border-radius: 8px !important;
        /* Border, not a drop shadow, keeps the white edge crisp */
        border: 3px solid #ffffff !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        overflow: hidden !important;
    }

    .tq-uw-avatar img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
    }

    /* Card body - extra top padding clears the overlapping avatar */
    .tq-uw-body {
        padding: 30px 18px 15px 18px !important;
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        background: #ffffff !important;
    }

    [dir="rtl"] .tq-uw-body {
        text-align: right !important;
    }

    .tq-uw-name-row {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-bottom: 3px !important;
    }

    .tq-uw-name {
        font-size: 17px !important;
        font-weight: 800 !important;
        color: #222 !important;
        margin: 0 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .tq-uw-verified {
        color: #1877f2 !important;
        font-size: 15px !important;
    }

    .tq-uw-bio {
        font-size: 13px !important;
        color: #666 !important;
        line-height: 1.4 !important;
        height: 38px !important;
        overflow: hidden !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        margin: 0 !important;
    }

    .tq-uw-footer {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding-top: 10px !important;
        border-top: 1px solid #f2f2f2 !important;
    }

    .tq-uw-stats {
        font-size: 12px !important;
        font-weight: 700 !important;
        color: #999 !important;
        display: flex !important;
        gap: 10px !important;
    }

    .tq-uw-rating {
        color: #EA993E !important;
    }

    .tq-uw-btn {
        font-size: 13px !important;
        font-weight: 800 !important;
        color: #306FAA !important;
    }

    /* --- Mobile / App Style (Flutter) --- */
    @media (max-width: 767px) {
        .tq-uw-card {
            height: 200px !important;
            /* Card spacing on mobile */
            margin: 10px 5px !important;
        }

        .tq-uw-header {
            height: 90px !important;
        }

        .tq-uw-avatar {
            width: 55px !important;
            height: 55px !important;
        }

        .tq-uw-body {
            padding: 25px 15px 12px 15px !important;
        }
    }
</style>

@endsection
