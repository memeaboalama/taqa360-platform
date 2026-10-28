@section('title', __('admin::home.admin') . " | " . __('admin::lang.login'))
@push('css')
    <!-- Main Theme Js -->
    <script src="{{ asset('assets/vendor/js/authentication-main.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendor//libs/swiper/swiper-bundle.min.css') }}">
    <style>
        .admin-login-form-column .btn-primary,
        .admin-login-form-column .btn-primary:hover,
        .admin-login-form-column .btn-primary:focus,
        .admin-login-form-column .btn-primary:active {
            background-color: #306FAA !important;
            border-color: #306FAA !important;
            color: #fff !important;
        }

        .admin-login-form-column #button-addon2,
        .admin-login-form-column #button-addon2 i {
            color: #306FAA !important;
        }

        @media (min-width: 1200px) {
            .admin-login-layout {
                height: 100vh;
                min-height: 100vh;
                overflow: hidden;
            }

            .admin-login-form-column,
            .admin-login-cover {
                height: 100vh;
                min-height: 100vh;
            }

            .admin-login-form-area {
                height: calc(100% - 92px) !important;
            }

            .admin-login-form-column .p-5 {
                padding: 2rem !important;
            }

            .admin-login-cover {
                overflow: hidden;
            }

            .admin-login-promo {
                display: block;
                width: 100%;
                max-width: 100%;
                height: auto;
                max-height: calc(100vh - 400px);
                padding: 16px !important;
                object-fit: contain;
            }
        }
    </style>

@endpush

<!DOCTYPE html>
@if(LaravelLocalization::getCurrentLocale() == 'ar')
    <html lang="ar" dir="rtl" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
        data-menu-styles="light" style="--primary-rgb: {{ config('app.primary_rgp', '48, 111, 170') }};" data-toggled="close">
    @elseif(LaravelLocalization::getCurrentLocale() == 'en')
        <html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
            data-menu-styles="light" style="--primary-rgb: {{ config('app.primary_rgp', '48, 111, 170') }};" data-toggled="close">
        @endif

@include('admin::layout.head')

<body class="bg-white">
    @include('admin::layout.switcher')
    <div class="row authentication mx-0 admin-login-layout">

        <div class="col-xxl-6 col-xl-6 col-lg-6 admin-login-form-column">
            @if(LaravelLocalization::getCurrentLocale() == "ar")
                <h3 class="mt-4 gy-3 m-4" style="height:auto !important;">
                    <a
                        class="dropdown-item d-flex align-items-center"
                        hreflang="en"
                        href="{{ LaravelLocalization::getLocalizedURL("en", null, [], true) }}">
                                    <span class="avatar avatar-xs lh-1 me-2 m-1">
                                        <img src="{{ asset('assets/vendor') }}/images/flags/en.jpg" alt="img">
                                    </span>
                        EN
                    </a>
                </h3>
            @elseif(LaravelLocalization::getCurrentLocale() == "en")
                <h3 class="mt-4 gy-3 m-4" style="height:auto !important;">
                    <a
                        class="dropdown-item d-flex align-items-center"
                        hreflang="ar"
                        href="{{ LaravelLocalization::getLocalizedURL("ar", null, [], true) }}">
                                    <span class="avatar avatar-xs lh-1 me-2">
                                        <img src="{{ asset('assets/vendor') }}/images/flags/ar.jpg" alt="img">
                                    </span>
                        العربية
                    </a>
                </h3>
            @endif

            <div class="row justify-content-center align-items-center h-100 admin-login-form-area">
                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="p-5">
                        <p class="h3 fw-bolder mb-4">@lang('admin::lang.Admin Login')</p>
                        <p class="h5 fw-normal mb-2">@lang('admin::lang.Hi, Welcome Back')</p>
                        <p class="mb-4 op-7 fw-normal">@lang('admin::lang.Login into your account')</p>

                        <livewire:admin::auth.login />

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-6 col-lg-6 d-xl-block d-none px-0 bg-primary admin-login-cover" style="background-color: #306FAA !important;">
            <img src="{{ asset('assets/ecommerce/images/logo-white.png') }}" alt="logo"
                class="desktop-logo mt-4 gy-3 m-4" style="height:auto !important;" width="170">
            <div class="row justify-content-center align-items-center">
                <div class="col-xxl-12 col-xl-12 col-lg-12 mt-4 gy-3 text-white">
                    <div class="p-5">
                        <h2>@lang('admin::lang.Manage Orders')</h2>
                        <h2>@lang('admin::lang.With eats Control Panel.')</h2>
                    </div>
                </div>
                <div class="col-xxl-12 col-xl-12 col-lg-12 d-xl-block px-0">
                    <img src="{{ asset('assets/vendor/images/auth.png') }}" class="p-5 admin-login-promo">
                </div>
            </div>
        </div>

    </div>
    @push('js')
        <!-- Swiper JS -->
        <script src="{{ asset('assets/vendor/libs/swiper/swiper-bundle.min.js') }}"></script>

        <!-- Internal Sing-Up JS -->
        <script src="{{ asset('assets/vendor/js/authentication.js') }}"></script>

        <!-- Show Password JS -->
        <script src="{{ asset('assets/vendor/js/show-password.js') }}"></script>
    @endpush

    @include('admin::layout.scripts')
</body>

</html>