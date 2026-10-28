@extends('ecommerce::layout.store-layout')

@php
    $title = static fn($value) => is_array($value) ? ($value[app()->getLocale()] ?? $value['en'] ?? reset($value)) : ($value ?: '');
    $image = static fn($value) => $value ?: asset('assets/images/pro/1.jpg');
    $localizedName = is_array($merchant->company_name ?? null) ? ($merchant->company_name[app()->getLocale()] ?? $merchant->company_name['en'] ?? reset($merchant->company_name)) : ($merchant->company_name ?? $merchant->name);
    $localizedDesc = is_array($merchant->description ?? null) ? ($merchant->description[app()->getLocale()] ?? $merchant->description['en'] ?? reset($merchant->description)) : ($merchant->description ?? '');
    $merchantLogo = $merchant->logo ?: asset('assets/ecommerce/images/logo.png');
    $merchantRating = (float) ($merchant->rate ?? $merchant->rating ?? 0);
    $totalProducts = $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->total() : $products->count();
    // Split the store's own uploads the way visitors expect to see them.
    $attachmentImages = ($attachments ?? collect())->filter->is_image->values();
    $attachmentDocs = ($attachments ?? collect())->reject->is_image->values();
    $fileSize = static function ($bytes) {
        $kb = round(((float) $bytes) / 1024, 0);
        return $kb >= 1024
            ? __('ecommerce::ecommerce.file_size_mb', ['size' => number_format($kb / 1024, 1)])
            : __('ecommerce::ecommerce.file_size_kb', ['size' => number_format($kb)]);
    };
@endphp

@section('title', $localizedName)

@section('active-nav', 'merchants')

