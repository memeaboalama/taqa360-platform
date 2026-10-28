<header class="app-header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="#" class="header-logo">
                        <img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="TAQA"
                             class="desktop-logo">
                        <img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="TAQA"
                             class="toggle-logo">
                        <img src="{{ asset('assets/ecommerce/images/logo-white.png') }}" alt="TAQA"
                             class="desktop-dark">
                        <img src="{{ asset('assets/ecommerce/images/logo-white.png') }}" alt="TAQA"
                             class="toggle-dark">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar"
                   class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                   data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">
            <!-- Start::header-element -->
            <div class="header-element country-selector">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                   data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/vendor') }}/images/flags/{{ LaravelLocalization::getCurrentLocale() }}.jpg" alt="img"
                         class="rounded-circle">
                </a>

                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a
                                class="dropdown-item d-flex align-items-center"
                                hreflang="{{ $localeCode }}"
                                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    <span class="avatar avatar-xs lh-1 me-2">
                                        <img src="{{ asset('assets/vendor') }}/images/flags/{{ $localeCode }}.jpg" alt="img">
                                    </span>
                                {{ $properties['native'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- End::header-element -->



            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown"
                   data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-3 me-0">
                            <span
                                class="avatar avatar-md bg-secondary">
                                {{ \Str::substr(resolve('_InitialMerchantName'),0,2) }}
                            </span>
                        </div>
                        <div class="d-sm-block d-none">
                            <p class="fw-semibold mb-0 lh-1">{{ resolve('_InitialMerchantName') }}</p>
                        </div>
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                    aria-labelledby="mainHeaderProfile">
                    @MerchantHasRole([
                    'show basic info_profile',
                    'show files_profile',
                    'show iban info_profile',
                    'show packages_profile',
                    ])
                    <li><a class="dropdown-item d-flex" href="{{ route('marketplace:profile') }}"><i
                                class="ti ti-user-circle fs-18 me-2 op-7"></i>{{ __('marketplace::home.profile') }}</a></li>
                    @endMerchantHasRole
                    @MerchantHasRole(['contact support_general'])
                    <li><a class="dropdown-item d-flex" href="{{ route('marketplace:sendMessage') }}"><i
                                class="ti ti-headset fs-18 me-2 op-7"></i>{{ __('marketplace::home.Support') }}</a></li>
                    @endMerchantHasRole
                    @if(auth()->guard('merchant')->check() || auth()->guard('merchant_employee')->check())
                    <li><a class="dropdown-item d-flex" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updatePasswordModal"><i
                                class="ti ti-lock fs-18 me-2 op-7"></i>{{ __('marketplace::home.Update Password') }}</a></li>
                    @endif

                    <livewire:marketplace::auth.logout/>
                </ul>
            </div>
            <!-- End::header-element -->


        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
