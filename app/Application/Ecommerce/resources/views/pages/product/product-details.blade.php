@extends('ecommerce::layout.layout')

@section('title', $product->title . ' | ' . __('ecommerce::ecommerce.site_name'))
@section('content')

<style>
.rating .fa-star {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
}

.rating .fa-star.active,
.rating .fa-star.hover {
    color: #ffa200;
}

.comment_rate {
    display: inline-flex;
    gap: 5px;
}

.product-social {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.product-social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.product-social a:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
}

.wishlist-btn {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.wishlist-btn:hover {
    color: #dc3545;
}

.wishlist-btn .fa-heart.text-danger {
    color: #dc3545 !important;
}

.review-item {
    padding: 15px 0;
}

.review-item:not(:last-child) {
    border-bottom: 1px solid #eee;
}

.media.d-flex {
    display: flex;
    align-items: flex-start;
}

.theme-card .media {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.theme-card .media:last-child {
    border-bottom: none;
}

.product-alert-message {
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.owl-carousel .owl-nav button {
    background: rgba(0,0,0,0.5) !important;
    color: white !important;
    border-radius: 50%;
    width: 40px;
    height: 40px;
}

.owl-carousel .owl-nav button:hover {
    background: rgba(0,0,0,0.8) !important;
}

.tab-content {
    padding: 20px 0;
}

.nav-tabs .nav-link {
    border: none;
    color: #666;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    border-bottom: 2px solid #007bff;
}

.material-border {
    height: 2px;
    background: transparent;
    margin-top: -2px;
}

.nav-link.active .material-border {
    background: #007bff;
}

/* --- New Styles Added --- */
.merchant-info-block .merchant-details p {
    margin-bottom: 5px;
}
.merchant-info-block .merchant-details .merchant-name a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}
.merchant-info-block .merchant-details .merchant-name a:hover {
    text-decoration: underline;
}
.services-list .service-item {
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 5px;
    transition: all 0.3s ease;
}
.services-list .service-item:hover {
    border-color: #007bff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.services-list .service-item h5 {
    margin-bottom: 5px;
}


.merchant-rating {
    display: inline-flex;
    align-items: center;
}

.merchant-rating .rating.small .fa-star {
    font-size: 0.8rem;
}

.merchant-rating .text-muted {
    font-size: 0.8rem;
}

.verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.verified-badge i {
    font-size: 0.9rem;
}

.verified-badge small {
    font-size: 0.75rem;
}

.store-name {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.store-name strong {
    font-size: 0.85rem;
}

.shop_bread_crumb .functionalities ul li a,
.shop_bread_crumb .functionalities ul li strong {
    color: #ffffff !important;
}
.shop_bread_crumb .functionalities ul li strong i {
    display: inline-block;
}
[dir="rtl"] .shop_bread_crumb .functionalities ul li strong i {
    transform: scaleX(-1);
}

</style>

<div class="bread_crumb shop_bread_crumb p-0">
    <div class="container">
        <div class="row">
            <div class="col-md-6 sec_low">
                <div class="functionalities">
                    <ul id="breadcrumb" class="breadcrumb">
                        <li><a title="@lang('ecommerce::ecommerce.home')" href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                        <li>
                            <a href="{{ $product?->main_category_id ? route('categories.index', ['main_category' => $product?->main_category_id]) : "#" }}" up-target=".main-body" up-preload>
                                {{ is_array($translation = $product?->mainCategory?->getTranslation('title', app()->getLocale())) ? ($translation[app()->getLocale()] ?? __('ecommerce::ecommerce.product_label')) : $translation }}
                            </a>
                        </li>
                        <li><strong><i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ \Illuminate\Support\Str::limit($product->title, 30) }}</strong></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-title">
                    <h2>{{ $product->title }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-b-space shop_page cmn_bg_yellow">
    <ul class="plus_decore">
        <li></li>
        <li></li>
    </ul>
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-sm-12 col-xs-12 product-left">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="filter-main-btn mb-2"><span class="filter-btn"><i class="fa fa-filter" aria-hidden="true"></i> @lang('ecommerce::ecommerce.filter')</span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="outer">
                                <div id="big" class="owl-carousel owl-theme">
                                    @if($product->images && count($product->images) > 0)
                                        @foreach($product->images as $image)
                                        <div class="item">
                                            <img src="{{ $image['image_path'] ?? $product->image_path ?? asset('assets/images/pro/b1.jpg') }}"
                                                 alt="{{ $product->title }}"
                                                 onerror="this.src='{{ asset('assets/images/pro/b1.jpg') }}'">
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="item">
                                            <img src="{{ $product->image_path ?? asset('assets/images/pro/b1.jpg') }}"
                                                 alt="{{ $product->title }}"
                                                 onerror="this.src='{{ asset('assets/images/pro/b1.jpg') }}'">
                                        </div>
                                        @for($i = 2; $i <= 4; $i++)
                                        <div class="item">
                                            <img src="{{ asset("assets/images/pro/b{$i}.jpg") }}" alt="{{ $product->title }}">
                                        </div>
                                        @endfor
                                    @endif
                                </div>
                                <div id="thumbs" class="owl-carousel owl-theme">
                                    @if($product->images && count($product->images) > 0)
                                        @foreach($product->images as $image)
                                        <div class="item">
                                            <img src="{{ $image['image_path'] ?? $product->image_path ?? asset('assets/images/pro/b1.jpg') }}"
                                                 alt="{{ $product->title }}"
                                                 onerror="this.src='{{ asset('assets/images/pro/b1.jpg') }}'">
                                        </div>
                                        @endforeach
                                    @else
                                        @for($i = 1; $i <= 4; $i++)
                                        <div class="item">
                                            <img src="{{ asset("assets/images/pro/b{$i}.jpg") }}" alt="{{ $product->title }}">
                                        </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 rtl-text">
                            <div class="product-right">
                                <h2>{{ $product->title }}</h2>

                                @if($product->sale_price && $product->sale_price < $product->default_price)
                                    @php
                                        $discountPercentage = round((($product->default_price - $product->sale_price) / $product->default_price) * 100);
                                    @endphp
                                    <h4>
                                        <del>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</del>
                                        <span>{{ $discountPercentage }}% @lang('ecommerce::ecommerce.off')</span>
                                    </h4>
                                    <h3>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->sale_price) }}</h3>
                                @else
                                    <h3>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h3>
                                @endif

                                <div class="rating mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star{{ $i <= ($product->rate ?? 4) ? ' active' : '' }}"></i>
                                    @endfor
                                    <span class="ms-2">({{ $product->reviews_count ?? 0 }} @lang('ecommerce::ecommerce.reviews'))</span>
                                </div>

                                <div class="product-description border-product">
                                    <h6 class="product-title">@lang('ecommerce::ecommerce.quantity')</h6>
                                    <div class="qty-box">
                                        <div class="input-group">
                                            <span class="input-group-prepend">
                                                <button type="button" class="btn quantity-left-minus" data-type="minus">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                            </span>
                                            <input type="text" name="quantity" class="form-control input-number" value="1" min="1" id="product-quantity">
                                            <span class="input-group-prepend">
                                                <button type="button" class="btn quantity-right-plus" data-type="plus">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-buttons d-flex gap-2">
                                    @if(auth()->check())
                                    <form class="add-to-cart-form" method="POST" action="{{ route('cart.add') }}"
                                        up-submit up-target=".cart-header" up-scroll="false">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $product->id }}">
                                        <input type="hidden" name="qty" value="1" id="cart-quantity">
                                        <button type="submit" class="theme_btn add-to-cart-btn">
                                            <span class="btn-text">@lang('ecommerce::ecommerce.add_to_cart')</span>
                                            <span class="btn-loading" style="display: none;">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                            <span class="theme_btn_eff"></span>
                                        </button>
                                    </form>

                                    <form class="buy-now-form" method="POST" action="{{ route('cart.add') }}"
                                        up-submit up-target=".main-body" up-scroll="false">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $product->id }}">
                                        <input type="hidden" name="qty" value="1" id="buy-now-quantity">
                                        <input type="hidden" name="redirect_to_checkout" value="1">
                                        <button type="submit" class="theme_btn buy-now-btn">
                                            <span class="btn-text">@lang('ecommerce::ecommerce.buy_now')</span>
                                            <span class="btn-loading" style="display: none;">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                            <span class="theme_btn_eff"></span>
                                        </button>
                                    </form>
                                    @else
                                    <button type="button" class="theme_btn add-to-cart-btn"
                                            data-bs-toggle="modal" data-bs-target="#loginRequiredModal">
                                            <span class="btn-text">@lang('ecommerce::ecommerce.add_to_cart')</span>
                                        <span class="theme_btn_eff"></span>
                                    </button>

                                    <button type="button" class="theme_btn buy-now-btn"
                                            data-bs-toggle="modal" data-bs-target="#loginRequiredModal">
                                            <span class="btn-text">@lang('ecommerce::ecommerce.buy_now')</span>
                                        <span class="theme_btn_eff"></span>
                                    </button>
                                    @endif
                                </div>

                                <div class="border-product">
                                    <h6 class="product-title">@lang('ecommerce::ecommerce.product_details')</h6>
                                    <p>{{ $product->description ?? __('ecommerce::ecommerce.no_description') }}</p>

                                    @if($product->calories || $product->preparing_time)
                                    <div class="product-meta mt-3">
                                        @if($product->calories)
                                            <p><strong>@lang('ecommerce::ecommerce.calories'):</strong> {{ $product->calories }} kcal</p>
                                        @endif
                                        @if($product->preparing_time)
                                            <p><strong>@lang('ecommerce::ecommerce.prep_time'):</strong> {{ $product->preparing_time }} @lang('ecommerce::ecommerce.mins')</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                {{-- <div class="border-product merchant-info-block">
                                    <h6 class="product-title">@lang('ecommerce::ecommerce.sold_by')</h6>
                                    <div class="merchant-details">
                                        <p class="merchant-name">
                                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                            <strong><a href="#">{{ $product->merchant->name }}</a></strong>
                                        </p>
                                        <div class="merchant-rating d-flex align-items-center">
                                            <div class="rating">
                                                <i class="fa fa-star active"></i>
                                                <i class="fa fa-star active"></i>
                                                <i class="fa fa-star active"></i>
                                                <i class="fa fa-star active"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <span class="ms-2 text-muted">(4.5 | 120 @lang('ecommerce::ecommerce.reviews'))</span>
                                        </div>
                                        <p class="branch-location mt-2">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                                            @lang('ecommerce::ecommerce.branch'): <strong>@lang('ecommerce::ecommerce.main_branch_riyadh')</strong>
                                        </p>
                                    </div>
                                </div> --}}

                                <div class="border-product merchant-info-block">
                                    <h6 class="product-title">@lang('ecommerce::ecommerce.sold_by')</h6>
                                    <div class="merchant-details">
                                        <p class="merchant-name">
                                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                            <strong>
                                            <a href="{{ route('merchant.show', $product->merchant->id) }}"
                                                up-target=".main-body"
                                                up-preload>
                                                    {{ $product->merchant->name }}
                                            </a>
                                            </strong>
                                            <span class="store-name ms-2">
                                                <strong>{{ $product->merchant->getTranslation('company_name', app()->getLocale()) ?: $product->merchant->getTranslation('company_name', 'en') }}</strong>
                                            </span>
                                            <span class="verified-badge ms-2">
                                                <i class="fa fa-check-circle text-success"></i>
                                                <small class="text-muted">@lang('ecommerce::ecommerce.verified_seller')</small>
                                            </span>
                                            <span class="merchant-rating ms-2">
                                                <div class="rating small d-inline">
                                                    <i class="fa fa-star active"></i>
                                                    <i class="fa fa-star active"></i>
                                                    <i class="fa fa-star active"></i>
                                                    <i class="fa fa-star active"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <span class="ms-1 text-muted small">4.5 (120)</span>
                                            </span>
                                        </p>
                                        <p class="branch-location mt-2">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                                            @lang('admin::home.Branch Name') : <strong>Main Branch - Riyadh</strong>
                                        </p>
                                    </div>
                                </div>


                                <div class="border-product">
                                    <h6 class="product-title">@lang('ecommerce::ecommerce.share_it')</h6>
                                    <div class="product-icon">
                                        <ul class="product-social">
                                            <li><a href="#" class="share-facebook" data-url="{{ url()->current() }}"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="#" class="share-twitter" data-url="{{ url()->current() }}" data-title="{{ $product->title }}"><i class="fa fa-twitter"></i></a></li>
                                            <li><a href="#" class="share-linkedin" data-url="{{ url()->current() }}"><i class="fa fa-linkedin"></i></a></li>
                                            <li><a href="#" class="share-whatsapp" data-url="{{ url()->current() }}" data-title="{{ $product->title }}"><i class="fa fa-whatsapp"></i></a></li>
                                        </ul>

                                        {{-- Wishlist Button --}}
                                        @include('ecommerce::partials.product.wishlist-btn', ['product' => $product, 'displayText' => true, 'style' => 'btn'])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Tabs Section -->
                    <div class="tab-product m-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="#top-home" role="tab" aria-selected="true">
                                            <i class="icofont icofont-ui-home"></i>@lang('ecommerce::ecommerce.description')
                                        </a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-top-tab" data-bs-toggle="tab" href="#top-profile" role="tab" aria-selected="false">
                                            <i class="icofont icofont-man-in-glasses"></i>@lang('ecommerce::ecommerce.details')
                                        </a>
                                        <div class="material-border"></div>
                                    </li>
                                    <!-- ===== START: New Services Tab Link ===== -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="services-top-tab" data-bs-toggle="tab" href="#top-services" role="tab" aria-selected="false">
                                            <i class="icofont icofont-tools-alt-2"></i>@lang('ecommerce::ecommerce.available_services')
                                        </a>
                                        <div class="material-border"></div>
                                    </li>
                                    <!-- ===== END: New Services Tab Link ===== -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-top-tab" data-bs-toggle="tab" href="#top-contact" role="tab" aria-selected="false">
                                            <i class="icofont icofont-contacts"></i>@lang('ecommerce::ecommerce.specifications')
                                        </a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="review-top-tab" data-bs-toggle="tab" href="#top-review" role="tab" aria-selected="false">
                                            <i class="icofont icofont-contacts"></i>@lang('ecommerce::ecommerce.reviews') ({{ $product->reviews_count ?? 0 }})
                                        </a>
                                        <div class="material-border"></div>
                                    </li>
                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                        <p>{{ $product->description ?? __('ecommerce::ecommerce.no_detailed_description') }}</p>

                                        @if($product->features)
                                            <h5>@lang('ecommerce::ecommerce.key_features'):</h5>
                                            <ul>
                                                @foreach(explode(',', $product->features) as $feature)
                                                    <li>{{ trim($feature) }}</li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @if($product->benefits)
                                            <h5>@lang('ecommerce::ecommerce.benefits'):</h5>
                                            <p>{{ $product->benefits }}</p>
                                        @endif
                                    </div>

                                    <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                                        <div class="single-product-tables">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.store_name')</strong></td>
                                                        <td>
                                                            @if($product->merchant)
                                                                <a href="{{ route('merchant.show', $product->merchant->id) }}" up-target=".main-body" up-preload>
                                                                    {{ $product->merchant->getTranslation('company_name', app()->getLocale()) ?: ($product->merchant->name ?? 'N/A') }}
                                                                </a>
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.category')</strong></td>
                                                        <td>{{ $product?->category->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.sku')</strong></td>
                                                        <td class="num">{{ $product->sku ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.price')</strong></td>
                                                        <td>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</td>
                                                    </tr>
                                                    @if($product->sale_price)
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.sale_price')</strong></td>
                                                        <td>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->sale_price) }}</td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.status')</strong></td>
                                                        <td>
                                                            <span class="badge badge-{{ $product->status->value === 'active' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($product->status->value) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.stock')</strong></td>
                                                        <td>{{ $product->stock ?? __('ecommerce::ecommerce.in_stock') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>@lang('ecommerce::ecommerce.added_date')</strong></td>
                                                        <td>{{ $product->created_at->format('M d, Y') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- ===== START: New Services Tab Content ===== -->
                                    <div class="tab-pane fade" id="top-services" role="tabpanel" aria-labelledby="services-top-tab">
                                        <p>@lang('ecommerce::ecommerce.services_intro')</p>
                                        <ul class="services-list list-unstyled mt-4">
                                            <li class="service-item d-flex align-items-center mb-3">
                                                <i class="fa fa-wrench fa-2x text-primary me-3"></i>
                                                <div>
                                                    <h5>@lang('ecommerce::ecommerce.solar_panel_installation')</h5>
                                                    <p class="mb-0 text-muted">@lang('ecommerce::ecommerce.solar_panel_desc')</p>
                                                </div>
                                            </li>
                                            <li class="service-item d-flex align-items-center mb-3">
                                                <i class="fa fa-cogs fa-2x text-primary me-3"></i>
                                                <div>
                                                    <h5>@lang('ecommerce::ecommerce.periodic_maintenance')</h5>
                                                    <p class="mb-0 text-muted">@lang('ecommerce::ecommerce.periodic_maintenance_desc')</p>
                                                </div>
                                            </li>
                                            <li class="service-item d-flex align-items-center mb-3">
                                                <i class="fa fa-leaf fa-2x text-primary me-3"></i>
                                                <div>
                                                    <h5>@lang('ecommerce::ecommerce.system_consultation')</h5>
                                                    <p class="mb-0 text-muted">@lang('ecommerce::ecommerce.system_consultation_desc')</p>
                                                </div>
                                            </li>
                                            <li class="service-item d-flex align-items-center">
                                                <i class="fa fa-shield fa-2x text-primary me-3"></i>
                                                <div>
                                                    <h5>@lang('ecommerce::ecommerce.extended_warranty')</h5>
                                                    <p class="mb-0 text-muted">@lang('ecommerce::ecommerce.extended_warranty_desc')</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- ===== END: New Services Tab Content ===== -->


                                    <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                                        <div class="specifications">
                                            @if($product->specifications)
                                                {!! $product->specifications !!}
                                            @else
                                                <p>@lang('ecommerce::ecommerce.no_specifications')</p>
                                                <div class="default-specs">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>@lang('ecommerce::ecommerce.brand')</strong></td>
                                                                <td>@lang('ecommerce::ecommerce.generic')</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>@lang('ecommerce::ecommerce.weight')</strong></td>
                                                                <td>N/A</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>@lang('ecommerce::ecommerce.dimensions')</strong></td>
                                                                <td>N/A</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>@lang('ecommerce::ecommerce.warranty')</strong></td>
                                                                <td>@lang('ecommerce::ecommerce.one_year')</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>@lang('ecommerce::ecommerce.material')</strong></td>
                                                                <td>N/A</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                        @if($product->reviews && $product->reviews->count() > 0)
                                            <div class="reviews-list mb-4">
                                                @foreach($product->reviews as $review)
                                                <div class="review-item border-bottom pb-3 mb-3">
                                                    <div class="review-header d-flex justify-content-between">
                                                        <div class="reviewer-info">
                                                            <strong>{{ $review->user->name ?? __('ecommerce::ecommerce.anonymous') }}</strong>
                                                            <div class="rating">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fa fa-star{{ $i <= $review->rating ? ' active' : '' }}"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                    @if($review->title)
                                                        <h6 class="mt-2">{{ $review->title }}</h6>
                                                    @endif
                                                    <p class="mb-0">{{ $review->comment }}</p>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">@lang('ecommerce::ecommerce.no_reviews_yet')</p>
                                        @endif

                                        <div class="review-form-section">
                                            <h5 class="mb-3">@lang('ecommerce::ecommerce.add_your_review')</h5>
                                            <form class="theme-form review-form" action="" method="POST" up-submit up-target="#top-review" up-scroll="false">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <div class="form-row row g-3">
                                                    <div class="col-md-12">
                                                        <div class="media d-flex">
                                                            <label class="me-3">@lang('ecommerce::ecommerce.rating')</label>
                                                            <div class="media-body">
                                                                <div class="rating three-star comment_rate" data-rating="0">
                                                                    <i class="fa fa-star" data-value="1"></i>
                                                                    <i class="fa fa-star" data-value="2"></i>
                                                                    <i class="fa fa-star" data-value="3"></i>
                                                                    <i class="fa fa-star" data-value="4"></i>
                                                                    <i class="fa fa-star" data-value="5"></i>
                                                                    <input type="hidden" name="rating" id="rating-value" value="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="name">@lang('ecommerce::ecommerce.name')</label>
                                                        <input type="text" class="form-control" name="name" id="name" placeholder="@lang('ecommerce::ecommerce.enter_your_name')" required
                                                               value="{{ auth()->user() ? auth()->user()->name : '' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email">@lang('ecommerce::ecommerce.email')</label>
                                                        <input type="email" dir="ltr" class="form-control" name="email" id="email" placeholder="@lang('ecommerce::ecommerce.email')" required
                                                               value="{{ auth()->user() ? auth()->user()->email : '' }}">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="title">@lang('ecommerce::ecommerce.review_title')</label>
                                                        <input type="text" class="form-control" name="title" id="title" placeholder="@lang('ecommerce::ecommerce.enter_review_title')" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="comment">@lang('ecommerce::ecommerce.your_review')</label>
                                                        <textarea class="form-control" name="comment" id="comment" placeholder="@lang('ecommerce::ecommerce.write_your_review')" rows="6" required></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="submit" class="theme_btn review_btn">
                                                            @lang('ecommerce::ecommerce.submit_review')
                                                            <span class="theme_btn_eff"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Section -->
                <div class="col-sm-3 collection-filter">
                    <div class="collection-filter-block">
                        <div class="collection-mobile-back">
                            <span class="filter-back">
                                <i class="fa fa-angle-left arrow-start" aria-hidden="true"></i>
                                @lang('ecommerce::ecommerce.back')
                            </span>
                        </div>
                        <div class="collection-collapse-block border-0 open mb-0">
                            <h3 class="collapse-block-title">@lang('ecommerce::ecommerce.related_categories')</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    <ul class="category-list">
                                        @foreach($relatedCategories as $category)
                                        <li><a href="{{ route('products.by-category', $category?->id) }}" up-target=".main-body" up-preload>{{ $category?->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Block -->
                    <div class="collection-filter-block">
                        <div class="product-service mb-0">
                            <div class="media d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <div class="media-body">
                                    <h4>@lang('ecommerce::ecommerce.free_shipping')</h4>
                                    <p>@lang('ecommerce::ecommerce.free_shipping_worldwide')</p>
                                </div>
                            </div>
                            <div class="media d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <div class="media-body">
                                    <h4>@lang('ecommerce::ecommerce.service_24_7')</h4>
                                    <p>@lang('ecommerce::ecommerce.online_service')</p>
                                </div>
                            </div>
                            <div class="media d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                <div class="media-body">
                                    <h4>@lang('ecommerce::ecommerce.festival_offer')</h4>
                                    <p>@lang('ecommerce::ecommerce.special_festival_offer')</p>
                                </div>
                            </div>
                            <div class="media d-flex border-0 m-0 pb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <div class="media-body">
                                    <h4>@lang('ecommerce::ecommerce.online_payment')</h4>
                                    <p>@lang('ecommerce::ecommerce.contrary_belief')</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Products -->
                    <div class="theme-card">
                        <h5 class="title-border">@lang('ecommerce::ecommerce.related_products')</h5>
                        <div class="offer-slider slide-1 collection-filter-block">
                            <div class="mb-0">
                                @foreach($relatedProducts as $relatedProduct)
                                <div class="media d-flex mb-3">
                                    <a href="{{ route('product.show', $relatedProduct->id) }}" up-target=".main-body" up-preload>
                                        <img class="img-fluid" src="{{ $relatedProduct->image_path ?? asset('assets/images/pro/1.jpg') }}"
                                             alt="{{ $relatedProduct->title }}"
                                             style="width: 80px; height: 80px; object-fit: cover;"
                                             onerror="this.src='{{ asset('assets/images/pro/1.jpg') }}'">
                                    </a>
                                    <div class="media-body align-self-center ms-3">
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star{{ $i <= ($relatedProduct->rate ?? 4) ? ' active' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <a href="{{ route('product.show', $relatedProduct->id) }}" up-target=".main-body" up-preload>
                                            <h6>{{ \Illuminate\Support\Str::limit($relatedProduct->title, 25) }}</h6>
                                        </a>
                                        <h5 class="mb-0">{{ \App\Domain\AppBase\Models\Setting\Currency::format($relatedProduct->default_price) }}</h5>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// ... (Your existing script remains unchanged) ...
document.addEventListener('DOMContentLoaded', function() {
    initProductPage();
});

up.on('up:fragment:inserted', function(event) {
    if (event.target.matches('.main-body') || event.target.closest('.main-body')) {
        setTimeout(initProductPage, 100);
    }
});

// Re-initialize after browser back/forward history navigation (fragment restored from cache)
up.on('up:location:changed', function() {
    setTimeout(initProductPage, 100);
});

// Tracks which product-page sections have already been wired up.
// Stored in JS memory (not on the DOM) so that nodes restored from Unpoly's cache
// on back/forward navigation are treated as new and get their handlers re-attached.
const initializedProductSections = new WeakSet();

function initProductPage() {

    const quantityInput = document.getElementById('product-quantity');
    if (!quantityInput) return;
    if (initializedProductSections.has(quantityInput)) return;
    initializedProductSections.add(quantityInput);

    const stars = document.querySelectorAll('.comment_rate .fa-star');
    const ratingInput = document.getElementById('rating-value');

    if (stars.length > 0 && ratingInput) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-value');
                ratingInput.value = rating;

                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });

            star.addEventListener('mouseover', function() {
                const rating = this.getAttribute('data-value');
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= rating) {
                        s.classList.add('hover');
                    } else {
                        s.classList.remove('hover');
                    }
                });
            });
        });

        document.querySelector('.comment_rate').addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value;
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= currentRating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
                s.classList.remove('hover');
            });
        });
    }

    const shareButtons = document.querySelectorAll('.product-social a');
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.classList.contains('share-facebook') ? 'facebook' :
                        this.classList.contains('share-twitter') ? 'twitter' :
                        this.classList.contains('share-linkedin') ? 'linkedin' : 'whatsapp';

            const url = this.getAttribute('data-url');
            const title = this.getAttribute('data-title') || document.title;

            shareProduct(type, url, title);
        });
    });

    // Initialize Carousels
    initOwlCarousels();
}

