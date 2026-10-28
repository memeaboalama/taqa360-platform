@section('title',__('driver::home.Driver |') . __('driver::home.login'))
@push('css')
<!-- Main Theme Js -->
<script src="{{ asset('assets/vendor/js/authentication-main.js') }}"></script>

@endpush

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

        <div class="col-xxl-6 col-xl-6 col-lg-6" style="height:550px">
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

            <div class="row justify-content-center align-items-center">
                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="p-5">
                        <p class="h3 fw-bolder mb-4">@lang('driver::home.Driver Register New')</p>
                        <p class="h5 fw-normal mb-2">@lang('driver::home.Driver Welcome')</p>
                        <!-- <a href="{{ route('driver:login') }}" class="btn btn-primary btn-lg mt-3">
                            <i class="ri-login-box-line me-2"></i>@lang('driver::home.login')
                        </a> -->


                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-6 col-lg-6 d-xl-block d-none px-0 bg-primary">
            <a href="{{ url('driver/login') }}"> <img src="{{ asset('assets/ecommerce/images/logo-white.png') }}" alt="logo"
                    class="desktop-logo mt-4 gy-3 m-4" style="height:auto !important;" width="120"></a>
            <div class="row justify-content-center align-items-center">



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