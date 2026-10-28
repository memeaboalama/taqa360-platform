@extends('ecommerce::layout.store-layout')

@php
    $localized = static function ($value, $fallback = '') {
        if (is_array($value))
            return $value[app()->getLocale()] ?? $value['en'] ?? reset($value) ?? $fallback;
        return $value ?: $fallback;
    };
    $logo = static fn($merchant) => $merchant->logo ?: asset('assets/ecommerce/images/logo.png');
@endphp

@section('title', __('ecommerce::ecommerce.merchants_list'))

@section('active-nav', 'merchants')

@section('page-styles')
    <style>
        main {
            padding: 30px 0 60px
        }

        .page-header {
            padding-bottom: 25px;
            border-bottom: 1px solid #f1f1f1;
            margin-bottom: 25px
        }

        .page-title {
            font-size: 25px;
            margin: 0 0 5px;
            font-weight: 800
        }

        .breadcrumb {
            font-size: 11px;
            color: #9aa1a8
        }

        .breadcrumb a,
        .breadcrumb a:visited,
        .breadcrumb a:hover,
        .breadcrumb a:focus,
        .breadcrumb a:active {
            color: var(--yellow);
            text-decoration: none
        }

        .breadcrumb strong {
            color: var(--yellow)
        }

        .layout {
            display: grid;
            grid-template-columns: 190px minmax(0, 1fr);
            gap: 22px;
            direction: inherit
        }

        .sidebar,
        .results {
            direction: inherit
        }

        .filter-sidebar {
            border: 1px solid var(--line);
            border-radius: 9px;
            padding: 14px 13px;
            box-shadow: var(--shadow);
            position: sticky;
            top: 70px
        }

        .filter-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 13px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f1f3;
            margin-bottom: 8px
        }

        .filter-title i {
            color: var(--yellow)
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 8px;
            border-radius: 5px;
            color: #68717b;
            font-size: 10px;
            text-decoration: none
        }

        .filter-option:visited,
        .filter-option:hover,
        .filter-option:focus {
            text-decoration: none
        }

        .filter-option:hover,
        .filter-option.active {
            background: #eef4fa;
            color: var(--yellow)
        }

        .filter-option.active {
            font-weight: 700
        }

        .filter-option .count {
            margin-inline-start: auto;
            color: #a5acb2;
            font-size: 9px
        }

        .controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px
        }

        .sort {
            height: 40px;
            width: 135px;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 0 10px;
            color: #69717a;
            background: #fff;
            font-size: 10px
        }

        .merchant-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px
        }

        .merchant-card {
            background: #fff;
            border: 1px solid #e1e6ea;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            min-height: 286px;
            overflow: hidden;
            transition: .2s;
            box-shadow: 0 2px 7px #1d253008
        }

        .merchant-card:hover {
            border-color: var(--yellow);
            box-shadow: 0 6px 16px #1d253014;
            transform: translateY(-2px)
        }

        .merchant-banner {
            height: 55px;
            background: #f7f8f9 repeating-linear-gradient(135deg, #f7f8f9, #f7f8f9 4px, #eef1f3 4px, #eef1f3 5px);
            position: relative;
            border-bottom: 1px solid #edf0f2
        }

        .rating-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 1px 6px;
            color: #f0a000;
            font-size: 9px;
            font-weight: 700
        }

        .merchant-logo-wrap {
            width: 50px;
            height: 50px;
            background: #fff;
            border: 1px solid #e8ebed;
            border-radius: 50%;
            padding: 3px;
            margin: -25px auto 0;
            position: relative;
            z-index: 2;
            box-shadow: 0 2px 4px #0000000d
        }

        .merchant-logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%
        }

        .merchant-body {
            padding: 6px 10px 4px;
            text-align: center;
            flex: 1
        }

        .merchant-name {
            font-size: 13px;
            font-weight: 700;
            margin: 4px 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .verified {
            color: #00aeea;
            font-size: 11px
        }

        .merchant-desc {
            font-size: 9px;
            color: #888;
            margin: 0 0 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .merchant-stats {
            display: flex;
            border: 1px solid #f0f0f0;
            border-radius: 5px;
            background: #fdfdfd;
            margin-bottom: 8px;
            padding: 4px 0
        }

        .stat {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-left: 1px solid #f0f0f0;
            line-height: 1.2
        }

        .stat:last-child {
            border-left: 0
        }

        .stat strong {
            font-size: 11px
        }

        .stat span {
            font-size: 8px;
            color: #999
        }

        .contacts {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 9px;
            color: #666
        }

        .contact {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #f9f9f9;
            padding: 4px 7px;
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden
        }

        .contact i {
            color: #aaa;
            width: 11px
        }

        .contact span {
            overflow: hidden;
            text-overflow: ellipsis
        }

        .merchant-footer {
            padding: 8px 10px;
            background: #fff;
            border-top: 1px solid #f5f5f5
        }

        .visit {
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            background: var(--yellow);
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            color: #fff;
            text-decoration: none
        }

        .visit:hover,
        .visit:focus {
            background: #245C8E;
            color: #fff;
            text-decoration: none
        }

        .empty {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px;
            color: #9299a0
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 7px;
            margin-top: 26px
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

        .footer-links a {
            color: #d2d4d6
        }

        @media(max-width:991px) {
            .layout {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:991px) {
            .filter-sidebar {
                display: none
            }
        }

        @media(max-width:991px) {
            .merchant-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:560px) {
            .page-title {
                font-size: 21px
            }
        }

        @media(max-width:560px) {
            .controls {
                gap: 7px
            }
        }

        @media(max-width:560px) {
            .sort {
                width: 105px
            }
        }

        @media(max-width:560px) {
            .merchant-grid {
                gap: 9px
            }
        }

        @media(max-width:560px) {
            .merchant-card {
                min-height: 255px
            }
        }

        @media(max-width:560px) {
            .merchant-name {
                font-size: 10px
            }
        }

        @media(max-width:560px) {
            .merchant-desc {
                font-size: 8px
            }
        }

        @media(max-width:560px) {
            .visit {
                height: 27px;
                font-size: 9px
            }
        }

        .search-merchant {
            height: 40px;
            flex: 1;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 0 14px;
            outline: 0;
            direction: inherit
        }
    </style>
@endsection

@section('content')
    <div class="merchants-page">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">@lang('ecommerce::ecommerce.merchants_list')</h1>
                <div class="breadcrumb"><a href="{{ route('ecommerce.static') }}">@lang('ecommerce::ecommerce.home')</a> /
                    <strong>@lang('ecommerce::ecommerce.merchants_list')
                    </strong>
                </div>
            </div>
            <div class="layout">
                <aside class="sidebar">
                    <div class="filter-sidebar">
                        <div class="filter-title"><i class="fa fa-th-large"></i>
                            @lang('ecommerce::ecommerce.categories.categories')</div><a
                            class="filter-option {{ request('category', 'all') === 'all' ? 'active' : '' }}"
                            href="{{ route('merchants.index', request()->except('category', 'page')) }}"><i
                                class="fa fa-th-large"></i><span>@lang('ecommerce::ecommerce.categories.all_categories')</span><span
                                class="count">{{ $merchants->total() }}</span></a>@foreach($categories as $category)<a
                                    class="filter-option {{ (string) request('category') === (string) $category->id ? 'active' : '' }}"
                                    href="{{ route('merchants.index', array_merge(request()->except('page'), ['category' => $category->id])) }}"><i
                                class="fa fa-angle-right arrow-end"></i><span>{{ $localized($category->title) }}</span></a>@endforeach
                    </div>
                </aside>
                <section class="results">
                    <form class="controls" method="get" action="{{ route('merchants.index') }}"><input
                            class="search-merchant" name="q" value="{{ request('q') }}"
                            placeholder="@lang('ecommerce::ecommerce.search_by_merchant_name')"><input type="hidden"
                            name="category" value="{{ request('category', 'all') }}"><select class="sort" name="sort_by"
                            onchange="this.form.submit()">
                            <option value="latest" {{ request('sort_by', 'latest') === 'latest' ? 'selected' : '' }}>
                                @lang('ecommerce::ecommerce.newest')
                            </option>
                            <option value="name_asc" {{ request('sort_by') === 'name_asc' ? 'selected' : '' }}>
                                @lang('ecommerce::ecommerce.name_a_z')
                            </option>
                            <option value="name_desc" {{ request('sort_by') === 'name_desc' ? 'selected' : '' }}>
                                @lang('ecommerce::ecommerce.name_z_a')
                            </option>
                            <option value="oldest" {{ request('sort_by') === 'oldest' ? 'selected' : '' }}>
                                @lang('ecommerce::ecommerce.oldest')
                            </option>
                        </select></form>
                    <div class="merchant-grid">
                        @forelse($merchants as $merchant)@php $name = $localized($merchant->company_name, $merchant->name);
                                $description = $localized($merchant->description, __('ecommerce::ecommerce.default_merchant_desc'));
                                $rating = (float) ($merchant->rate ?? 0);
                                $branches = $merchant->branches_count ?? $merchant->branches()->count();
                            $items = $merchant->items_count ?? $merchant->items()->count();@endphp
                            <div class="merchant-card">
                                <div class="merchant-banner"><span class="rating-badge">★
                                        {{ number_format($rating, 1) }}</span></div><a
                                    href="{{ route('merchant.show', $merchant->id) }}" class="merchant-logo-wrap"><img
                                        class="merchant-logo" src="{{ $logo($merchant) }}" alt="{{ $name }}"></a>
                                <div class="merchant-body">
                                    <h2 class="merchant-name">{{ $name }} @if($merchant->is_verified)<i
                                    class="fa fa-check-circle verified"></i>@endif</h2>
                                    <p class="merchant-desc">{{ Str::limit($description, 55) }}</p>
                                    <div class="merchant-stats">
                                        <div class="stat">
                                            <strong>{{ $branches }}</strong><span>@lang('ecommerce::ecommerce.branches')</span>
                                        </div>
                                        <div class="stat">
                                            <strong>{{ $items }}</strong><span>@lang('ecommerce::ecommerce.sales')</span>
                                        </div>
                                    </div>
                                    <div class="contacts">@if($merchant->phone)
                                        <div class="contact"><i class="fa fa-phone"></i><span
                                                class="num">{{ $merchant->phone }}</span>
                                    </div>@endif @if($merchant->email)
                                            <div class="contact"><i class="fa fa-envelope-o"></i><span
                                        class="ltr">{{ $merchant->email }}</span></div>@endif
                                    </div>
                                </div>
                                <div class="merchant-footer"><a class="visit"
                                        href="{{ route('merchant.show', $merchant->id) }}">@lang('ecommerce::ecommerce.visit_store')
                                        <i class="fa fa-arrow-right arrow-end"></i></a></div>
                        </div>@empty<div class="empty"><i
                            class="fa fa-search"></i>@lang('ecommerce::ecommerce.no_matching_merchants')</div>
                        @endforelse
                    </div>@if($merchants->hasPages())
                        <div class="pagination">
                            {!! $merchants->onFirstPage() ? '<span>' . e(__('ecommerce::ecommerce.previous')) . '</span>' : '<a href="' . $merchants->previousPageUrl() . '">' . e(__('ecommerce::ecommerce.previous')) . '</a>' !!}{{ $merchants->links('pagination::bootstrap-4') }}{!! $merchants->hasMorePages() ? '<a href="' . $merchants->nextPageUrl() . '">' . e(__('ecommerce::ecommerce.next')) . '</a>' : '<span>' . e(__('ecommerce::ecommerce.next')) . '</span>' !!}
                    </div>@endif
                </section>
            </div>
        </div>
    </div>
@endsection