<!doctype html>
@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $isRTL = $isRTL ?? in_array($currentLocale, ['ar', 'he'], true);
@endphp
<html lang="{{ $currentLocale }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('ecommerce::ecommerce.home')) - {{ __('ecommerce::ecommerce.site_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/ecommerce/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/themify.css') }}">
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/css/bootstrap.rtl.min.css') }}"
            id="bootstrap-css">
    @else
        <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/bootstrap.css') }}" id="bootstrap-css">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #20252d;
            --muted: #777d86;
            --line: #e9ebef;
            --yellow: #306FAA;
            --yellow-deep: #245C8E;
            --soft: #f7f8f9;
            --shadow: 0 5px 18px rgba(29, 37, 48, .055)
        }

        * {
            box-sizing: border-box
        }

        html {
            scroll-behavior: smooth;
            overflow-x: clip
        }

        body {
            margin: 0;
            color: var(--ink);
            background: #fff;
            font-family: 'Cairo', Tahoma, sans-serif;
            font-size: 13px;
            overflow-x: clip
        }

        button,
        input,
        select {
            font: inherit
        }

        button {
            border: 0;
            cursor: pointer
        }

        button {
            cursor: pointer;
            border: 0
        }

        .container {
            width: min(1260px, calc(100% - 40px));
            margin: auto
        }

        .topbar {
            height: 74px;
            border-bottom: 1px solid #f0f1f3;
            display: flex;
            align-items: center
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px
        }

        .brand {
            display: flex;
            align-items: center;
            min-width: 172px;
            padding: 0 8px
        }

        .brand-logo {
            display: block;
            width: 132px;
            height: 58px;
            max-height: 58px;
            object-fit: contain
        }

        .search {
            flex: 1;
            max-width: 535px;
            height: 42px;
            display: flex;
            direction: ltr;
            border: 1px solid #eceef0;
            border-radius: 9px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .025)
        }

        .search input {
            direction: {{ $isRTL ? 'rtl' : 'ltr' }};
            text-align: {{ $isRTL ? 'right' : 'left' }};
            flex: 1;
            border: 0;
            outline: 0;
            padding: 0 18px
        }

        .search button {
            width: 48px;
            background: var(--yellow);
            color: #fff;
            font-size: 18px
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 18px;
            direction: ltr;
            color: #5d6269;
            flex-direction: row-reverse
        }

        .action {
            position: relative;
            display: grid;
            place-items: center;
            background: transparent;
            color: inherit;
            font-size: 19px;
            padding: 0
        }

        .action.cart b {
            position: absolute;
            right: -9px;
            top: -10px;
            display: grid;
            place-items: center;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            background: var(--yellow);
            font-size: 10px
        }

        .service,
        .primary-btn {
            background: var(--yellow);
            color: #fff !important;
            border-radius: 5px;
            font-weight: 700
        }

        .service {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 35px;
            padding: 0 13px;
            border-radius: 5px;
            background: var(--yellow);
            font-size: 11px;
            font-weight: 700;
            gap: 6px
        }

        .language-action {
            position: relative
        }

        .language-toggle {
            width: 24px;
            height: 30px;
            background: transparent;
            color: #5d6269;
            font-size: 19px
        }

        .language-menu {
            display: none;
            position: absolute;
            z-index: 20;
            top: 39px;
            right: 50%;
            min-width: 88px;
            margin: 0;
            padding: 5px 0;
            list-style: none;
            background: #fff;
            border: 1px solid var(--line);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .12)
        }

        .language-action.open .language-menu {
            display: block
        }

        .language-menu button {
            width: 100%;
            background: transparent;
            padding: 6px 12px;
            font-size: 10px;
            text-align: start
        }

        .nav {
            height: 51px;
            position: sticky;
            top: 0;
            z-index: 15;
            background: #fff;
            border-bottom: 1px solid #f3f3f3;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .025)
        }

        .nav ul {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            list-style: none;
            margin: 0;
            padding: 0
        }

        .nav a {
            color: #333;
            font-size: 12px;
            text-decoration: none
        }

        .nav a.active,
        .nav a:hover {
            color: var(--yellow) !important;
            font-weight: 700
        }

        .view-all {
            color: var(--yellow) !important;
        }

        footer {
            background: #252b31;
            color: #fff;
            padding: 24px 0 18px;
            margin-top: 0
        }

        .footer-grid {
            display: flex;
            justify-content: center
        }

        .footer-brand {
            text-align: center
        }

        .footer-logo {
            display: block;
            width: 160px;
            max-height: 80px;
            object-fit: contain;
            margin: 0 auto 8px
        }

        .footer-brand p {
            font-size: 14px;
            line-height: 1.8
        }

        .copyright {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            border-top: 1px solid #3d4348;
            color: #afb4b8;
            font-size: 11px;
            margin-top: 15px;
            padding-top: 10px
        }

        .footer-links {
            display: flex;
            gap: 18px
        }

        .footer-links a,
        .socials a {
            color: #d2d4d6;
            font-size: 10px;
            text-decoration: none
        }

        .socials {
            display: flex;
            gap: 14px
        }

        @media(max-width:800px) {
            .container {
                width: calc(100% - 24px)
            }
        }

        @media(max-width:800px) {
            .topbar {
                height: auto;
                padding: 12px 0
            }
        }

        @media(max-width:800px) {
            .topbar-inner {
                flex-wrap: wrap;
                gap: 10px
            }
        }

        @media(max-width:800px) {
            .brand {
                min-width: 130px
            }
        }

        @media(max-width:800px) {
            .brand-logo {
                width: 120px;
                height: auto
            }
        }

        @media(max-width:800px) {
            .top-actions {
                gap: 12px
            }
        }

        @media(max-width:800px) {
            .search {
                height: 38px;
                flex-basis: 100%;
                max-width: none
            }
        }

        @media(max-width:800px) {
            .nav {
                overflow: auto
            }
        }

        @media(max-width:800px) {
            .nav ul {
                justify-content: flex-start;
                gap: 27px;
                width: max-content;
                padding: 0 10px
            }
        }

        .top-actions .action.mobile-search-toggle,
        .top-actions .action.mobile-menu-toggle,
        .nav .mobile-nav-close,
        .nav .mobile-service-item {
            display: none
        }

        @media(max-width:800px) {
            .copyright {
                flex-wrap: wrap;
                justify-content: center;
                text-align: center
            }
        }

        @media(max-width:520px) {
            .topbar {
                padding: 7px 0
            }

            .topbar-inner {
                display: grid;
                grid-template-columns: minmax(70px, 78px) minmax(0, 1fr);
                gap: 5px;
                align-items: center
            }

            .brand {
                grid-column: 1;
                grid-row: 1;
                min-width: 0;
                padding: 0
            }

            .brand-logo {
                width: 78px;
                height: 40px;
                max-height: 40px
            }

            .search {
                display: none;
                grid-column: 1 / -1;
                grid-row: 2;
                width: 100%;
                min-width: 0;
                max-width: none;
                flex-basis: auto;
                height: 38px
            }

            .search.is-open {
                display: flex
            }

            .search input {
                min-width: 0;
                padding: 0 8px;
                font-size: 11px
            }

            .search button {
                flex: 0 0 38px;
                width: 38px
            }

            .top-actions {
                grid-column: 2;
                grid-row: 1;
                justify-self: end;
                width: max-content;
                justify-content: flex-start;
                gap: 6px
            }

            .top-actions .service {
                display: none !important
            }

            .nav .mobile-service-item {
                display: block;
                margin-top: 12px;
                border: 0
            }

            .nav .mobile-service-item .service {
                display: flex;
                width: 100%;
                min-height: 42px;
                justify-content: center;
                gap: 8px;
                padding: 0 12px;
                font-size: 13px
            }

            .top-actions .action,
            .top-actions .language-toggle {
                width: 21px;
                min-width: 21px;
                height: 32px;
                font-size: 16px
            }

            .top-actions .action.mobile-search-toggle,
            .top-actions .action.mobile-menu-toggle {
                display: inline-flex
            }

            .mobile-menu-toggle {
                flex-direction: column;
                gap: 4px
            }

            .mobile-menu-toggle span {
                display: block;
                width: 17px;
                height: 2px;
                border-radius: 2px;
                background: currentColor
            }

            .nav {
                display: none;
                position: fixed;
                inset: 0;
                z-index: 100;
                width: 100%;
                height: 100vh;
                height: 100dvh;
                overflow: hidden;
                border: 0;
                background: transparent;
                box-shadow: none
            }

            .nav.is-open {
                display: block
            }

            .nav:not(.is-open) {
                display: none
            }

            .nav::before {
                position: absolute;
                inset: 0;
                background: rgba(15, 23, 42, .48);
                content: ""
            }

            .nav ul {
                position: absolute;
                inset-block: 0;
                inset-inline-start: 0;
                z-index: 1;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
                gap: 0;
                width: min(82vw, 320px);
                height: 100%;
                margin: 0;
                padding: 12px 16px;
                overflow-y: auto;
                background: #fff;
                box-shadow: 0 0 24px rgba(0, 0, 0, .14);
                transition: transform .22s ease
            }

            html[dir="rtl"] .nav ul {
                transform: translateX(105%)
            }

            html[dir="ltr"] .nav ul {
                transform: translateX(-105%)
            }

            html[dir="rtl"] .nav.is-open ul,
            html[dir="ltr"] .nav.is-open ul {
                transform: translateX(0)
            }

            .nav li:not(.mobile-nav-close) {
                border-bottom: 1px solid var(--line)
            }

            .nav li:not(.mobile-nav-close) a {
                display: block;
                padding: 13px 0;
                font-size: 14px
            }

            .nav .mobile-nav-close {
                display: flex;
                align-items: center;
                justify-content: space-between;
                min-height: 58px;
                padding-bottom: 8px;
                border-bottom: 1px solid var(--line)
            }

            .nav .mobile-nav-brand {
                display: inline-flex;
                align-items: center;
                padding: 0;
                border: 0
            }

            .nav .mobile-nav-brand img {
                display: block;
                width: 92px;
                height: 42px;
                object-fit: contain
            }

            .mobile-nav-close button {
                width: 38px;
                height: 38px;
                background: transparent;
                color: var(--ink);
                font-size: 28px;
                line-height: 1
            }

            .top-actions .action.mobile-search-toggle {
                font-size: 21px
            }
        }

        .top-actions a {
            text-decoration: none;
        }

        .top-actions .action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 36px;
            line-height: 1;
        }

        .top-actions .service {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 35px;
            padding: 0 13px;
        }

        .top-actions .language-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 36px;
            line-height: 1;
        }

        html {
            height: 100%
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column
        }

        .site-main {
            flex: 1 0 auto
        }
    </style>
    @yield('page-styles')
    @include('ecommerce::partials.direction-utils')
