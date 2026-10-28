<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="author" content="pixelstrap">
    <meta name="description" content="Reno | The online tools store HTML Templates from pixelstrap">
    <meta name="keywords" content="Reno | The online tools store HTML Templates from pixelstrap">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-locale" content="{{ app()->getLocale() }}">
    <meta name="app-dir" content="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/ecommerce/images/favicon.png') }}" type="image/x-icon">

    <title>@hasSection('title') @yield('title') - @endif{{ $headerData['site_name'] ?? __('ecommerce::ecommerce.site_name') }}</title>

    <!-- Fav icon -->
    @if(isset($headerData['favicon']) && !empty($headerData['favicon']))
        <link rel="icon"
            href="{{ \Illuminate\Support\Facades\Storage::disk('fileDisk')->url($headerData['favicon']) }}?v={{ time() }}" />
        <link rel="apple-touch-icon"
            href="{{ \Illuminate\Support\Facades\Storage::disk('fileDisk')->url($headerData['favicon']) }}?v={{ time() }}" />
    @else
        <link rel="icon" href="{{ asset('assets/images/ecommerce/Favicon.png') }}" />
    @endif

    <!-- Font Family-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.salla.network/fonts/apple.css?v=2.0.5" rel="stylesheet">
    <link href="https://cdn.salla.network/fonts/sallaicons.css?v=1.1.2?v=2.0.5" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/font-awesome.min.css') }}">

    <!-- Themify icon -->
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/themify.css') }}">

    <!-- Flat icon icon -->
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/flaticon.css') }}">

    <!-- Bootstrap CSS -->
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/css/bootstrap.rtl.min.css') }}"
            id="bootstrap-css">
    @else
        <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/bootstrap.css') }}" id="bootstrap-css">
    @endif

    @include('ecommerce::partials.direction-utils')


    <!-- Animation CSS -->
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/animate.min.css') }}">

    <!-- Owl carousel css-->
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/owl.theme.default.min.css') }}">

    <!--Slick slider css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecommerce/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecommerce/css/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecommerce/css/slick-rtl-fix.css') }}">

    <!-- zoom css-->
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/magnific-popup.css') }}">

    <!-- Style css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecommerce/css/color5.css') }}" media="screen"
        id="color">

    <!-- RTL/LTR Banners Support -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecommerce/css/rtl-banners.css') }}" up-asset>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Unpoly CSS -->
    <link rel="stylesheet" href="https://unpkg.com/unpoly@3.7.3/unpoly.min.css">

    <!-- Swiper CSS - Must be in head for Unpoly compatibility -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">

    <!-- Swiper Categories Custom Styles - Must be in head -->
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-categories.css') }}">

    <!-- Unpoly JS - MUST be in head to survive body swaps -->
    <script src="https://unpkg.com/unpoly@3.7.3/unpoly.min.js"></script>

    <!-- Swiper JS - Must be in head for Unpoly compatibility -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/rtl.css') }}" up-asset>

    {{-- CRITICAL: Unpoly core MUST be in head to survive body swaps --}}
    @include('ecommerce::layout.unpoly-core')

    <style>
        body,
        p,
        span,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        a,
        button {
            font-family: 'Cairo', Tahoma, sans-serif !important;
        }

        .main-body {
            font-family: 'Cairo', Tahoma, sans-serif !important;
            min-height: 60vh;
            position: relative;
        }

        .loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            pointer-events: none;
        }

        .loader-wrapper.active {
            display: flex;
        }

        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #FFF;
            border-bottom-color: #306FAA;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite alternate;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .global-message {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        }

        body.rtl .global-message {
            right: auto;
            left: 20px;
            animation-name: slideInLeft;
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

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .global-message {
                right: 10px;
                left: 10px;
                min-width: auto;
            }
        }

        body.loading {
            cursor: wait;
        }

        body.loading::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9998;
        }
    </style>

    <script>
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} global-message`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        window.addEventListener('wishlist:updated', function (event) {
            showNotification(event.detail.message, event.detail.type);
        });

        window.addEventListener('cart:updated', function (event) {
            showNotification(event.detail.message, event.detail.type);
        });

        // Listen for Unpoly responses with notification headers
        up.on('up:request:loaded', function (event) {
            const notificationHeader = event.response.header('X-App-Notification');
            if (notificationHeader) {
                try {
                    const decodedData = atob(notificationHeader);
                    const notification = JSON.parse(decodedData);
                    showNotification(notification.message, notification.type);
                } catch (error) {
                    console.error('Error parsing notification:', error);
                }
            }

            const conflictModalHeader = event.response.header('X-App-Conflict-Modal');
            if (conflictModalHeader) {
                try {
                    const decoded = JSON.parse(atob(conflictModalHeader));
                    if (typeof showConfirmationModal === 'function') {
                        showConfirmationModal(
                            decoded.url,
                            decoded.message,
                            decoded.title,
                            decoded.buttonText,
                            decoded.method,
                            decoded.extraFields
                        );
                    }
                } catch (error) {
                    console.error('Error parsing conflict modal:', error);
                }
            }

            if (event.response.header('X-App-Login-Required')) {
                const modalEl = document.getElementById('loginRequiredModal');
                if (modalEl) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        let modal = typeof bootstrap.Modal.getInstance === 'function' ? bootstrap.Modal.getInstance(modalEl) : null;
                        if (!modal) modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    } else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalEl).modal('show');
                    }
                }
            }
        });

        // Guests: intercept "add to cart" and open the login modal instead of submitting
        if ({{ auth()->check() ? 'false' : 'true' }}) {
            function openLoginRequiredModal() {
                const modalEl = document.getElementById('loginRequiredModal');
                if (modalEl) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        let modal = typeof bootstrap.Modal.getInstance === 'function' ? bootstrap.Modal.getInstance(modalEl) : null;
                        if (!modal) modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    } else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalEl).modal('show');
                    }
                }
            }

            document.addEventListener('click', function (event) {
                const el = event.target.closest('[up-method="post"][up-target], form[up-target]');
                if (el && String(el.getAttribute('up-target') || '').includes('cart-header')) {
                    event.preventDefault();
                    event.stopPropagation();
                    openLoginRequiredModal();
                }
            }, true);

            document.addEventListener('submit', function (event) {
                const form = event.target.closest('form[up-target]');
                if (form && String(form.getAttribute('up-target') || '').includes('cart-header')) {
                    event.preventDefault();
                    event.stopPropagation();
                    openLoginRequiredModal();
                }
            }, true);
        }

        // Always dismiss the loading overlay after any fragment swap
        up.on('up:fragment:inserted', function () {
            if (typeof hideLoader === 'function') {
                hideLoader();
            }
        });
    </script>
    @stack('head')
</head>

{{--

<body up-main up-history="true" up-preload up-follow up-instant> --}}

    <body class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
        data-bootstrap-variant="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-locale="{{ app()->getLocale() }}"
        data-dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="loader-wrapper active">
            <div class="loader"></div>
        </div>

        <div class="ecommerce-layout">
            @include('ecommerce::layout.header')

            <main class="main-body p-0" up-main>
                @yield('content')
            </main>

            @include('ecommerce::layout.footer')
        </div>

        @include('ecommerce::layout.scripts')

        <div class="tap-top top-cls top_5">
            <div>
                <i class="fa fa-angle-double-up"></i>
            </div>
        </div>

        @include('ecommerce::partials.product.quick-view')
        @include('ecommerce::partials.login-required-modal')
        @include('ecommerce::partials.service-request-modal')
        @include('ecommerce::partials.confirmation-modal')
    </body>

</html>