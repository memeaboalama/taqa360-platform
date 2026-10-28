<!DOCTYPE html>
@if(LaravelLocalization::getCurrentLocale() == 'en')
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="light" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};" data-toggled="close" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};">
@elseif(LaravelLocalization::getCurrentLocale() == 'ar')
<html lang="ar" dir="rtl" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="light" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};" data-toggled="close" style="--primary-rgb: {{ config('app.primary_rgp','48, 111, 170') }};">
@endif

{{-- head  --}}
@include('admin::layout.head')

<body>

    @if(session('login_welcome'))
        <div id="page-loader" style="position: fixed; inset: 0; z-index: 999999; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
            <span class="avatar avatar-rounded bg-primary-transparent d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                <i class="ri-user-smile-line fs-1"></i>
            </span>
            <p class="fw-bolder mb-3" style="color: #000;">@lang('admin::home.loading_dashboard_msg')</p>
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">...</span></div>
        </div>
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    var loader = document.getElementById('page-loader');
                    if (loader) {
                        loader.style.transition = 'opacity 0.4s ease';
                        loader.style.opacity = '0';
                        setTimeout(function () { if (loader.parentNode) loader.parentNode.removeChild(loader); }, 450);
                    }
                }, 500);
            });
        </script>
    @endif

    <div id="logout-loader" style="display: none; position: fixed; inset: 0; z-index: 999999; background: #ffffff; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <span class="avatar avatar-rounded bg-primary-transparent d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
            <i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ri-logout-box-r-line' : 'ri-logout-box-line' }} fs-1"></i>
        </span>
        <p class="fw-bolder mb-3" style="color: #000;">@lang('admin::home.logging_out_msg')</p>
        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">...</span></div>
    </div>
    <script>
        document.addEventListener('click', function (e) {
            var anchor = e.target && e.target.closest ? e.target.closest('[wire\\:click\\.prevent="logout"], [wire\\:click="logout"]') : null;
            if (!anchor) return;
            var loader = document.getElementById('logout-loader');
            if (!loader) return;
            loader.style.display = 'flex';
            setTimeout(function () {
                if (document.body.contains(loader)) loader.style.display = 'none';
            }, 10000);
        }, true);
    </script>

    <div class="page">
        {{-- header && navbar  --}}
        @include('admin::layout.header')

        {{-- sidebar  --}}
        @include('admin::layout.sidebar')


        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <div class="d-flex align-items-center">
                        @yield('page-header')
                        <h1 class="page-title fw-semibold fs-18 mb-0">@yield('title')</h1>
                    </div>
                    <div class="ms-md-1 ms-0">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                </div>
                <!-- Page Header Close -->

                <!-- Start::row-1 -->
                <div class="row" id='app'>
                    @yield('content')
                </div>
                <!--End::row-1 -->

            </div>
        </div>


        {{-- footer  --}}
        @include('marketplace::layout.footer')

    </div>


    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>

    <x-livewire-alert::flash />

    @include('admin::layout.scripts')

</body>

</html>