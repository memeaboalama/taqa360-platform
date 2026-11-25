<!doctype html>
@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $isRTL = $isRTL ?? in_array($currentLocale, ['ar', 'he'], true);
    $isMerchantDetailPage = request()->routeIs('merchant.show');
@endphp
<html lang="{{ $currentLocale }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('ecommerce::ecommerce.home')) - {{ __('ecommerce::ecommerce.site_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset($platformBrand['favicon']) }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/bootstrap.css') }}" id="bootstrap-css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #20252d;
            --muted: #777d86;
            --line: #e9ebef;
            --yellow: {{ $platformBrand['primary_color'] }};
            --yellow-deep: {{ $platformBrand['primary_hover_color'] }};
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
            justify-content: center;
            align-items: center
        }

        .footer-brand {
            width: 100%;
            text-align: center
        }

        .footer-logo {
            display: block;
            width: auto;
            max-width: 160px;
            height: auto;
            max-height: 65px;
            object-fit: contain;
            margin: 0 auto 8px
        }

        .footer-brand p {
            margin: 0;
            font-size: 14px;
            line-height: 1.8
        }

        .footer-contact {
            margin-top: 10px
        }

        .footer-contact h2 {
            margin: 0 0 12px;
            font-size: 15px;
            font-weight: 700
        }

        .footer-contact p {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            margin: 0 0 5px;
            color: #d2d4d6;
            font-size: 12px;
            line-height: 1.7
        }

        .footer-contact p i {
            flex: 0 0 14px;
            margin-top: 4px
        }

        .footer-contact a {
            color: inherit;
            text-decoration: none;
            overflow-wrap: anywhere
        }

        .footer-contact a:hover {
            color: #fff;
            text-decoration: underline
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

        @media(max-width:800px) {
            .footer-grid {
                gap: 20px
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

        .password-change-trigger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 9px 18px;
            border: 1px solid var(--yellow);
            border-radius: 5px;
            background: var(--yellow);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background-color .2s, border-color .2s, transform .2s
        }

        .password-change-trigger:hover {
            background: var(--yellow-deep);
            border-color: var(--yellow-deep);
            color: #fff;
            transform: translateY(-1px)
        }

        .password-modal-error {
            display: block;
            margin-top: 5px
        }

        .password-modal-error:empty {
            display: none
        }

        #changePasswordModal .modal-content {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 16px 42px rgba(29, 37, 48, .18)
        }

        #changePasswordModal .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 20px;
            border-bottom-color: var(--line);
            background: var(--soft)
        }

        #changePasswordModal .modal-title {
            margin: 0;
            color: var(--ink);
            font-weight: 700
        }

        #changePasswordModal .modal-header .btn-close {
            flex: 0 0 auto;
            margin: 0
        }

        #changePasswordModal .form-label {
            color: var(--ink);
            font-weight: 600
        }

        #changePasswordModal .form-control:focus {
            border-color: var(--yellow);
            box-shadow: 0 0 0 .2rem rgba(0, 0, 0, .08)
        }

        #changePasswordModal .modal-footer {
            border-top-color: var(--line)
        }

        #changePasswordModal .modal-footer .btn-secondary {
            border-color: var(--line);
            background: var(--soft);
            color: var(--ink)
        }

        #changePasswordModal .modal-footer .btn-secondary:hover {
            border-color: var(--line);
            background: #edf0f3;
            color: var(--ink)
        }

        #changePasswordModal .password-modal-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 9px 18px;
            border: 1px solid var(--yellow);
            border-radius: 5px;
            background: var(--yellow);
            color: #fff;
            font-weight: 700
        }

        #changePasswordModal .password-modal-submit:hover {
            border-color: var(--yellow-deep);
            background: var(--yellow-deep)
        }

        #changePasswordModal .password-modal-submit:disabled {
            cursor: wait;
            opacity: .8
        }

        #changePasswordForm.is-submitting .password-modal-idle-icon {
            display: none
        }

        #changePasswordModal .password-modal-form-error {
            margin-bottom: 16px
        }
    </style>
    @yield('page-styles')
    @include('ecommerce::partials.direction-utils')
</head>

