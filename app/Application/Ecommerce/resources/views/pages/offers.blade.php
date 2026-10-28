@extends('ecommerce::layout.store-layout')

@php
    $title = static fn($value) => is_array($value) ? ($value[app()->getLocale()] ?? $value['en'] ?? reset($value)) : ($value ?: '');
    $image = static fn($value) => $value ?: asset('assets/images/pro/1.jpg');
    $currencySymbol = \App\Domain\AppBase\Models\Setting\Currency::symbol();
@endphp

@section('title', __('ecommerce::ecommerce.special_offers'))

@section('active-nav', 'offers')

@section('page-styles')
    <style>
        :root {
            --fire-red: #ff4747;
            --fire-orange: #ff7a00;
            --badge-green: #2ecc71;
        }

        main {
            padding: 24px 0 60px;
        }

        /* --- Hero Section --- */
        .offers-hero {
            position: relative;
            background: linear-gradient(135deg, #1f252d 0%, #2c3440 60%, #3a4554 100%);
            border-radius: 14px;
            padding: 35px 30px;
            color: #fff;
            margin-bottom: 28px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .offers-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            left: -60px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(234, 153, 62, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .offers-hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 71, 71, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 25px;
        }

        .hero-text {
            max-width: 580px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(234, 153, 62, 0.18);
            border: 1px solid rgba(234, 153, 62, 0.45);
            color: var(--yellow);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 12px;
            letter-spacing: 0.3px;
        }

        .hero-badge i {
            color: #306FAA;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .offers-hero h1 {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.35;
            margin: 0 0 10px;
            color: #ffffff;
        }

        .offers-hero h1 span {
            color: var(--yellow);
        }

        .offers-hero p {
            color: #cbd3dc;
            font-size: 13px;
            line-height: 1.8;
            margin: 0;
        }

        .countdown-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 16px 20px;
            text-align: center;
            min-width: 270px;
        }

        .countdown-title {
            font-size: 11px;
            color: #EA993E;
            margin-bottom: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .timer-boxes {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            direction: ltr;
        }

        .timer-box {
            background: #15191f;
            border-radius: 8px;
            padding: 8px 10px;
            min-width: 52px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(234, 153, 62, 0.25);
        }

        .timer-val {
            font-size: 19px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            font-family: monospace;
        }

        .timer-unit {
            font-size: 9px;
            color: #9aa1a8;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .timer-separator {
            font-size: 18px;
            font-weight: bold;
            color: var(--yellow);
        }

        /* --- Vouchers Section --- */
        .vouchers-section {
            margin-bottom: 30px;
        }

        .section-header-compact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .section-header-compact h2 {
            font-size: 15px;
            margin: 0;
            padding-inline-start: 11px;
            border-inline-start: 3px solid var(--yellow);
            font-weight: 700;
            color: var(--ink);
        }

        .section-header-compact span {
            font-size: 11px;
            color: #7d848d;
        }

        .vouchers-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .voucher-card {
            position: relative;
            background: #fff;
            border: 1px dashed #d5d9df;
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .voucher-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
            border-color: var(--yellow);
        }

        .voucher-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
        }

        .voucher-badge {
            background: #fff5d6;
            color: #b77900;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .voucher-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 4px;
            line-height: 1.4;
        }

        .voucher-sub {
            font-size: 10px;
            color: #7b828c;
            margin: 0;
            line-height: 1.4;
        }

        .voucher-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 10px;
            padding-top: 9px;
            border-top: 1px dashed #edf0f3;
        }

        .voucher-code {
            font-family: monospace;
            font-size: 12px;
            font-weight: 700;
            color: #272c35;
            background: #f4f6f8;
            padding: 4px 8px;
            border-radius: 5px;
            letter-spacing: 0.5px;
        }

        .copy-btn {
            background: var(--yellow);
            color: #4b3b00;
            border: none;
            padding: 5px 11px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .copy-btn:hover {
            background: var(--yellow-deep);
            color: #2b2200;
        }

        .copy-btn.copied {
            background: var(--badge-green);
            color: #fff;
        }

        /* --- Filter Strip & Pills --- */
        .category-strip {
            display: grid;
            grid-template-columns: repeat(8, minmax(0, 1fr));
            min-height: 90px;
            padding: 6px 8px;
            border: 1px solid var(--line);
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 22px;
            background: #fff;
        }

        .category-card {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 8px 4px;
            border-inline-end: 1px solid var(--line);
            color: #59616a;
            background: #fff;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }

        .category-card:last-child {
            border-inline-end: 0;
        }

        .category-card.active,
        .category-card:hover {
            color: #a86c00;
            background: #eef4fa;
            border: 1px solid var(--yellow);
            border-radius: 7px;
            margin: 0 2px;
            font-weight: 700;
        }

        .category-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #eef4fa;
            color: #e59c00;
            font-size: 13px;
        }

        .category-card span {
            font-size: 11px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .category-count {
            font-size: 9px;
            color: #9aa1a8;
        }

        /* --- Toolbar & Discount Pills --- */
        .offers-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 18px;
            padding: 12px 16px;
            background: #fafbfc;
            border: 1px solid var(--line);
            border-radius: 8px;
        }

        .discount-pills {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            color: #5a626c;
            background: #fff;
            border: 1px solid #dce0e5;
            transition: all 0.18s ease;
        }

        .pill-btn:hover {
            border-color: var(--yellow);
            color: #b77900;
        }

        .pill-btn.active {
            background: var(--yellow);
            border-color: var(--yellow);
            color: #3b2f00;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(234, 153, 62, 0.35);
        }

        .pill-btn.pill-flash {
            border-color: #ffd2cc;
            color: #d93826;
        }

        .pill-btn.pill-flash.active {
            background: #e74c3c;
            border-color: #e74c3c;
            color: #fff;
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.35);
        }

        .toolbar-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-select {
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 11px;
            color: #4b525b;
            background: #fff;
            outline: none;
            cursor: pointer;
        }

        /* --- Products Grid --- */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .product-offer-card {
            position: relative;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
        }

        .product-offer-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(29, 37, 48, 0.08);
            border-color: #EA993E;
        }

        .offer-ribbon {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #e74c3c, #ff6b57);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 5px;
            z-index: 3;
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);
            display: flex;
            align-items: center;
            gap: 3px;
        }

        [dir="ltr"] .offer-ribbon {
            right: auto;
            left: 10px;
        }

        .favorite-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #fff;
            color: #9aa1a8;
            border: 1px solid #edf0f3;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: grid;
            place-items: center;
            font-size: 14px;
            z-index: 3;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        [dir="ltr"] .favorite-btn {
            left: auto;
            right: 10px;
        }

        .favorite-btn:hover,
        .favorite-btn.is-favorite {
            color: #e74c3c;
            border-color: #ffd3cc;
            background: #fff6f5;
        }

        .card-img-wrap {
            position: relative;
            display: block;
            width: 100%;
            height: 145px;
            margin-bottom: 8px;
            overflow: hidden;
            border-radius: 6px;
            background: #fbfbfb;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-offer-card:hover .card-img {
            transform: scale(1.05);
        }

        .time-badge {
            position: absolute;
            bottom: 4px;
            left: 4px;
            right: 4px;
            background: rgba(32, 37, 45, 0.85);
            backdrop-filter: blur(4px);
            color: #EA993E;
            font-size: 9px;
            font-weight: 700;
            padding: 3px 6px;
            border-radius: 4px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .card-merchant {
            font-size: 10px;
            color: #8c939d;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-merchant a {
            color: inherit;
            text-decoration: none;
        }

        .card-title {
            font-size: 12px;
            font-weight: 700;
            line-height: 1.5;
            color: var(--ink);
            margin: 0 0 6px;
            min-height: 36px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-rating {
            font-size: 12px;
            color: #EA993E;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            direction: ltr;
            justify-content: flex-end;
        }

        [dir="ltr"] .card-rating {
            justify-content: flex-start;
        }

        .card-rating span {
            font-size: 9px;
            color: #838a93;
        }

        .card-price-area {
            margin-top: auto;
            padding-top: 6px;
            border-top: 1px solid #f2f4f7;
        }

        .price-row {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 3px;
        }

        .current-price {
            font-size: 15px;
            font-weight: 800;
            color: #e74c3c;
        }

        .old-price {
            font-size: 11px;
            color: #9ea5ae;
            text-decoration: line-through;
        }

        .save-tag {
            font-size: 9px;
            color: #27ae60;
            font-weight: 700;
            background: #eefcf3;
            padding: 1px 5px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 8px;
        }

        .add-cart-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            width: 100%;
            height: 32px;
            border-radius: 16px;
            background: var(--yellow);
            color: #4b3b00;
            font-size: 11px;
            font-weight: 700;
            border: 0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .add-cart-btn:hover {
            background: var(--yellow-deep);
            color: #2b2200;
            box-shadow: 0 3px 8px rgba(234, 153, 62, 0.4);
        }

        /* --- Empty State --- */
        .empty-offers {
            grid-column: 1 / -1;
            text-align: center;
            padding: 55px 20px;
            background: #fafbfc;
            border: 1px dashed var(--line);
            border-radius: 12px;
        }

        .empty-offers i {
            font-size: 42px;
            color: #cbd2d9;
            margin-bottom: 12px;
        }

        .empty-offers h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 6px;
        }

        .empty-offers p {
            font-size: 12px;
            color: #7d848e;
            margin: 0 0 16px;
        }

        /* --- Pagination --- */
        .pagination-wrap {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 32px;
            direction: ltr;
        }

        .pagination-wrap a,
        .pagination-wrap span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 0 10px;
            font-size: 11px;
            color: #555;
            background: #fff;
            text-decoration: none;
        }

        .pagination-wrap .active,
        .pagination-wrap a:hover {
            background: var(--yellow);
            border-color: var(--yellow);
            color: #2e2400;
            font-weight: 700;
        }

        /* --- Toast Notification --- */
        #toast-notice {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #1f252d;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            z-index: 9999;
            display: none;
            align-items: center;
            gap: 8px;
            border-inline-start: 4px solid var(--yellow);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .vouchers-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .products-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
            .category-strip {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .offers-hero {
                padding: 22px 18px;
            }
            .hero-inner {
                flex-direction: column;
                align-items: stretch;
            }
            .countdown-card {
                width: 100%;
            }
            .category-strip {
                display: flex;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            .category-card {
                min-width: 105px;
            }
            .products-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px;
            }
            .vouchers-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        {{-- 1. Hero Promotional Campaign Banner --}}
        <section class="offers-hero">
            <div class="hero-inner">
                <div class="hero-text">
                    <div class="hero-badge">
                        <i class="fa fa-bolt"></i> @lang('ecommerce::ecommerce.flash_sale')
                    </div>
                    <h1>@lang('ecommerce::ecommerce.special_offers_hero_title')</h1>
                    <p>@lang('ecommerce::ecommerce.special_offers_hero_desc')</p>
                </div>

                <div class="countdown-card">
                    <div class="countdown-title">
                        <i class="fa fa-clock-o"></i> @lang('ecommerce::ecommerce.ends_in')
                    </div>
                    <div class="timer-boxes" id="main-countdown">
                        <div class="timer-box">
                            <div class="timer-val" id="timer-days">02</div>
                            <div class="timer-unit">@lang('ecommerce::ecommerce.days')</div>
                        </div>
                        <div class="timer-separator">:</div>
                        <div class="timer-box">
                            <div class="timer-val" id="timer-hours">14</div>
                            <div class="timer-unit">@lang('ecommerce::ecommerce.hours')</div>
                        </div>
                        <div class="timer-separator">:</div>
                        <div class="timer-box">
                            <div class="timer-val" id="timer-mins">35</div>
                            <div class="timer-unit">@lang('ecommerce::ecommerce.mins')</div>
                        </div>
                        <div class="timer-separator">:</div>
                        <div class="timer-box">
                            <div class="timer-val" id="timer-secs">42</div>
                            <div class="timer-unit">{{ app()->getLocale() === 'ar' ? 'ثانية' : 'Secs' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 2. Exclusive Coupons / Vouchers Section --}}
        @if(!empty($vouchers))
            <section class="vouchers-section">
                <div class="section-header-compact">
                    <h2>@lang('ecommerce::ecommerce.exclusive_vouchers')</h2>
                    <span>@lang('ecommerce::ecommerce.exclusive_vouchers_desc')</span>
                </div>
                <div class="vouchers-grid">
                    @foreach($vouchers as $voucher)
                        <div class="voucher-card">
                            <div class="voucher-top">
                                <div>
                                    <h3 class="voucher-title">{{ $voucher['title'] }}</h3>
                                    <p class="voucher-sub">{{ $voucher['subtitle'] }}</p>
                                </div>
                                <span class="voucher-badge">{{ $voucher['badge'] }}</span>
                            </div>
                            <div class="voucher-bottom">
                                <span class="voucher-code">{{ $voucher['code'] }}</span>
                                <button type="button" class="copy-btn" onclick="copyVoucherCode('{{ $voucher['code'] }}', this)">
                                    <i class="fa fa-copy"></i> @lang('ecommerce::ecommerce.copy_code')
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- 3. Category Filter Strip --}}
        @if($categories->isNotEmpty())
            <div class="category-strip">
                <a class="category-card {{ $activeCategory === 'all' ? 'active' : '' }}"
                   href="{{ route('offers.index', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}">
                    <div class="category-icon"><i class="fa fa-th-large"></i></div>
                    <span>@lang('ecommerce::ecommerce.all_offers')</span>
                </a>
                @foreach($categories as $category)
                    <a class="category-card {{ (string)$activeCategory === (string)$category->id ? 'active' : '' }}"
                       href="{{ route('offers.index', array_merge(request()->except('category', 'page'), ['category' => $category->id])) }}">
                        <div class="category-icon">
                            @if($category->icon)
                                <i class="{{ $category->icon }}"></i>
                            @else
                                <i class="fa fa-tag"></i>
                            @endif
                        </div>
                        <span>{{ $title($category->title) }}</span>
                        <div class="category-count">({{ $category->items_count }})</div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- 4. Toolbar: Discount Range Pills & Sort Dropdown --}}
        <div class="offers-toolbar">
            <div class="discount-pills">
                <a class="pill-btn {{ $activeDiscount === 'all' ? 'active' : '' }}"
                   href="{{ route('offers.index', array_merge(request()->except('discount_range', 'page'), ['discount_range' => 'all'])) }}">
                    @lang('ecommerce::ecommerce.all_offers')
                </a>
                <a class="pill-btn pill-flash {{ $activeDiscount === 'flash' ? 'active' : '' }}"
                   href="{{ route('offers.index', array_merge(request()->except('discount_range', 'page'), ['discount_range' => 'flash'])) }}">
                    <i class="fa fa-bolt"></i> @lang('ecommerce::ecommerce.flash_sale')
                </a>
                <a class="pill-btn {{ $activeDiscount === '50_plus' ? 'active' : '' }}"
                   href="{{ route('offers.index', array_merge(request()->except('discount_range', 'page'), ['discount_range' => '50_plus'])) }}">
                    @lang('ecommerce::ecommerce.discounts_50_plus')
                </a>
                <a class="pill-btn {{ $activeDiscount === '30_to_50' ? 'active' : '' }}"
                   href="{{ route('offers.index', array_merge(request()->except('discount_range', 'page'), ['discount_range' => '30_to_50'])) }}">
                    @lang('ecommerce::ecommerce.discounts_30_to_50')
                </a>
                <a class="pill-btn {{ $activeDiscount === '10_to_30' ? 'active' : '' }}"
                   href="{{ route('offers.index', array_merge(request()->except('discount_range', 'page'), ['discount_range' => '10_to_30'])) }}">
                    @lang('ecommerce::ecommerce.discounts_10_to_30')
                </a>
            </div>

            <form method="GET" action="{{ route('offers.index') }}" class="toolbar-controls" id="offers-sort-form">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('discount_range'))
                    <input type="hidden" name="discount_range" value="{{ request('discount_range') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <label for="sort_by" class="d-none">@lang('ecommerce::ecommerce.sort_by_label')</label>
                <select name="sort_by" id="sort_by" class="sort-select" onchange="document.getElementById('offers-sort-form').submit()">
                    <option value="discount_desc" {{ $activeSort === 'discount_desc' ? 'selected' : '' }}>
                        🔥 @lang('ecommerce::ecommerce.sort_discount_highest')
                    </option>
                    <option value="price_asc" {{ $activeSort === 'price_asc' ? 'selected' : '' }}>
                        @lang('ecommerce::ecommerce.price_lowest_first')
                    </option>
                    <option value="price_desc" {{ $activeSort === 'price_desc' ? 'selected' : '' }}>
                        @lang('ecommerce::ecommerce.price_highest_first')
                    </option>
                    <option value="latest" {{ $activeSort === 'latest' ? 'selected' : '' }}>
                        @lang('ecommerce::ecommerce.sort_default')
                    </option>
                </select>
            </form>
        </div>

        {{-- 5. Products Offers Grid --}}
        <section class="products-grid">
            @forelse($products as $product)
                <article class="product-offer-card">
                    {{-- Percentage Discount Ribbon — from Item::getDiscountPercentAttribute() --}}
                    @php $discountPct = $product->discount_percent; @endphp
                    <div class="offer-ribbon">
                        <i class="fa fa-percent" style="font-size: 9px;"></i>
                        {{ $discountPct }}% @lang('ecommerce::ecommerce.off')
                    </div>

                    {{-- Wishlist / Favorite Button --}}
                    <a class="favorite-btn {{ isset($favoriteIds[$product->id]) ? 'is-favorite' : '' }}"
                       href="{{ route('wishlist.index') }}"
                       data-item-id="{{ $product->id }}"
                       aria-label="@lang('ecommerce::ecommerce.add_to_wishlist')">
                        <i class="{{ isset($favoriteIds[$product->id]) ? 'fa fa-heart' : 'ti-heart' }}"></i>
                    </a>

                    {{-- Product Image Wrap --}}
                    <a class="card-img-wrap" href="{{ route('product.static', $product->id) }}">
                        <img class="card-img"
                             src="{{ $image($product->image_path) }}"
                             alt="{{ $title($product->title) }}"
                             loading="lazy">
                        {{-- Flash badge: show when offer expires within 24 hours --}}
                        @if($product->sale_expires_at && $product->sale_expires_at->diffInHours(now()) <= 24)
                            @php $hoursLeft = max(0, (int) now()->diffInHours($product->sale_expires_at, false)); @endphp
                            <div class="time-badge">
                                <i class="fa fa-hourglass-half"></i>
                                @lang('ecommerce::ecommerce.ends_in') {{ $hoursLeft }} @lang('ecommerce::ecommerce.hours')
                            </div>
                        @endif
                    </a>

                    {{-- Merchant / Store Name --}}
                    @if($product->merchant)
                        <div class="card-merchant">
                            <i class="fa fa-building-o"></i>
                            <a href="{{ route('merchant.show', $product->merchant->id) }}">{{ $product->merchant->name }}</a>
                        </div>
                    @endif

                    {{-- Product Title --}}
                    <h3 class="card-title">
                        <a href="{{ route('product.static', $product->id) }}" style="color: inherit; text-decoration: none;">
                            {{ $title($product->title) }}
                        </a>
                    </h3>

                    {{-- Rating --}}
                    <div class="card-rating">
                        ★★★★★ <span>({{ number_format((float) ($product->rating ?? 4.9), 1) }})</span>
                    </div>

                    {{-- Price Area — all values from real DB columns via Item model --}}
                    <div class="card-price-area">
                        <div class="price-row">
                            <span class="current-price">
                                {{-- sale_price is the real discounted price from DB --}}
                                {{ \App\Domain\AppBase\Models\Setting\Currency::format((float) $product->sale_price) }}
                            </span>
                            <del class="old-price">
                                {{-- default_price is the original price from DB --}}
                                {{ \App\Domain\AppBase\Models\Setting\Currency::format((float) $product->default_price) }}
                            </del>
                        </div>
                        <div class="save-tag">
                            @lang('ecommerce::ecommerce.save_amount', [
                                'amount' => \App\Domain\AppBase\Models\Setting\Currency::format(
                                    (float) $product->default_price - (float) $product->sale_price
                                )
                            ])
                        </div>

                        {{-- Add to Cart Form --}}
                        <form action="{{ route('cart.add') }}" method="POST" class="add-cart-form">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" value="1">
                            @if(method_exists($product, 'isInStock') && !$product->isInStock())
                                <button type="button" class="add-cart-btn" disabled style="background:#e5e7eb!important;color:#9ca3af!important;cursor:not-allowed;">
                                    @lang('ecommerce::ecommerce.out_of_stock')
                                </button>
                            @else
                                <button type="submit" class="add-cart-btn">
                                    <i class="ti-shopping-cart"></i> @lang('ecommerce::ecommerce.add_to_cart')
                                </button>
                            @endif
                        </form>
                    </div>
                </article>
            @empty
                <div class="empty-offers">
                    <i class="fa fa-tags"></i>
                    <h3>@lang('ecommerce::ecommerce.no_offers_found')</h3>
                    <p>@lang('ecommerce::ecommerce.no_products_desc')</p>
                    <a href="{{ route('offers.index') }}" class="pill-btn active" style="display: inline-flex; padding: 8px 18px;">
                        @lang('ecommerce::ecommerce.view_all_offers')
                    </a>
                </div>
            @endforelse
        </section>

        {{-- 6. Pagination --}}
        @if($products->hasPages())
            <div class="pagination-wrap">
                @if(!$products->onFirstPage())
                    <a href="{{ $products->previousPageUrl() }}">@lang('ecommerce::ecommerce.previous')</a>
                @else
                    <span>@lang('ecommerce::ecommerce.previous')</span>
                @endif

                {!! $products->links('pagination::bootstrap-4') !!}

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}">@lang('ecommerce::ecommerce.next')</a>
                @else
                    <span>@lang('ecommerce::ecommerce.next')</span>
                @endif
            </div>
        @endif
    </div>

    {{-- Toast Notification Box --}}
    <div id="toast-notice">
        <i class="fa fa-check-circle" style="color: #2ecc71;"></i>
        <span id="toast-text">@lang('ecommerce::ecommerce.copied')</span>
    </div>
