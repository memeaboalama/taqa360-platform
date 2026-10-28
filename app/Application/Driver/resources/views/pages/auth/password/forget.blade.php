@section('title',__('driver::home.Driver |') . __('driver::home.forget_password'))
@push('css')
<!-- Main Theme Js -->
<script src="{{ asset('assets/vendor/js/authentication-main.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor//libs/swiper/swiper-bundle.min.css') }}">
@if(LaravelLocalization::getCurrentLocale() == 'ar')
<style>
    #signup-phone:-moz-placeholder {
        text-align: right;
    }

    #signup-phone:-ms-input-placeholder {
        text-align: right;
    }

    #signup-phone::-webkit-input-placeholder {
        text-align: right;
    }

    #signup-phone {
        direction: rtl;
    }

    #signup-phone:focus {
        text-align: left;
        direction: ltr;
    }
</style>
@endif
@endpush

<!DOCTYPE html>
@if(LaravelLocalization::getCurrentLocale() == 'en')
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="light" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};" data-toggled="close" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};">
@elseif(LaravelLocalization::getCurrentLocale() == 'ar')
<html lang="ar" dir="rtl" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="light" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};" data-toggled="close" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};">
@endif

@include('driver::layout.head')

<body class="bg-white">
    <div class="row authentication mx-0">
        <div class="col-xxl-6 col-xl-6 col-lg-6" style="height:700px">
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
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="p-5">
                        <p class="h3 fw-bolder mb-4">@lang('driver::home.Driver Forget Password')</p>
                        <p class="mb-4 op-7 fw-normal">@lang('driver::home.Enter your phone number in order to get an OTP')</p>
                        <livewire:driver::auth.password.forget />

                        <div class="text-center">
                            <p class="fs-18 text-muted mt-4">@lang('driver::home.Already have an account?') <a
                                    href="{{ route('driver:login') }}" class="text-primary">@lang('driver::home.Sign In')</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-6 col-lg-6 d-xl-block d-none px-0 bg-primary">
            <a href="{{ url('driver/login') }}"> <img src="{{ asset('assets/ecommerce/images/logo-white.png') }}" alt="logo"
                    class="desktop-logo mt-4 gy-3 m-4" style="height:auto !important;" width="120"></a>
            <div class="row justify-content-center align-items-center">
                <div class="col-xxl-12 col-xl-12 col-lg-12 mt-4 gy-3 text-white">
                    <div class="p-5">
                        @if(LaravelLocalization::getCurrentLocale() == "en")
                        {!! $settings->auth_page_content_en !!}
                        @elseif(LaravelLocalization::getCurrentLocale() == "ar")
                        {!! $settings->auth_page_content_ar !!}
                        @endif
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-8 col-lg-8 d-xl-block px-0">
                    <img src="{{ $settings->auth_page_image_path }}" width="700" class="p-5">
                </div>

                <!-- <div class="col-xxl-4 col-xl-4 col-lg-4 d-xl-block px-5">
                    <a href="#">
                        <img src="{{ asset('assets/vendor/images/appstore.png') }}" class="p-2">
                    </a>
                    <a href="#">
                        <img src="{{ asset('assets/vendor/images/playstore.png') }}" class="p-2">
                    </a>
                </div> -->
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

    @include('driver::layout.scripts')
</body>

</html>