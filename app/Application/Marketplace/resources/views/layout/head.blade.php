<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="Content-Security-Policy" content="block-all-mixed-content">
    <meta name="Author" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>@yield('title')</title>
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

        [dir="rtl"] .ti-arrow-narrow-right {
            transform: rotate(180deg);
        }

        [data-page-style=classic] .card.custom-card {
            padding: 1rem !important;
        }

        .card.border-box-1 {
            border: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .btn-data-loading.btn-info-transparent:hover,
        .btn-data-loading.btn-info-transparent:focus,
        .btn-data-loading.btn-info-transparent:active,
        .btn-data-loading-nav.btn-info-transparent:hover,
        .btn-data-loading-nav.btn-info-transparent:focus,
        .btn-data-loading-nav.btn-info-transparent:active {
            outline: none !important;
            box-shadow: none !important;
            background-color: rgba(var(--info-rgb), .1) !important;
            border-color: rgba(var(--info-rgb), .1) !important;
            color: rgb(var(--info-rgb)) !important;
        }

        .btn-data-loading.btn-primary-transparent:hover,
        .btn-data-loading.btn-primary-transparent:focus,
        .btn-data-loading.btn-primary-transparent:active {
            outline: none !important;
            box-shadow: none !important;
            background-color: rgba(var(--primary-rgb), .1) !important;
            border-color: rgba(var(--primary-rgb), .1) !important;
            color: rgb(var(--primary-rgb)) !important;
        }

        .btn-data-loading.btn-danger-transparent:hover,
        .btn-data-loading.btn-danger-transparent:focus,
        .btn-data-loading.btn-danger-transparent:active {
            outline: none !important;
            box-shadow: none !important;
            background-color: rgba(var(--danger-rgb), .1) !important;
            border-color: rgba(var(--danger-rgb), .1) !important;
            color: rgb(var(--danger-rgb)) !important;
        }

        .btn-data-loading.btn-warning-transparent:hover,
        .btn-data-loading.btn-warning-transparent:focus,
        .btn-data-loading.btn-warning-transparent:active {
            outline: none !important;
            box-shadow: none !important;
            background-color: rgba(var(--warning-rgb), .1) !important;
            border-color: rgba(var(--warning-rgb), .1) !important;
            color: rgb(var(--warning-rgb)) !important;
        }

        .btn-data-loading-nav:hover,
        .btn-data-loading-nav:focus,
        .btn-data-loading-nav:active {
            outline: none !important;
            box-shadow: none !important;
        }

        .app-sidebar .main-menu .slide .side-menu__item.active {
            color: #fff !important;
            background-color: rgb(var(--primary-rgb)) !important;
            box-shadow: 0 0.25rem 0.6rem rgba(var(--primary-rgb), .35) !important;
        }

        .app-sidebar .main-menu .slide .side-menu__item.active .side-menu__icon,
        .app-sidebar .main-menu .slide .side-menu__item.active .side-menu__label,
        .app-sidebar .main-menu .slide .side-menu__item.active .side-menu__angle {
            color: #fff !important;
            fill: #fff !important;
        }

        .app-sidebar .main-menu .slide .side-menu__item.active:before {
            border-color: #fff !important;
        }
    </style>
</head>