@endsection

@section('scripts')
    <script>
        // Copy voucher code with visual feedback
        function copyVoucherCode(code, btn) {
            if (!navigator.clipboard) {
                var tempInput = document.createElement("input");
                tempInput.value = code;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                showToastNotice(@json(__('ecommerce::ecommerce.copied')) + ': ' + code);
                return;
            }

            navigator.clipboard.writeText(code).then(function() {
                var origText = btn.innerHTML;
                btn.classList.add('copied');
                btn.innerHTML = '<i class="fa fa-check"></i> ' + @json(__('ecommerce::ecommerce.copied'));
                showToastNotice(@json(__('ecommerce::ecommerce.copied')) + ': ' + code);

                setTimeout(function() {
                    btn.classList.remove('copied');
                    btn.innerHTML = origText;
                }, 2500);
            }).catch(function(err) {
                console.error('Could not copy code:', err);
            });
        }

        function showToastNotice(msg) {
            var toast = document.getElementById('toast-notice');
            var text = document.getElementById('toast-text');
            if (!toast || !text) return;

            text.textContent = msg;
            toast.style.display = 'inline-flex';

            setTimeout(function() {
                toast.style.display = 'none';
            }, 3000);
        }

        // Live Countdown Clock
        document.addEventListener('DOMContentLoaded', function () {
            var daysEl = document.getElementById('timer-days');
            var hoursEl = document.getElementById('timer-hours');
            var minsEl = document.getElementById('timer-mins');
            var secsEl = document.getElementById('timer-secs');

            if (!daysEl || !hoursEl || !minsEl || !secsEl) return;

            var targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 2);
            targetDate.setHours(targetDate.getHours() + 14);

            function updateTimer() {
                var now = new Date().getTime();
                var distance = targetDate.getTime() - now;

                if (distance <= 0) {
                    daysEl.textContent = '00';
                    hoursEl.textContent = '00';
                    minsEl.textContent = '00';
                    secsEl.textContent = '00';
                    return;
                }

                var d = Math.floor(distance / (1000 * 60 * 60 * 24));
                var h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var s = Math.floor((distance % (1000 * 60)) / 1000);

                daysEl.textContent = String(d).padStart(2, '0');
                hoursEl.textContent = String(h).padStart(2, '0');
                minsEl.textContent = String(m).padStart(2, '0');
                secsEl.textContent = String(s).padStart(2, '0');
            }

            updateTimer();
            setInterval(updateTimer, 1000);

            // Add-to-cart AJAX form handler
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

            // Wishlist toggle handler
            document.addEventListener('click', async function (event) {
                var favorite = event.target.closest('.favorite-btn');
                if (!favorite) return;
                event.preventDefault();

                var itemId = favorite.getAttribute('data-item-id');
                if (!itemId) return;

                try {
                    var response = await fetch("{{ route('wishlist.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ item_id: itemId })
                    });

                    if (response.status === 401) {
                        var loginModal = document.getElementById('loginRequiredModal');
                        if (loginModal && window.bootstrap) {
                            new bootstrap.Modal(loginModal).show();
                        } else {
                            window.location.href = "{{ route('auth.login') }}";
                        }
                        return;
                    }

                    var data = await response.json();
                    if (data.status === 'success') {
                        favorite.classList.toggle('is-favorite');
                        var icon = favorite.querySelector('i');
                        if (icon) {
                            if (favorite.classList.contains('is-favorite')) {
                                icon.className = 'fa fa-heart';
                            } else {
                                icon.className = 'ti-heart';
                            }
                        }
                        showToastNotice(data.message || @json(__('ecommerce::ecommerce.added_successfully')));
                    }
                } catch (err) {
                    console.error('Wishlist error:', err);
                }
            });
        });
    </script>
@endsection
