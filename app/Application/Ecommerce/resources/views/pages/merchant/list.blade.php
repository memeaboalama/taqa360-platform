@extends('ecommerce::layout.layout')

@section('title', __('ecommerce::ecommerce.merchants_list'))

@section('content')

<style>
    :root {
        --card-radius: 8px;
        --control-radius: 6px;
        --card-shadow: 0 4px 6px -1px rgba(0,0,0,0.06);
        --primary: #306FAA;
        --primary-hover: #245C8E;
        --text-on-primary: #ffffff;
        --primary-light: #F1F6FB;
        --text-dark: #3e3e3e;
        --text-gray: #718096;
        --border-color: #e2e8f0;
    }

    .merchant-list-page {
        background: #fdfdfd;
        min-height: 100vh;
        padding-bottom: 60px;
    }

    /* Hero/Breadcrumb Section */
    .page-header {
        background: #fff;
        padding: 25px 0;
        margin-bottom: 30px;
        border-bottom: 1px solid #f1f1f1;
    }
    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 5px;
    }
    .breadcrumb-item a {
        color: var(--text-gray);
        text-decoration: none;
        font-size: 0.9rem;
    }
    .breadcrumb-item.active {
        color: #EA993E; /* Darker yellow/gold for text readability */
        font-weight: 600;
    }

    /* Sidebar Filter (Desktop) */
    .filter-sidebar {
        background: white;
        border-radius: var(--card-radius);
        padding: 24px;
        border: 1px solid #eee;
        position: sticky;
        top: 20px;
    }
    @media (max-width: 991px) {
        .filter-sidebar {
            display: none;
        }
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f8f8;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .filter-title i { color: var(--primary); }

    .filter-option {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        border-radius: var(--control-radius);
        color: var(--text-gray);
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 6px;
        cursor: pointer;
        text-decoration: none;
        border: 1px solid transparent;
        font-size: 0.95rem;
        gap: 12px; /* Added gap for spacing */
    }
    .filter-option:hover {
        background: var(--primary-light);
        color: var(--text-dark);
        border-color: #faecc9;
    }
    .filter-option.active {
        background: var(--primary);
        color: var(--text-on-primary);
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(48, 111, 170, 0.2);
    }
    .filter-option.active i { color: var(--text-on-primary) !important; }

    /* Controls Header */
    .controls-header {
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: nowrap;
    }

    .search-wrapper {
        flex: 1;
        min-width: 0;
        position: relative;
    }
    .search-input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #ddd;
        border-radius: var(--control-radius);
        font-size: 14px;
        transition: all 0.3s;
        background: #fff;
        height: 42px;
    }
    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(48, 111, 170, 0.15);
        outline: none;
    }
    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 14px;
    }

    /* Grouping Filter & Sort Buttons */
    .actions-wrapper {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    /* Sort Select - Custom Style */
    .custom-select-wrapper {
        position: relative;
        display: inline-block;
    }
    .custom-select {
        appearance: none;
        -webkit-appearance: none;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: var(--control-radius);
        padding: 0 32px 0 12px;
        height: 42px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
        cursor: pointer;
        width: 100%;
        max-width: 160px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23555' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 0 0 1 .753 1.659l-4.796 5.48a1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 10px;
    }
    .custom-select:focus {
        border-color: var(--primary);
        outline: none;
    }

    /* Mobile Filter Button */
    .mobile-filter-btn {
        display: none;
        align-items: center;
        justify-content: center;
        height: 42px;
        width: 42px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: var(--control-radius);
        color: var(--text-dark);
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .mobile-filter-btn:hover {
        border-color: var(--primary);
        color: #EA993E; /* Darker yellow text */
    }

    /* Mobile Tweaks */
    @media (max-width: 991px) {
        .mobile-filter-btn {
            display: inline-flex;
            width: auto;
            padding: 0 12px;
            gap: 6px;
        }
        .filter-btn-text {
            display: none;
        }
        @media (min-width: 500px) {
            .filter-btn-text { display: inline; }
        }
    }

    @media (max-width: 576px) {
        .controls-header { gap: 8px; }
        .search-input { padding-left: 36px; padding-right: 12px; }
        .mobile-filter-btn { padding: 0; width: 42px; }
        .filter-btn-text { display: none !important; }
        .custom-select {
            width: auto;
            padding-right: 25px;
            background-position: right 6px center;
        }
    }

    /* Mobile Bottom Sheet */
    @media (min-width: 992px) {
        .sheet-overlay, .category-sheet, .mobile-filter-btn { display: none !important; }
    }

    .sheet-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1040;
        opacity: 0; visibility: hidden; transition: all 0.3s; backdrop-filter: blur(2px);
    }
    .sheet-overlay.show { opacity: 1; visibility: visible; }

    .category-sheet {
        position: fixed; bottom: -100%; left: 0; right: 0; background: white; z-index: 1050;
        border-top-left-radius: 20px; border-top-right-radius: 20px;
        padding: 25px 20px 40px 20px; box-shadow: 0 -5px 25px rgba(0,0,0,0.15);
        transition: bottom 0.35s cubic-bezier(0.16, 1, 0.3, 1); max-height: 80vh; overflow-y: auto;
    }
    .category-sheet.show { bottom: 0; }

    .sheet-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
    }
    .sheet-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); }
    .sheet-close {
        background: #f1f3f5; border: none; width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; color: #555;
        font-size: 18px; cursor: pointer;
    }

    /* Loader */
    .up-active .merchant-results-wrapper {
        opacity: 0.5; pointer-events: none; filter: blur(1px); transition: opacity 0.2s;
    }

    /* ---- RTL SUPPORT ---- */

    [dir="rtl"] .merchant-list-page {
        direction: rtl;
        text-align: right;
    }

    [dir="rtl"] .page-header {
        text-align: right;
    }

    [dir="rtl"] .breadcrumb {
        padding: 0; margin: 0;
        display: flex;
        flex-wrap: wrap;
        list-style: none; /* Reset list style */
    }

    [dir="rtl"] .breadcrumb-item {
        display: flex;
        align-items: center;
    }

    /* Reverse slash */
    [dir="rtl"] .breadcrumb-item + .breadcrumb-item::before {
        float: right;
        padding-right: 0;
        padding-left: 0.5rem;
        /* Consider content: "/"; usually handled by bootstrap, just ensuring margin */
    }

    [dir="rtl"] .filter-sidebar { text-align: right; }

    [dir="rtl"] .filter-option {
        text-align: right;
        flex-direction: row;
    }

    /* Helper spacing flip */
    [dir="rtl"] .me-2 { margin-left: 0.5rem !important; margin-right: 0 !important; }
    [dir="rtl"] .ms-2 { margin-right: 0.5rem !important; margin-left: 0 !important; }

    /* Search Input RTL */
    [dir="rtl"] .search-input {
        padding: 10px 40px 10px 15px;
    }
    [dir="rtl"] .search-icon {
        left: auto; right: 14px;
    }

    /* Select RTL */
    [dir="rtl"] .custom-select {
        padding: 0 12px 0 32px;
        background-position: left 10px center;
    }

    /* Mobile RTL */
    @media (max-width: 576px) {
        [dir="rtl"] .search-input { padding-right: 36px; padding-left: 12px; }
        [dir="rtl"] .custom-select { padding-left: 25px; background-position: left 6px center; }
    }