</head>

<body>
    @include('ecommerce::partials.store-header')
    @php $storeActiveNav = trim($__env->yieldContent('active-nav', '')) ?: ($activeNav ?? ''); @endphp
    @include('ecommerce::partials.store-nav', ['activeNav' => $storeActiveNav])
    <main class="site-main">
        @yield('content')
    </main>
    @include('ecommerce::partials.store-footer')
    @include('ecommerce::partials.static-auth-modals')
    <script>
        (function () {
            var searchToggle = document.querySelector('[data-mobile-search-toggle]');
            var searchForm = document.getElementById('store-search');
            var menuToggle = document.querySelector('[data-mobile-menu-toggle]');
            var menuClose = document.querySelector('[data-mobile-menu-close]');
            var navigation = document.getElementById('store-navigation');

            function setSearchOpen(isOpen) {
                searchForm.classList.toggle('is-open', isOpen);
                searchToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                if (isOpen) searchForm.querySelector('input').focus();
            }

            function setMenuOpen(isOpen) {
                navigation.classList.toggle('is-open', isOpen);
                menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            searchToggle.addEventListener('click', function () {
                setSearchOpen(!searchForm.classList.contains('is-open'));
            });

            menuToggle.addEventListener('click', function () {
                setMenuOpen(true);
            });

            menuClose.addEventListener('click', function () {
                setMenuOpen(false);
                menuToggle.focus();
            });

            navigation.addEventListener('click', function (event) {
                if (event.target === navigation) setMenuOpen(false);
            });

            document.addEventListener('click', function (event) {
                if (searchForm.classList.contains('is-open') &&
                    !searchForm.contains(event.target) &&
                    !searchToggle.contains(event.target)) {
                    setSearchOpen(false);
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key !== 'Escape') return;
                setSearchOpen(false);
                if (navigation.classList.contains('is-open')) {
                    setMenuOpen(false);
                    menuToggle.focus();
                }
            });
        })();
    </script>
    @yield('scripts')
</body>

</html>