function initOwlCarousels() {
    if (typeof $.fn.owlCarousel === 'function') {

        $('#big').owlCarousel({
            items: 1,
            loop: true,
            margin: 10,
            nav: true,
            dots: false,
            autoplay: false,
            navText: ['<i class="fa fa-angle-left arrow-start"></i>', '<i class="fa fa-angle-right arrow-next"></i>'],
            responsive: {
                0: { items: 1 },
                600: { items: 1 },
                1000: { items: 1 }
            }
        });

        $('#thumbs').owlCarousel({
            items: 4,
            loop: false,
            margin: 10,
            nav: false,
            dots: false,
            autoplay: false,
            navText: ['<i class="fa fa-angle-left arrow-start"></i>', '<i class="fa fa-angle-right arrow-next"></i>'],
            responsive: {
                0: { items: 3 },
                600: { items: 4 },
                1000: { items: 4 }
            }
        });

        const big = $('#big');
        const thumbs = $('#thumbs');

        thumbs.on('click', '.owl-item', function() {
            const index = $(this).index();
            big.trigger('to.owl.carousel', [index, 300]);
        });
    }
}

function shareProduct(type, url, title) {
    const shareUrls = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
        twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`,
        linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
        whatsapp: `https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`
    };

    window.open(shareUrls[type], '_blank', 'width=600,height=400');
}

function showMessage(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'info': 'alert-info',
        'warning': 'alert-warning'
    }[type] || 'alert-info';

    const oldAlerts = document.querySelectorAll('.product-alert-message');
    oldAlerts.forEach(alert => alert.remove());

    const isRtl = document.documentElement.getAttribute('dir') === 'rtl' || document.body.classList.contains('rtl');
    const hPos = isRtl ? 'left: 20px; right: auto;' : 'right: 20px; left: auto;';

    const alertHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show product-alert-message" role="alert"
             style="position: fixed; top: 80px; ${hPos} z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', alertHTML);

    setTimeout(() => {
        const alert = document.querySelector('.product-alert-message');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}


document.addEventListener('DOMContentLoaded', function() {
    const tabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });

                target.classList.add('show', 'active');

                tabTriggers.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
});


function closeLoginModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('loginRequiredModal'));
    if (modal) {
        modal.hide();
    }

    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }

    document.body.classList.add('modal-open');
}
</script>



<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true" up-overlay="swp">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginRequiredModalLabel">@lang('ecommerce::ecommerce.login_required')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h4 class="text-muted">@lang('ecommerce::ecommerce.login_to_continue')</h4>
                    <p class="text-muted mt-3">@lang('ecommerce::ecommerce.login_to_add_cart')</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="theme_btn btn-secondary me-2" data-bs-dismiss="modal">
                    @lang('ecommerce::ecommerce.cancel')
                    <span class="theme_btn_eff"></span>
                </button>
                <a href="{{ route('auth.login') }}" class="theme_btn" up-target=".main-body" onclick="closeLoginModal()">
                    @lang('ecommerce::ecommerce.login')
                    <span class="theme_btn_eff"></span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