<body class="{{ $isMerchantDetailPage ? 'merchant-detail-shell' : '' }}">
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
            var fallbackErrorMessage = @json(__('ecommerce::ecommerce.server_error'));
            var passwordSuccessMessage = @json(__('ecommerce::ecommerce.password_updated'));

            function getModal(root) {
                if (!root) return null;
                return root.matches && root.matches('#changePasswordModal')
                    ? root
                    : root.querySelector('#changePasswordModal');
            }

            function getModalInstance(modal) {
                var instance = window.bootstrap.Modal.getInstance(modal);
                return instance || new window.bootstrap.Modal(modal);
            }

            function clearPasswordErrors(form) {
                form.querySelectorAll('[data-password-error]').forEach(function (element) {
                    element.textContent = '';
                });
                form.querySelectorAll('.is-invalid').forEach(function (element) {
                    element.classList.remove('is-invalid');
                });
                var formError = form.querySelector('[data-password-form-error]');
                formError.textContent = '';
                formError.classList.add('d-none');
            }

            function setPasswordSubmitting(form, isSubmitting) {
                form.dataset.submitting = isSubmitting ? 'true' : 'false';
                form.classList.toggle('is-submitting', isSubmitting);
                form.setAttribute('aria-busy', isSubmitting ? 'true' : 'false');
                form.querySelector('[type="submit"]').disabled = isSubmitting;
                form.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (button) {
                    button.disabled = isSubmitting;
                });
                form.querySelector('.password-modal-spinner').classList.toggle('d-none', !isSubmitting);
            }

            function showPasswordErrors(form, errors) {
                var firstInvalidInput = null;
                Object.keys(errors).forEach(function (fieldName) {
                    var errorElement = Array.from(form.querySelectorAll('[data-password-error]')).find(function (element) {
                        return element.dataset.passwordError === fieldName;
                    });
                    var input = Array.from(form.elements).find(function (element) {
                        return element.name === fieldName;
                    });
                    if (errorElement) errorElement.textContent = errors[fieldName].join(' ');
                    if (input) {
                        input.classList.add('is-invalid');
                        if (!firstInvalidInput) firstInvalidInput = input;
                    }
                });
                if (firstInvalidInput) firstInvalidInput.focus();
            }

            async function submitPasswordChange(form, modal) {
                if (form.dataset.submitting === 'true') return;

                clearPasswordErrors(form);
                setPasswordSubmitting(form, true);

                try {
                    var response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form),
                        credentials: 'same-origin'
                    });
                    var payload;

                    try {
                        payload = await response.json();
                    } catch (error) {
                        throw new Error(fallbackErrorMessage);
                    }

                    if (response.status === 422 && payload.errors) {
                        showPasswordErrors(form, payload.errors);
                        return;
                    }

                    if (!response.ok) {
                        throw new Error(payload.message || fallbackErrorMessage);
                    }

                    setPasswordSubmitting(form, false);
                    form.reset();
                    getModalInstance(modal).hide();
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(payload.message || passwordSuccessMessage, 'success');
                    }
                    if (payload.redirect_url) {
                        window.setTimeout(function () {
                            window.location.replace(payload.redirect_url);
                        }, 2500);
                    }
                } catch (error) {
                    var formError = form.querySelector('[data-password-form-error]');
                    formError.textContent = error.message || fallbackErrorMessage;
                    formError.classList.remove('d-none');
                } finally {
                    setPasswordSubmitting(form, false);
                }
            }

            function initializePasswordModal(root) {
                var modal = getModal(root);
                if (!modal) return;

                var form = modal.querySelector('#changePasswordForm');
                if (form && form.dataset.initialized !== 'true') {
                    form.dataset.initialized = 'true';
                    form.dataset.submitting = 'false';
                    modal.addEventListener('hide.bs.modal', function (event) {
                        if (form.dataset.submitting === 'true') event.preventDefault();
                    });
                    form.addEventListener('submit', function (event) {
                        event.preventDefault();
                        submitPasswordChange(form, modal);
                    });
                }

                if (modal.dataset.openOnError === 'true' && window.bootstrap && window.bootstrap.Modal) {
                    getModalInstance(modal).show();
                }
            }

            function initializeOnPage() {
                initializePasswordModal(document);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeOnPage);
            } else {
                initializeOnPage();
            }

            document.addEventListener('up:fragment:inserted', function (event) {
                initializePasswordModal(event.target);
            });
        })();
    </script>
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