</style>

<div class="merchant-list-page" up-main>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title">@lang('ecommerce::ecommerce.merchants_list')</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('ecommerce::ecommerce.home')</a></li>
                    <li class="breadcrumb-item ">@lang('ecommerce::ecommerce.merchants_list')</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="filter-sidebar">
                    <div class="filter-title">
                        <i class="fa fa-th-large text-primary"></i>
                        @lang('ecommerce::ecommerce.categories.categories')
                    </div>

                    <div id="desktop-categories">
                         <div class="categories-list">
                            <a onclick="setCategory('all')" class="filter-option {{ request('category', 'all') == 'all' ? 'active' : '' }}">
                                <i class="fa fa-th-large"></i>
                                @lang('ecommerce::ecommerce.all_categories')
                            </a>

                            @foreach($categories as $cat)
                                @if($cat)
                                <a onclick="setCategory('{{ $cat->id }}')" class="filter-option {{ request('category') == $cat->id ? 'active' : '' }}">
                                    <i class="fa fa-angle-right arrow-end text-muted small"></i>
                                    {{ $cat->title }}
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-12">
                <div class="controls-header">
                    <div class="search-wrapper">
                        <i class="fa fa-search search-icon"></i>
                        <input type="text"
                               class="search-input"
                               value="{{ request('q') }}"
                               placeholder="@lang('ecommerce::ecommerce.search_by_merchant_name')"
                               onkeyup="updateSearch(this.value)">
                    </div>

                    <div class="actions-wrapper">
                        <button type="button" class="mobile-filter-btn" onclick="toggleSheet(true)" title="@lang('ecommerce::ecommerce.categories.categories')">
                            <i class="fa fa-sliders"></i>
                            <span class="filter-btn-text">@lang('ecommerce::ecommerce.categories.categories')</span>
                        </button>

                        <div class="custom-select-wrapper">
                            <select class="custom-select"
                                    onchange="setSort(this.value)">
                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.latest')</option>
                                <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.oldest')</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.name') (A-Z)</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.name') (Z-A)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="merchant-results-wrapper">
                    @include('ecommerce::pages.merchant.partials.list_result')
                </div>
            </div>
        </div>
    </div>

    <form id="merchant-filter-form" action="{{ route('merchants.index') }}" method="GET"
            up-target=".merchant-results-wrapper"
            up-history="true"
            up-scroll="false"
            class="d-none">

        <input type="hidden" name="q" id="hidden_q" value="{{ request('q') }}">
        <input type="hidden" name="sort_by" id="hidden_sort" value="{{ request('sort_by', 'latest') }}">

        <input type="radio" name="category" value="all" id="cat_all" {{ request('category', 'all') == 'all' ? 'checked' : '' }}>
        @foreach($categories as $cat)
            @if($cat)
            <input type="radio" name="category" value="{{ $cat->id }}" id="cat_{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }}>
            @endif
        @endforeach
    </form>

    <!-- Mobile Bottom Sheet -->
    <div class="sheet-overlay d-lg-none" id="sheetOverlay" onclick="toggleSheet(false)"></div>
    <div class="category-sheet d-lg-none" id="categorySheet">
        <div class="sheet-header">
            <div class="sheet-title">@lang('ecommerce::ecommerce.categories.categories')</div>
            <button class="sheet-close" onclick="toggleSheet(false)"><i class="fa fa-times"></i></button>
        </div>
        <div class="sheet-body">
            <!-- Reusing the category list structure -->
             <div class="categories-list">
                <a onclick="setCategory('all')" class="filter-option {{ request('category', 'all') == 'all' ? 'active' : '' }}">
                    <i class="fa fa-th-large me-2 ms-2"></i>
                    @lang('ecommerce::ecommerce.all_categories')
                </a>

                @foreach($categories as $cat)
                    @if($cat)
                    <a onclick="setCategory('{{ $cat->id }}')" class="filter-option {{ request('category') == $cat->id ? 'active' : '' }}">
                        <i class="fa fa-angle-right arrow-end me-2 ms-2 text-muted small"></i>
                        {{ $cat->title }}
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</div>



