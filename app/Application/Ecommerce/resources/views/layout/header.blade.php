<style>
    .lang-direction-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
    }

    .lang-dir-switch {
        background: #306FAA;
        color: white;
        padding: 12px 18px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        display: block;
        text-align: center;
        min-width: 60px;
    }

    .lang-dir-switch:hover {
        background: #e63e2d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    [dir="rtl"] {
        direction: ltr;
        /* text-align: right; */
    }

    [dir="rtl"] .top-header-left ul,
    [dir="rtl"] .top-header-right ul {
        padding: 0;
        margin: 0;
    }

    [dir="rtl"] .top-header-right {
        text-align: left;
    }

    [dir="rtl"] .top-header-left {
        text-align: right;
    }

    [dir="rtl"] .top-header-right li,
    [dir="rtl"] .top-header-left li {
        display: inline-block;
    }

    [dir="rtl"] .nav-section {
        float: left;
    }

    [dir="rtl"] .cart-box {
        float: left;
        margin-left: 0;
        margin-right: 15px;
    }

    [dir="rtl"] #main-menu {
        text-align: right;
        float: right;
    }

    @media (min-width: 1200px) {
        [dir="rtl"] #main-menu>li>a {
            text-align: right;
            direction: rtl;
        }
    }

    [dir="rtl"] .mobile-back {
        text-align: left;
    }

    [dir="rtl"] .dropdown-menu {
        text-align: right;
        /* right: 0 !important;
    left: auto !important; */
    }

    [dir="rtl"] .dropdown-menu .dropdown-item {
        text-align: right;
        direction: rtl;
    }

    [dir="rtl"] .mega-menu .container .row {
        direction: rtl;
    }

    [dir="rtl"] .mega-menu .link-section {
        text-align: right;
    }

    [dir="rtl"] .mega-menu .menu-content ul {
        text-align: right;
        padding-right: 0;
    }

    [dir="rtl"] .mega-menu .menu-content li {
        text-align: right;
    }

    [dir="rtl"] .shopping-cart {
        text-align: right;
    }

    [dir="rtl"] .shopping-cart .media {
        direction: rtl;
    }

    [dir="rtl"] .shopping-cart .media .me-3 {
        margin-left: 10px;
        margin-right: 0 !important;
        float: right;
    }

    [dir="rtl"] .shopping-cart .media-body {
        text-align: right;
        margin-right: 10px;
        margin-left: 0;
    }

    [dir="rtl"] .close-circle {
        left: 10px;
        right: auto;
    }

    /* Search input alignment in RTL */
    [dir="rtl"] .ecommerce-from .form-control {
        text-align: right;
    }

    [dir="rtl"] .btn_white {
        margin-left: 0;
        margin-right: 10px;
    }

    [dir="rtl"] .me-3 {
        margin-right: 0 !important;
        margin-left: 1rem !important;
    }

    [dir="rtl"] .ms-3 {
        margin-left: 0 !important;
        margin-right: 1rem !important;
    }

    [dir="rtl"] .ps-2 {
        padding-right: 0.5rem !important;
        padding-left: 0 !important;
    }

    [dir="rtl"] .text-end {
        text-align: left !important;
    }

    [dir="rtl"] .text-start {
        text-align: right !important;
    }

    [dir="rtl"] .row {
        direction: rtl;
    }

    [dir="rtl"] .col-3,
    [dir="rtl"] .col-9 {
        float: right;
    }

    html {
        transition: all 0.3s ease;
    }

    /* Mobile RTL - Simple Mirroring */
    @media (max-width: 768px) {

        /* Home Slider Arrows */
        body.rtl .home-slider .owl-nav .owl-prev {
            left: 30px;
            right: unset;
        }

        body.rtl .home-slider .owl-nav .owl-prev i {
            -webkit-transform: scaleX(-1);
            transform: scaleX(-1);
        }

        body.rtl .home-slider .owl-nav .owl-next {
            left: 5px;
            right: unset;
        }

        body.rtl .home-slider .owl-nav .owl-next i {
            -webkit-transform: scaleX(-1);
            transform: scaleX(-1);
        }

        /* Search Form Elements */
        body.rtl .ecommerce-layout .ecommerce-from input {
            border-radius: 0 8px 8px 0;
            padding-right: 15px;
            padding-left: 0;
            text-align: right;
        }

        body.rtl .ecommerce-layout .ecommerce-from #search-input {
            direction: rtl;
        }

        body.rtl .ecommerce-layout .ecommerce-from select {
            padding-left: 25px;
            padding-right: 10px;
            background-position: left 10px center;
            text-align: right;
        }

        body.rtl .ecommerce-layout .ecommerce-from .btn_inverse {
            border-radius: 8px 0 0 8px;
            padding-left: 10px;
            padding-right: 15px;
        }

        body.rtl .ecommerce-layout .ecommerce-from .btn_inverse i {
            margin-left: 0;
            margin-right: 10px;
        }
    }

    /* RTL: modal title on the right and close button on the left */
    [dir="rtl"] .modal-content {
        direction: rtl;
    }

    [dir="rtl"] .modal-header .btn-close {
        margin-left: 0;
        margin-right: auto;
    }

    /* Shopping cart dropdown consistency across all screen sizes */
    .ecommerce-layout .cart-box .shopping-cart {
        z-index: 1000;
        min-width: 260px;
        max-width: calc(100vw - 30px);
    }

    @media (max-width: 1199px) and (min-width: 992px) {
        .ecommerce-layout .cart-box .shopping-cart {
            min-width: 280px;
        }
    }

    [dir="rtl"] .ecommerce-layout .cart-box .shopping-cart {
        left: 0;
        right: auto;
    }
