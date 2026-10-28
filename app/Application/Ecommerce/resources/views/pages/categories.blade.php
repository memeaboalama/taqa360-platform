@extends('ecommerce::layout.store-layout')

@php
    $title = static fn($value) => is_array($value) ? ($value[app()->getLocale()] ?? $value['en'] ?? reset($value)) : ($value ?: '');
    $image = static fn($value) => $value ?: asset('assets/images/pro/1.jpg');
    $currencySymbol = \App\Domain\AppBase\Models\Setting\Currency::symbol();
    $priceRanges = ['hundred' => '10 - 100 ' . $currencySymbol, 'twohundred' => '100 - 200 ' . $currencySymbol, 'threehundred' => '200 - 300 ' . $currencySymbol, 'fourhundredabove' => '400+ ' . $currencySymbol];
@endphp

@section('title', $currentCategory->title ?? __('ecommerce::ecommerce.categories.categories'))

@section('active-nav', 'categories')

@section('page-styles')
    <style>
        main {
            padding: 35px 0 55px
        }

        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 21px
        }

        .page-title h1 {
            font-size: 25px;
            margin: 0;
            border-bottom: 3px solid var(--yellow);
            padding-bottom: 5px
        }

        .page-title span {
            font-size: 11px;
            color: #9aa1a8
        }

        .category-strip {
            display: grid;
            grid-template-columns: repeat(8, minmax(0, 1fr));
            min-height: 116px;
            padding: 5px 8px;
            border: 1px solid var(--line);
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 28px
        }

        .category-card {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 6px;
            border-left: 1px solid var(--line);
            color: #59616a;
            background: #fff;
            text-align: center;
            text-decoration: none
        }

        .category-strip .category-card,
        .category-strip .category-card:visited {
            color: #59616a;
            text-decoration: none
        }

        .category-card.active,
        .category-card:hover {
            color: #306FAA;
            background: #eef4fa;
            border: 1px solid var(--yellow);
            border-radius: 7px;
            margin: 0 2px
        }

        .category-strip .category-card.active,
        .category-strip .category-card:hover,
        .category-strip .category-card:focus {
            color: #306FAA;
            text-decoration: none
        }

        .category-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #efefff;
            color: #6b62cc
        }

        .category-card:nth-child(2n) .category-icon {
            background: #e8f7ef;
            color: #42a579
        }

        .category-card:nth-child(3n) .category-icon {
            background: #fff0ef;
            color: #ee766d
        }

        .category-card span {
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%
        }

        .layout {
            display: grid;
            grid-template-columns: 190px minmax(0, 1fr);
            gap: 22px;
            direction: inherit
        }

        .sidebar,
        .products-area {
            direction: inherit
        }

        .box {
            border: 1px solid var(--line);
            border-radius: 9px;
            box-shadow: var(--shadow);
            padding: 14px 13px;
            margin-bottom: 15px
        }

        .box-title {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #f0f1f3;
            padding-bottom: 10px;
            margin-bottom: 7px;
            font-size: 12px;
            font-weight: 700
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            color: #636b75;
            font-size: 10px
        }

        .filter-row input {
            accent-color: var(--yellow)
        }

        .subcats {
            list-style: none;
            padding: 0;
            margin: 0
        }

        .subcats li {
            display: flex;
            justify-content: space-between;
            padding: 7px 5px;
            border-radius: 4px;
            color: #6b727a;
            font-size: 10px
        }

        .subcats li.active,
        .subcats li:hover {
            background: #eef4fa;
            color: #306FAA
        }

        .subcats a,
        .subcats a:visited {
            flex: 1;
            min-width: 0;
            color: #59616a;
            text-decoration: none;
            overflow-wrap: anywhere
        }

        .subcats li.active a,
        .subcats a:hover,
        .subcats a:focus {
            color: #306FAA;
            text-decoration: none
        }

        .count {
            color: #9ca3aa;
            font-size: 9px;
            flex-shrink: 0;
            margin-inline-start: 8px
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px
        }

        .toolbar h2 {
            font-size: 15px;
            margin: 0;
            padding-right: 11px;
            border-right: 3px solid var(--yellow)
        }

        .sort {
            border: 1px solid var(--line);
            border-radius: 5px;
            padding: 7px 10px;
            color: #777;
            font-size: 10px;
            background: #fff
        }

        .products {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px
        }

        .product {
            min-height: 265px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px 11px;
            box-shadow: var(--shadow);
            position: relative
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
            border: none;
            padding: 0;
            cursor: pointer;
            color: #aab1b8;
            font-size: 16px;
            z-index: 10;
        }

        .product-image {
            display: block;
            width: 100%;
            height: 130px;
            object-fit: contain
        }

        .product h3 {
            font-size: 11px;
            line-height: 1.7;
            margin: 0 0 3px;
            min-height: 38px
        }

        .rating {
            color: #EA993E;
            font-size: 13px
        }

        .rating span {
            font-size: 9px;
            color: #777
        }

        .price {
            font-size: 12px;
            font-weight: 700;
            text-align: start
        }

        .add-cart {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 100%;
            height: 30px;
            border-radius: 17px;
            background: var(--yellow);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            margin-top: 8px
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 7px;
            margin-top: 28px;
            direction: ltr
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 29px;
            border: 1px solid var(--line);
            border-radius: 4px;
            padding: 0 9px;
            font-size: 10px
        }

        .pagination .active,
        .pagination a:hover {
            background: var(--yellow);
            border-color: var(--yellow)
        }

        .empty {
            grid-column: 1/-1;
            text-align: center;
            padding: 50px;
            color: #9299a0
        }

        .footer-links a {
            color: #d2d4d6
        }

        @media(max-width:900px) {
            .layout {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:900px) {
            .sidebar {
                display: grid;
                grid-template-columns: 1fr 1fr
            }
        }

        @media(max-width:900px) {
            .box {
                margin: 0
            }
        }

        @media(max-width:900px) {
            .category-strip {
                display: flex;
                overflow: auto
            }
        }

        @media(max-width:900px) {
            .category-card {
                min-width: 104px
            }
        }

        @media(max-width:900px) {
            .products {
                grid-template-columns: repeat(3, minmax(0, 1fr))
            }
        }

        @media(max-width:560px) {
            .page-title h1 {
                font-size: 21px
            }
        }

        @media(max-width:560px) {
            .page-title span {
                font-size: 9px
            }
        }

        @media(max-width:560px) {
            .sidebar {
                display: block
            }
        }

        @media(max-width:560px) {
            .box {
                margin-bottom: 10px
            }
        }

        @media(max-width:560px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 9px
            }
        }

        @media(max-width:560px) {
            .product {
                min-height: 225px;
                padding: 8px
            }
        }

        @media(max-width:560px) {
            .product-image {
                height: 100px
            }
        }

        @media(max-width:560px) {
            .product h3 {
                font-size: 9px
            }
        }

        @media(max-width:560px) {
            .add-cart {
                height: 27px;
                font-size: 9px
            }
        }

        .category-strip {
            overflow: visible;
            grid-auto-rows: minmax(106px, auto);
        }

        .favorite.is-favorite {
            color: #EA993E
        }

        @media (max-width: 900px) {
            .category-strip {
                overflow-x: auto;
                overflow-y: hidden;
                grid-auto-rows: auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container categories-page">
        <div class="page-title">
            <h1>{{ $currentCategory->title ?? __('ecommerce::ecommerce.categories.categories') }}</h1>
            <span>{{ $currentCategory->description ?? __('ecommerce::ecommerce.categories_desc') }}</span>
        </div>
        <section class="category-strip"><a
                class="category-card {{ !$activeMainId && !$activeSubId && !$activeChildId ? 'active' : '' }}"
                href="{{ route('categories.index') }}"><i class="category-icon fa fa-th"></i><span>@lang('ecommerce::ecommerce.categories.all_categories')
                    </span></a>@foreach(collect($subCategories ?? []) as $category)<a
                        class="category-card {{ $activeSubId == $category->id ? 'active' : '' }}"
                        href="{{ route('categories.index', ['sub_category' => $category->id, 'main_category' => $activeMainId ?? 'all']) }}"><i
                    class="category-icon fa {{ $loop->odd ? 'fa-graduation-cap' : 'fa-pencil' }}"></i><span>{{ $title($category->title) }}</span></a>@endforeach
        </section>
        <div class="layout">
            <aside class="sidebar">
                <form id="filter-form" method="get" action="{{ route('categories.index') }}"><input type="hidden"
                        name="main_category" value="{{ $activeMainId ?? 'all' }}"><input type="hidden" name="sub_category"
                        value="{{ $activeSubId ?? 'all' }}"><input type="hidden" name="child_category"
                        value="{{ $activeChildId ?? 'all' }}">
                    <div class="box">
                        <div class="box-title"><span>@lang('ecommerce::ecommerce.categories.categories')</span><i class="fa fa-angle-up"></i></div>
                        <ul class="subcats">
                            <li class="{{ !$activeChildId ? 'active' : '' }}"><a
                                    href="{{ request()->fullUrlWithQuery(['child_category' => 'all']) }}">@lang('ecommerce::ecommerce.categories.all_products')
                                    </a><span class="count">{{ $products->total() }}</span></li>
                            @foreach(collect($childCategories ?? []) as $child)
                                <li class="{{ $activeChildId == $child->id ? 'active' : '' }}"><a
                                        href="{{ request()->fullUrlWithQuery(['child_category' => $child->id]) }}">{{ $title($child->title) }}</a><span
                            class="count">{{ $child->items_count }}</span></li>@endforeach
                        </ul>
                    </div>
                    <div class="box">
                        <div class="box-title"><span>@lang('ecommerce::ecommerce.categories.price')</span><i class="fa fa-filter"></i></div>
                        @foreach($priceRanges as $key => $label)<label class="filter-row"><span>{{ $label }}</span><input
                        type="checkbox" name="price_ranges[]" value="{{ $key }}" {{ in_array($key, (array) request('price_ranges', []), true) ? 'checked' : '' }}></label>@endforeach<button
                            class="add-cart" type="submit">@lang('ecommerce::ecommerce.apply_filter')</button>
                    </div>
                </form>
            </aside>
            <section class="products-area">
                <div class="toolbar">
                    <h2>@lang('ecommerce::ecommerce.items_label')</h2><select class="sort" name="sort_by" form="filter-form"
                        onchange="document.getElementById('filter-form').submit()">
                        <option value="">@lang('ecommerce::ecommerce.sort_default')</option>
                        <option value="price_asc" {{ request('sort_by') === 'price_asc' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_lowest_first')
                        </option>
                        <option value="price_desc" {{ request('sort_by') === 'price_desc' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_highest_first')
                        </option>
                    </select>
                </div>
                <div class="products">@forelse($products as $product)
                    <article class="product"><button
                            type="button"
                            class="favorite{{ isset($favoriteIds[$product->id]) ? ' is-favorite' : '' }}"
                            data-product-id="{{ $product->id }}"
                            aria-label="@lang('ecommerce::ecommerce.add_to_wishlist')"><i
                                class="{{ isset($favoriteIds[$product->id]) ? 'fa fa-heart' : 'ti-heart' }}"></i></button><a
                            class="product-img-wrap" href="{{ route('product.static', $product->id) }}"><img
                                class="product-image" src="{{ $image($product->image_path) }}"
                                alt="{{ $title($product->title) }}">@if(!$product->isInStock())<span
                                class="product-stock-badge">@lang('ecommerce::ecommerce.out_of_stock')</span>@endif</a>
                        <h3><a href="{{ route('product.static', $product->id) }}" style="color:inherit;text-decoration:none">{{ Str::limit($title($product->title), 38) }}</a></h3>
                        <div class="rating">★★★★★ <span>({{ number_format((float) ($product->rating ?? 4.8), 1) }})</span>
                        </div>
                        <div class="price">
                            {{ \App\Domain\AppBase\Models\Setting\Currency::format((float) ($product->price ?? $product->default_price ?? 0)) }}
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST" class="add-cart-form" style="margin-top:8px;">
                            @csrf<input type="hidden" name="item_id" value="{{ $product->id }}"><input type="hidden"
                                name="qty" value="1">@if(!$product->isInStock())<button type="button" class="add-cart"
                                    disabled
                                style="background:#e5e7eb !important;color:#9ca3af !important;cursor:not-allowed">@lang('ecommerce::ecommerce.out_of_stock')</button>@else<button
                                type="submit" class="add-cart"><i class="ti-shopping-cart"></i> @lang('ecommerce::ecommerce.add_to_cart')</button>@endif
                        </form>
                </article>@empty<div class="empty"><i class="fa fa-search"></i>@lang('ecommerce::ecommerce.no_matching_products')</div>@endforelse
                </div>@if($products->hasPages())
                    <div class="pagination">@if(!$products->onFirstPage())<a
                    href="{{ $products->previousPageUrl() }}">@lang('ecommerce::ecommerce.previous')</a>@else<span>@lang('ecommerce::ecommerce.previous')</span>@endif{!! $products->links('pagination::bootstrap-4') !!}@if($products->hasMorePages())<a
                href="{{ $products->nextPageUrl() }}">@lang('ecommerce::ecommerce.next')</a>@else<span>@lang('ecommerce::ecommerce.next')</span>@endif</div>@endif
            </section>
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
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> ' + @json(__('ecommerce::ecommerce.product.adding'));
                    }
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
        });
    </script>
    <script>
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
    </script>
@endsection