@section('page-styles')
    <style>
        .merchant-hero {
            background: #fff;
            border-bottom: 1px solid var(--line);
            padding: 26px 0
        }

        .merchant-head {
            display: flex;
            align-items: center;
            gap: 22px;
            flex-wrap: wrap
        }

        .merchant-logo {
            width: 96px;
            height: 96px;
            border-radius: 14px;
            object-fit: contain;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: var(--shadow)
        }

        .merchant-info {
            flex: 1;
            min-width: 240px
        }

        .merchant-info h1 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 800
        }

        .merchant-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: 12px
        }

        .verified {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #1e8e50;
            background: #eafaf0;
            border: 1px solid rgba(30, 142, 80, .25);
            border-radius: 20px;
            padding: 3px 10px;
            font-weight: 700
        }

        .merchant-desc {
            margin: 10px 0 0;
            color: var(--muted);
            line-height: 1.9;
            max-width: 760px
        }

        .merchant-stats {
            display: flex;
            gap: 30px;
            margin-top: 16px;
            flex-wrap: wrap
        }

        .merchant-stats .stat b {
            display: block;
            font-size: 17px;
            color: var(--gold);
            font-weight: 800
        }

        .merchant-stats .stat span {
            font-size: 11px;
            color: var(--muted)
        }

        .merchant-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0;
            padding: 14px 0 18px;
            background: transparent;
            color: #68717b;
            font-size: 12px;
            direction: inherit
        }

        .merchant-breadcrumb a {
            color: #59636e;
            text-decoration: none;
            transition: color .2s ease
        }

        .merchant-breadcrumb a:hover {
            color: var(--gold)
        }

        .merchant-breadcrumb .breadcrumb-separator {
            color: #b7bdc4
        }

        .merchant-breadcrumb [aria-current="page"] {
            color: #245C8E;
            font-weight: 700
        }

        .merchant-hero {
            margin-bottom: 20px;
            padding: 22px 24px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            box-shadow: var(--shadow)
        }

        .merchant-rating {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #245C8E
        }

        .merchant-rating i {
            color: #EA993E
        }

        .merchant-contact {
            display: inline-flex;
            align-items: center;
            gap: 6px
        }

        /* --- Store attachments --- */
        .attachments {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-top: 22px;
            overflow: hidden
        }

        .attachments-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            background: #fafbfc
        }

        .attachments-head h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 800
        }

        .attachments-head p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8
        }

        .attachments-group {
            padding: 16px 18px
        }

        .attachments-group + .attachments-group {
            border-top: 1px solid var(--line)
        }

        .attachments-group-title {
            margin: 0 0 12px;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .4px
        }

        .attachments-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 14px
        }

        .attachments-image {
            display: block;
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            transition: transform .2s ease, box-shadow .2s ease
        }

        .attachments-image:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .1)
        }

        .attachments-image img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            display: block;
            background: #f6f7f9
        }

        .attachments-image span {
            display: block;
            padding: 8px 10px;
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .attachments-files {
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .attachments-file {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: inherit;
            text-decoration: none;
            transition: border-color .2s ease, background .2s ease
        }

        .attachments-file:hover {
            border-color: var(--gold);
            background: #eef4fa
        }

        .attachments-file .af-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: #f1f3f5;
            color: var(--gold);
            font-size: 16px
        }

        .attachments-file .af-meta {
            flex: 1;
            min-width: 0
        }

        .attachments-file .af-name {
            display: block;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .attachments-file .af-size {
            display: block;
            font-size: 11px;
            color: var(--muted)
        }

        .attachments-file .af-open {
            font-size: 12px;
            font-weight: 700;
            color: var(--gold);
            white-space: nowrap
        }

        .attachments-empty {
            padding: 26px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 13px
        }

        .attachments-empty i {
            font-size: 26px;
            color: #d3d7dc;
            display: block;
            margin-bottom: 8px
        }

        .layout {
            display: grid;
            grid-template-columns: 210px minmax(0, 1fr);
            gap: 22px;
            direction: inherit;
            padding-top: 20px
        }

        .sidebar,
        .results {
            direction: inherit
        }

        .filter-sidebar {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 16px;
            font-weight: 700;
            border-bottom: 1px solid var(--line);
            background: #fafbfc
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 16px;
            color: #5d6269;
            border-bottom: 1px solid #f4f5f7;
            font-size: 12px;
            text-decoration: none
        }

        .filter-option:last-child {
            border-bottom: 0
        }

        .filter-option:hover,
        .filter-option.active {
            color: var(--gold);
            background: #eef4fa;
            font-weight: 700
        }

        .filter-option .count {
            margin-inline-start: auto;
            color: #a7adb4;
            font-size: 11px
        }

        .controls {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 12px 16px;
            margin-bottom: 18px;
            flex-wrap: wrap
        }

        .sort {
            height: 38px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff;
            outline: 0
        }

        .product-count {
            color: var(--muted);
            font-size: 12px
        }

        .products {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            width: 100%;
            margin-bottom: 48px;
            padding-bottom: 8px
        }

        .product {
            min-height: 265px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px 11px;
            background: #fff;
            box-shadow: var(--shadow);
            position: relative;
            display: flex;
            flex-direction: column
        }

        .product-img-wrap {
            position: relative;
            display: block
        }

        .product-image {
            display: block;
            width: 100%;
            height: 130px;
            object-fit: contain
        }

        .product h3 {
            margin: 10px 0 5px;
            font-size: 13px;
            line-height: 1.6;
            height: 42px;
            overflow: hidden
        }

        .product-title-link {
            color: inherit;
            text-decoration: none
        }

        .product-title-link:hover {
            color: var(--gold)
        }

        .rating {
            color: #f0a500;
            font-size: 11px
        }

        .rating span {
            color: var(--muted)
        }

        .price {
            margin-top: 6px;
            font-size: 16px;
            font-weight: 800;
            color: var(--ink)
        }

        .add-cart-form {
            margin-top: auto;
            padding-top: 10px
        }

        .add-cart {
            width: 100%;
            height: 38px;
            border-radius: 5px;
            background: var(--yellow);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px
        }

        .favorite {
            position: absolute;
            top: 9px;
            left: 9px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 0;
            padding: 0;
            cursor: pointer;
            color: #aab1b8;
            font-size: 16px;
            z-index: 10;
        }

        .favorite.is-favorite {
            color: #EA993E
        }

        .pagination {
            padding: 20px;
            text-align: center
        }

        .empty {
            grid-column: 1 / -1;
            -ms-grid-column: 1 / -1;
            width: 100%;
            box-sizing: border-box;
            padding: 60px 20px;
            text-align: center;
            color: var(--muted);
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px
        }

        .empty i {
            font-size: 26px;
            display: block;
            margin-bottom: 10px
        }

        .footer-links a {
            color: #d2d4d6
        }

        @media(max-width:1000px) {
            .products {
                grid-template-columns: repeat(3, minmax(0, 1fr))
            }
        }

        @media(max-width:1000px) {
            .layout {
                grid-template-columns: 180px minmax(0, 1fr)
            }
        }

        @media(max-width:800px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:800px) {
            .layout {
                grid-template-columns: minmax(0, 1fr)
            }
        }

        @media(max-width:800px) {
            .merchant-head {
                flex-direction: column;
                text-align: center
            }
        }

        @media(max-width:800px) {

            .merchant-meta,
            .merchant-stats {
                justify-content: center
            }
        }

        @media(max-width:520px) {
            .products {
                grid-template-columns: minmax(0, 1fr)
            }
        }
    .search-merchant{flex: 1;
            min-width: 180px;
            height: 38px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 6px;
            outline: 0}.search-merchant:focus{border-color: var(--yellow)}
</style>
@endsection

@section('content')
    <div class="merchant-detail">
        <div class="container">
            <nav class="merchant-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('ecommerce.static') }}">@lang('ecommerce::ecommerce.home')</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <a href="{{ route('merchants.index') }}">@lang('ecommerce::ecommerce.merchants_list')</a>
                <span class="breadcrumb-separator" aria-hidden="true">/</span>
                <span aria-current="page">{{ $localizedName }}</span>
            </nav>
            <section class="merchant-hero" aria-labelledby="merchant-name">
                <div class="merchant-head">
                    <img class="merchant-logo" src="{{ $merchantLogo }}" alt="{{ $localizedName }}">
                    <div class="merchant-info">
                        <h1 id="merchant-name">{{ $localizedName }}</h1>
                        <div class="merchant-meta">
                            @if($merchant->is_verified)
                                <span class="verified"><i class="fa fa-check-circle" aria-hidden="true"></i> @lang('ecommerce::ecommerce.verified')</span>
                            @endif
                            <span class="merchant-rating" aria-label="@lang('ecommerce::ecommerce.rating') {{ number_format($merchantRating, 1) }}">
                                <i class="fa fa-star" aria-hidden="true"></i> {{ number_format($merchantRating, 1) }} @lang('ecommerce::ecommerce.rating')
                            </span>
                            <span><i class="fa fa-cube" aria-hidden="true"></i> {{ $totalProducts }} @lang('ecommerce::ecommerce.sales')</span>
                            @if($merchant->phone)
                                <span class="merchant-contact"><i class="fa fa-phone" aria-hidden="true"></i> <span class="num">{{ $merchant->phone }}</span></span>
                            @endif
                            @if($merchant->email)
                                <span class="merchant-contact"><i class="fa fa-envelope-o" aria-hidden="true"></i> <span class="ltr">{{ $merchant->email }}</span></span>
                            @endif
                        </div>
                        @if($localizedDesc)
                            <p class="merchant-desc">{{ $localizedDesc }}</p>
                        @endif
                    </div>
                </div>
            </section>
            <div class="layout">
                <aside class="sidebar">
                    <div class="filter-sidebar">
                        <div class="filter-title"><i class="fa fa-th-large"></i>
                            @lang('ecommerce::ecommerce.store_categories')</div><a
                            class="filter-option {{ request('category', 'all') === 'all' ? 'active' : '' }}"
                            href="{{ route('merchant.show', array_merge(['merchant' => $merchant->id], request()->except(['category', 'page']))) }}"><i
                                class="fa fa-th-large"></i><span>@lang('admin::lang.all')</span><span
                                class="count">{{ $categoryCounts['all'] ?? 0 }}</span></a>@foreach($categories as $category)<a
                                    class="filter-option {{ (string) request('category') === (string) $category->id ? 'active' : '' }}"
                                    href="{{ route('merchant.show', array_merge(['merchant' => $merchant->id, 'category' => $category->id], request()->except(['category', 'page']))) }}"><i
                                        class="fa fa-angle-right arrow-end"></i><span>{{ $title($category->title) }}</span><span
                                class="count">{{ $categoryCounts[$category->id] ?? 0 }}</span></a>@endforeach
                    </div>
                </aside>
                <section class="results">
                    <form class="controls" method="get" action="{{ route('merchant.show', $merchant->id) }}"><input
                            class="search-merchant" type="search" name="q" value="{{ request('q') }}"
                            placeholder="@lang('ecommerce::ecommerce.search_in_store_products')"><input type="hidden" name="category"
                            value="{{ request('category', 'all') }}"><select class="sort" name="sort_by"
                            onchange="this.form.submit()">
                            <option value="latest" {{ request('sort_by', 'latest') === 'latest' ? 'selected' : '' }}>
                                @lang('ecommerce::ecommerce.newest')</option>
                            <option value="best_selling" {{ request('sort_by') === 'best_selling' ? 'selected' : '' }}>
                                @lang('ecommerce::ecommerce.best_sellers')</option>
                            <option value="top_rated" {{ request('sort_by') === 'top_rated' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.sort_top_rated')
                                </option>
                            <option value="price_low" {{ request('sort_by') === 'price_low' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_low_high')
                                </option>
                            <option value="price_high" {{ request('sort_by') === 'price_high' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_high_low')
                                </option>
                        </select><span class="product-count">@lang('ecommerce::ecommerce.showing_products_count', ['count' => $products->count(), 'total' => $totalProducts])</span></form>
                    <div class="products">@forelse($products as $product)
                        <article class="product"><button
                                type="button"
                                class="favorite{{ isset($favoriteIds[$product->id]) ? ' is-favorite' : '' }}"
                                data-product-id="{{ $product->id }}"
                                aria-label="@lang('ecommerce::ecommerce.wishlist')"><i
                                    class="{{ isset($favoriteIds[$product->id]) ? 'fa fa-heart' : 'ti-heart' }}"></i></button><a
                                class="product-img-wrap" href="{{ route('product.static', $product->id) }}"><img
                                    class="product-image" src="{{ $image($product->image_path) }}"
                                    alt="{{ $title($product->title) }}" loading="lazy">@if(!$product->isInStock())<span
                                    class="product-stock-badge">@lang('ecommerce::ecommerce.out_of_stock')</span>@endif</a>
                            <h3><a class="product-title-link" href="{{ route('product.static', $product->id) }}">{{ \Illuminate\Support\Str::limit($title($product->title), 38) }}</a></h3>
                            <div class="rating">★★★★★
                                <span>({{ number_format((float) ($product->rating ?? 4.8), 1) }})</span>
                            </div>
                            <div class="price">
                                {{ \App\Domain\AppBase\Models\Setting\Currency::format((float) ($product->default_price ?? 0)) }}
                            </div>
                            <form class="add-cart-form" action="{{ route('cart.add') }}" method="POST">@csrf<input
                                    type="hidden" name="item_id" value="{{ $product->id }}"><input type="hidden" name="qty"
                                    value="1">@if(!$product->isInStock())<button type="button" class="add-cart" disabled
                                    style="background:#e5e7eb !important;color:#9ca3af !important;cursor:not-allowed">@lang('ecommerce::ecommerce.out_of_stock')</button>@else<button
                                        type="submit" class="add-cart"><i class="ti-shopping-cart"></i>
                                    @lang('ecommerce::ecommerce.add_to_cart')</button>@endif</form>
                    </article>@empty<div class="empty"><i
                            class="fa fa-search"></i>@lang('ecommerce::ecommerce.products.no_products_found')</div>
                        @endforelse
                    </div>
                    @if($products->hasPages())
                    <div class="pagination">{!! $products->links('pagination::bootstrap-4') !!}</div>@endif
                </section>
            </div>

            {{--
                Store attachments: read-only list of files the merchant uploaded.
                HIDDEN (commented out) - section class="attachments"
                <section class="attachments">
                    <div class="attachments-head">
                        <h2>@lang('ecommerce::ecommerce.store_attachments')</h2>
                        <p>@lang('ecommerce::ecommerce.store_attachments_desc')</p>
                    </div>

                    @if($attachments->isEmpty())
                        <div class="attachments-empty"><i class="ti-folder"></i>
                            @lang('ecommerce::ecommerce.store_no_attachments')
                        </div>
                    @else
                        @if($attachmentImages->isNotEmpty())
                            <div class="attachments-group">
                                <h3 class="attachments-group-title">@lang('ecommerce::ecommerce.store_attachment_images')</h3>
                                <div class="attachments-images">
                                    @foreach($attachmentImages as $file)
                                        <a class="attachments-image" href="{{ $file->public_url }}" target="_blank" rel="noopener">
                                            <img src="{{ $file->public_url }}" alt="{{ $file->display_name }}" loading="lazy">
                                            <span>{{ $file->display_name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($attachmentDocs->isNotEmpty())
                            <div class="attachments-group">
                                <h3 class="attachments-group-title">@lang('ecommerce::ecommerce.store_attachment_files')</h3>
                                <div class="attachments-files">
                                    @foreach($attachmentDocs as $file)
                                        <a class="attachments-file" href="{{ $file->public_url }}" target="_blank" rel="noopener">
                                            <span class="af-icon"><i class="ti-file"></i></span>
                                            <span class="af-meta">
                                                <span class="af-name">{{ $file->display_name }}</span>
                                                @if($file->size)<span class="af-size">{{ $fileSize($file->size) }}</span>@endif
                                            </span>
                                            <span class="af-open">@lang('ecommerce::ecommerce.open_file')</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </section>
            --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.add-cart-form').forEach(function (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    var btn = form.querySelector('button');
                    var origHtml = btn ? btn.innerHTML : '';
                    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> ' + @json(__('ecommerce::ecommerce.product.adding')); }
                    try {
                        if (!window.submitCartForm) throw new Error(@json(__('ecommerce::ecommerce.cart_service_unavailable')));
                        var result = await window.submitCartForm(form, {});
                        if (result && (result.login || result.cancelled)) return;
                        if (btn) {
                            btn.innerHTML = '✓ ' + @json(__('ecommerce::ecommerce.added'));
                            btn.style.background = '#27ae60';
                            btn.style.color = '#fff';
                            setTimeout(function () {
                                btn.disabled = false;
                                btn.innerHTML = origHtml;
                                btn.style.background = '';
                                btn.style.color = '';
                            }, 2500);
                        }
                    } catch (err) {
                        alert(err.message || @json(__('ecommerce::ecommerce.unexpected_error')));
                        if (btn) { btn.disabled = false; btn.innerHTML = origHtml; }
                    }
                });
            });

            document.addEventListener('click', async function (event) {
                var favorite = event.target.closest('.favorite');
                if (!favorite) return;
                event.preventDefault();
                event.stopPropagation();
                var itemId = favorite.dataset.productId;
                var token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!itemId || !token || favorite.dataset.loading === '1') return;
                favorite.dataset.loading = '1';
                favorite.disabled = true;
                try {
                    var body = new FormData();
                    body.append('_token', token);
                    body.append('product_id', itemId);
                    var response = await fetch('{{ route('wishlist.add') }}', { method: 'POST', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, body: body });
                    var data = await response.json().catch(function () { return null; });
                    if ((!data && response.url.includes('/login')) || (data && data.login)) {
                        window.location.href = data && data.redirect ? data.redirect : response.url;
                        return;
                    }
                    var isFavorite = data && data.status === 'added';
                    favorite.classList.toggle('is-favorite', isFavorite);
                    favorite.innerHTML = isFavorite ? '<i class="fa fa-heart"></i>' : '<i class="ti-heart"></i>';
                    if (typeof window.updateWishlistBadge === 'function' && data && typeof data.count !== 'undefined') {
                        window.updateWishlistBadge(data.count);
                    }
                } finally {
                    delete favorite.dataset.loading;
                    favorite.disabled = false;
                }
            });
        });
    </script>
@endsection