</style>


<div class="top-contact top-header commerce-layout p-1">
    <div class="container">
        <div class="row">
            <div class="col-md-6 d-none d-md-block">
                <ul class="top-header-left">
                    <li>
                        @if(isset($headerData['top_message']) && !empty($headerData['top_message']))
                        {{ $headerData['top_message'] }}
                        @else
                        @lang('ecommerce::ecommerce.welcome')
                        @endif
                    </li>
                    {{-- [TEMP DISABLED] تبويب العروض الخاصة معلق مؤقتاً
                    <li><a href="{{ $headerData['offer_link'] ?? route('offers.index') }}">{{ $headerData['offer_text'] ?? __('ecommerce::ecommerce.special_offers') }}</a></li>
                    --}}
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <ul class="top-header-right">
                    <li>
                    <li>
                        <a href="{{ route('wishlist.index') }}" up-target=".main-body" up-transition="cross-fade" class="position-relative">
                            <i class="ti-heart" style="font-size: 1.2rem;"></i>
                            @auth
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" id="wishlist-count" style="background-color: #306FAA; color: #ffffff; font-size: 0.6rem; padding: 0.25em 0.4em;">
                                {{ \App\Domain\MarketPlace\Models\Favorite::where('user_id', auth()->id())->count() }}
                            </span>
                            @endauth
                        </a>
                    </li>
                    </li>

                    <li>
                        <a href="#" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            {{ app()->getLocale() == 'ar' ? __('ecommerce::ecommerce.languages.ara') : __('ecommerce::ecommerce.languages.eng') }}
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <div class="dropdown-menu default-dropdown" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" onclick="switchLanguage('en')">
                                @lang('ecommerce::ecommerce.languages.eng')
                            </a>
                            <a class="dropdown-item" href="#" onclick="switchLanguage('ar')">
                                @lang('ecommerce::ecommerce.languages.ara')
                            </a>
                        </div>
                    </li>

                    {{-- <li>
                        <a href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                            @lang('ecommerce::ecommerce.currencies.usd')
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <div class="dropdown-menu default-dropdown" aria-labelledby="dropdownMenuButton1">
                            @foreach(['usd', 'inr', 'gbp', 'eur'] as $currency)
                                <a class="dropdown-item" href="#">
                                    @lang('ecommerce::ecommerce.currencies.'.$currency)
                                </a>
                            @endforeach
                        </div>
                    </li> --}}

                    <li>
                        @auth
                        <a href="{{ route('account.index') }}" up-target=".main-body">@lang('ecommerce::ecommerce.my_account')</a>
                        @else
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginRequiredModal">@lang('ecommerce::ecommerce.my_account')</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="main-header">
    <div class="mid-header">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <a href="{{ route('home') }}" class="brand-logo">
                        @if(isset($headerData['logo']) && !empty($headerData['logo']))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($headerData['logo']) }}" alt="{{ $headerData['site_name'] ?? 'logo' }}" width="20%">
                        @else
                        <img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="logo" width="20%">
                        @endif
                    </a>
                </div>
                <div class="col-9">
                    <div class="nav-section">
                        <nav id="main-nav">
                            <div class="toggle-nav">
                                <i class="fa fa-bars sidebar-bar"></i>
                            </div>
                            <ul id="main-menu" class="sm pixelstrap sm-horizontal{{ app()->getLocale() === 'ar' ? ' sm-rtl' : '' }}">
                                <li>
                                    <div class="mobile-back text-end">@lang('ecommerce::ecommerce.back')<i class="fa fa-angle-left arrow-start ps-2" aria-hidden="true"></i></div>
                                </li>
                                <li>
                                    <a href="{{ route('home') }}">@lang('ecommerce::ecommerce.home')</a>
                                </li>
                                <li>
                                    <a href="{{ route('categories.index') }}" up-target=".main-body" up-transition="cross-fade">@lang('ecommerce::ecommerce.ecommerce.categories')</a>
                                    {{-- <ul>
                                        @foreach($categories->take(8) as $cat)
                                            <li><a href="{{ route('categories.index', ['category' => $cat->id]) }}" up-target=".main-body" up-transition="cross-fade">{{ $cat->title }}</a>
                                </li>
                                @endforeach
                                <li><a href="{{ route('categories.index') }}" up-target=".main-body" up-transition="cross-fade">@lang('ecommerce::ecommerce.all_categories')</a></li>
                            </ul> --}}
                            </li>
                            <li>
                                <a href="{{ route('merchants.index') }}" up-target=".main-body" up-transition="cross-fade">@lang('ecommerce::ecommerce.merchants_list')</a>
                            </li>
                            {{-- [TEMP DISABLED] تبويب العروض الخاصة معلق مؤقتاً
                            <li>
                                <a href="{{ route('offers.index') }}" up-target=".main-body" up-transition="cross-fade">@lang('ecommerce::ecommerce.special_offers')</a>
                            </li>
                            --}}
                            {{-- <li>
                                    <a href="#">@lang('ecommerce::ecommerce.pages')</a>
                                    <ul>
                                        <li><a href="{{ route('auth.login') }}">@lang('ecommerce::ecommerce.login')</a></li>
                            <li><a href="{{ route('auth.register') }}">@lang('ecommerce::ecommerce.register')</a></li>
                            </ul>
                            </li> --}}
                            </ul>
                        </nav>
                    </div>

                    <div class="cart-header-container">
                        @include('ecommerce::pages.cart.partials.cart-header')
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="bottom-header">
        <div class="container">
            <div class="row">
                <div class="col-md-3 ecommerce-sidebar set-order-2">
                    <button class="btn btn-block btn_inverse toggle-btn">
                        <i class="fa fa-bars"></i>@lang('ecommerce::ecommerce.shop_by_category')
                    </button>
                </div>
                <div class="col-md-9 set-order-1">
                    <div class="d-flex align-items-center justify-content-end w-100">
                        <form class="ecommerce-from me-3 flex-grow-1"
                            action="{{ route('categories.search') }}"
                            method="GET"
                            up-target=".main-content"
                            up-submit
                            up-replace
                            up-history="true"
                            up-track="search"
                            id="search-form">

                            <div class="input-group">
                                <input type="text"
                                    name="q"
                                    class="form-control"
                                    placeholder="@lang('ecommerce::ecommerce.search_placeholder')"
                                    value="{{ request('q') }}"
                                    id="search-input"
                                    minlength="2">
                                <div class="input-group-append selective">
                                    <select name="category"
                                        class="single-option-selector"
                                        id="category-select">
                                        <option value="all">@lang('ecommerce::ecommerce.all_collection')</option>
                                        @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn_inverse" id="search-btn">
                                        @lang('ecommerce::ecommerce.search') <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="search-suggestions-container"
                                up-layer="new"
                                up-align="parent"
                                up-position="bottom"
                                up-dismissable
                                class="search-suggestions-wrapper">
                            </div>
                        </form>

                        <style>
                            /* Keep auth & service buttons in normal flow in LTR too
                               (the theme pins .btn_white absolutely over the row in English) */
                            .ecommerce-layout .bottom-header .btn_white {
                                position: static;
                            }
                            /* Remove the search-form right padding reserved for the
                               absolutely-positioned login button — LTR (English) only */
                            [dir="ltr"] .ecommerce-layout .bottom-header .ecommerce-from {
                                padding-right: 0;
                            }
                        </style>
                        <button type="button"
                            class="btn text-white me-2 ms-2 font-weight-bold"
                            style="background: #306FAA; color: #fff !important; border: none; border-radius: 6px; padding: 10px 18px; font-size: 0.95rem; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(48, 111, 170, 0.25);"
                            onclick="handleServiceRequestClick()">
                            <i class="fa fa-plus-circle"></i> @lang('ecommerce::ecommerce.request_service')
                        </button>

                        @auth
                        <a class="btn btn_white" href="{{ route('account.index') }}" up-target=".main-body">
                            <i class="fa fa-user-o"></i> @lang('ecommerce::ecommerce.greeting_name', ['name' => auth()->user()->name])
                        </a>
                        @else
                        <a class="btn btn_white"
                            href="{{ route('auth.login') }}"
                            up-follow="false">
                            <i class="fa fa-user-o"></i> @lang('ecommerce::ecommerce.login')
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.handleServiceRequestClick = function() {
        function openModalById(id) {
            const modalEl = document.getElementById(id);
            if (!modalEl) return;
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                let modal = typeof bootstrap.Modal.getInstance === 'function' ? bootstrap.Modal.getInstance(modalEl) : null;
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }
                modal.show();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalEl).modal('show');
            }
        }

        @auth
        openModalById('serviceRequestModal');
        @else
        openModalById('loginRequiredModal');
        @endauth
    };

    document.addEventListener('DOMContentLoaded', function() {
        applyDirection();
        // initializeSearch();
    });

    function applyDirection() {
        const currentLang = '{{ app()->getLocale() }}';
        const html = document.documentElement;
        const body = document.body;

        if (currentLang === 'ar') {
            html.dir = 'rtl';
            html.lang = 'ar';
            body.classList.add('rtl');
            body.classList.remove('ltr');
        } else {
            html.dir = 'ltr';
            html.lang = 'en';
            body.classList.remove('rtl');
            body.classList.add('ltr');
        }
    }

    // function initializeSearch() {
    //     const searchForm = document.getElementById('search-form');
    //     const searchInput = document.getElementById('search-input');
    //     const searchBtn = document.getElementById('search-btn');
    //     const categorySelect = document.getElementById('category-select');

    //     let searchTimeout;
    //     let lastSearchValue = '';

    //     searchBtn.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         performSearch();
    //     });

    //     searchInput.addEventListener('keypress', function(e) {
    //         if (e.key === 'Enter') {
    //             e.preventDefault();
    //             performSearch();
    //         }
    //     });

    //     searchInput.addEventListener('input', function(e) {
    //         const value = e.target.value.trim();

    //         clearTimeout(searchTimeout);

    //         if (value.length >= 3 && value !== lastSearchValue) {
    //             searchTimeout = setTimeout(() => {
    //                 performSearch();
    //             }, 1000);
    //         }
    //     });

    //     categorySelect.addEventListener('change', function(e) {
    //         if (searchInput.value.trim().length >= 2) {
    //             performSearch();
    //         }
    //     });

    //     function performSearch() {
    //         const searchValue = searchInput.value.trim();

    //         if (searchValue.length < 2) {
    //             showSearchMessage('@lang("ecommerce::ecommerce.min_search_chars")', 'warning');
    //             return;
    //         }

    //         if (searchValue === lastSearchValue) {
    //             return;
    //         }

    //         lastSearchValue = searchValue;

    //         searchBtn.disabled = true;
    //         searchBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> @lang("ecommerce::ecommerce.searching")';

    //         up.submit(searchForm, {
    //             feedback: false
    //         });

    //         setTimeout(() => {
    //             searchBtn.disabled = false;
    //             searchBtn.innerHTML = '@lang("ecommerce::ecommerce.search") <i class="fa fa-search" aria-hidden="true"></i>';
    //         }, 1000);
    //     }

    //     function showSearchMessage(message, type) {
    //         const existingMessage = document.querySelector('.search-message');
    //         if (existingMessage) {
    //             existingMessage.remove();
    //         }

    //         const messageClass = type === 'warning' ? 'alert-warning' : 'alert-info';

    //         const messageHTML = `
    //             <div class="alert ${messageClass} alert-dismissible fade show search-message"
    //                  style="position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;">
    //                 ${message}
    //                 <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    //             </div>
    //         `;

    //         document.body.insertAdjacentHTML('beforeend', messageHTML);

    //         setTimeout(() => {
    //             const messageElement = document.querySelector('.search-message');
    //             if (messageElement) {
    //                 messageElement.remove();
    //             }
    //         }, 3000);
    //     }

    //     up.on('up:fragment:inserted', function(event) {
    //         if (event.target.matches('.main-content') || event.target.closest('.main-content')) {
    //             setTimeout(initializeSearch, 100);
    //         }
    //     });
    // }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.lang-direction-btn') || e.target.closest('.lang-dir-switch')) {
            e.preventDefault();

            const currentLang = '{{ app()->getLocale() }}';
            const newLang = currentLang === 'ar' ? 'en' : 'ar';

            changeLanguageUrl(newLang);
        }
    });

    function changeLanguageUrl(newLang) {
        const currentUrl = window.location.href;
        const url = new URL(currentUrl);
        let path = url.pathname;

        // Remove existing language prefix if present (e.g. /en, /ar, /en/foo, /ar/foo)
        path = path.replace(/^\/(en|ar)(\/|$)/, '/');

        // Ensure clean path
        if (path.length > 1 && path.endsWith('/')) {
            path = path.slice(0, -1);
        }
        if (!path.startsWith('/')) {
            path = '/' + path;
        }

        // Create new path
        if (path === '/') {
            url.pathname = '/' + newLang;
        } else {
            url.pathname = '/' + newLang + path;
        }

        window.location.href = url.toString();
    }

    function switchLanguage(lang) {
        changeLanguageUrl(lang);
        return false;
    }
</script>