<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>@yield('title')</title>


    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/ecommerce/images/favicon.png') }}" type="image/x-icon">

    <!-- Choices JS -->

    @if(LaravelLocalization::getCurrentLocale() == 'en')
        <link id="style" href="{{ asset('assets/vendor/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @elseif(LaravelLocalization::getCurrentLocale() == 'ar')
        <link id="style" href="{{ asset('assets/vendor/libs/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @endif

    <link rel="icon" href="{{ asset('assets/ecommerce/images/favicon.png') }}" type="image/png">


    <!-- Style Css -->
    <link href="{{ asset('assets/vendor/css/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/css/taqa-branding.css') }}" rel="stylesheet">


    <!-- Icons Css -->
    <link href="{{ asset('assets/vendor/css/icons.css') }}" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="{{ asset('assets/vendor/libs/node-waves/waves.min.css') }}" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="{{ asset('assets/vendor/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@simonwep/pickr/themes/nano.min.css') }}">

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Crop Image Css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/cropperjs/cropper.min.css') }}" />
    @livewireStyles

    @stack('css')

    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('/assets/ecommerce/fonts/cairo/Cairo-Regular.ttf') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cairo';
            src: url('/assets/ecommerce/fonts/cairo/Cairo-SemiBold.ttf') format('truetype');
            font-weight: 600;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cairo';
            src: url('/assets/ecommerce/fonts/cairo/Cairo-Bold.ttf') format('truetype');
            font-weight: 700;
            font-style: normal;
        }

        body,
        * {
            font-family: 'Cairo', sans-serif !important;
        }

        .card.border-box-1 {
            border: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open > .side-menu__item,
        .app-sidebar .main-menu .slide:not(.has-sub) > .side-menu__item.active {
            color: #fff !important;
            background-color: rgb(var(--primary-rgb)) !important;
            box-shadow: 0 0.25rem 0.6rem rgba(var(--primary-rgb), .35) !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open > .side-menu__item .side-menu__icon,
        .app-sidebar .main-menu .slide.has-sub.open > .side-menu__item .side-menu__label,
        .app-sidebar .main-menu .slide.has-sub.open > .side-menu__item .side-menu__angle,
        .app-sidebar .main-menu .slide:not(.has-sub) > .side-menu__item.active .side-menu__icon,
        .app-sidebar .main-menu .slide:not(.has-sub) > .side-menu__item.active .side-menu__label,
        .app-sidebar .main-menu .slide:not(.has-sub) > .side-menu__item.active .side-menu__angle {
            color: #fff !important;
            fill: #fff !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open > .side-menu__item:before,
        .app-sidebar .main-menu .slide:not(.has-sub) > .side-menu__item.active:before {
            border-color: #000 !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open .slide-menu .side-menu__item,
        .app-sidebar .main-menu .slide.has-sub.open .slide-menu .side-menu__item .side-menu__label {
            color: var(--menu-prime-color) !important;
            font-size: 0.85rem !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open .slide-menu .side-menu__item.active {
            color: #fff !important;
            background-color: rgb(var(--primary-rgb)) !important;
            box-shadow: none !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open .slide-menu .side-menu__item.active .side-menu__icon,
        .app-sidebar .main-menu .slide.has-sub.open .slide-menu .side-menu__item.active .side-menu__label,
        .app-sidebar .main-menu .slide.has-sub.open .slide-menu .side-menu__item.active .side-menu__angle {
            color: #fff !important;
            fill: #fff !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open .slide-menu .side-menu__item.active:before {
            border-color: #fff !important;
        }

        .app-sidebar .main-menu .slide.has-sub.open > .slide-menu {
            display: block !important;
        }
    </style>
</head>