<script>
    function toggleSheet(show) {
        const overlay = document.getElementById('sheetOverlay');
        const sheet = document.getElementById('categorySheet');
        if (show) {
            overlay.classList.add('show');
            sheet.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        } else {
            overlay.classList.remove('show');
            sheet.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    function setCategory(id) {
        const radio = document.getElementById('cat_' + id);
        if(radio) radio.checked = true;

        document.querySelectorAll('.filter-option').forEach(el => el.classList.remove('active'));


        if(event && event.currentTarget) event.currentTarget.classList.add('active');

        toggleSheet(false);

        up.submit('#merchant-filter-form');
    }

    let searchTimeout;
    function updateSearch(val) {
        document.getElementById('hidden_q').value = val;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            up.submit('#merchant-filter-form');
        }, 400);
    }

    function setSort(val) {
        document.getElementById('hidden_sort').value = val;
        up.submit('#merchant-filter-form');
    }

    if (typeof up !== 'undefined') {
        up.compiler('.pagination a', function(link) {
            link.setAttribute('up-target', '.merchant-results-wrapper');
            link.setAttribute('up-history', 'true');
            link.setAttribute('up-scroll', '#merchant-results-wrapper');
            link.setAttribute('up-transition', 'cross-fade');
            link.addEventListener('click', function(e) { e.preventDefault(); }); // Security double-check
        });
    }
</script>

@endsection
