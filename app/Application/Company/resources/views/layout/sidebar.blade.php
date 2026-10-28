<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header" style="border: none">
        <a href="#" class="header-logo">
            <img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="TAQA" class="desktop-logo" width="112"
                style="display: block; width: 112px; max-width: calc(100% - 24px); height: auto; max-height: 88px; object-fit: contain; margin: 6px auto 2px">
            <img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="TAQA" class="toggle-logo" width="64">
            <img src="{{ asset('assets/ecommerce/images/logo-white.png') }}" alt="TAQA" class="desktop-dark" width="112">
            <img src="{{ asset('assets/ecommerce/images/logo-white.png') }}" alt="TAQA" class="toggle-dark" width="64">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Main</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('company:home') }}" class="side-menu__item {{ isActive(url('company/home')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">@lang('company::home.home')</span>
                    </a>
                </li>
                <!-- End::slide -->

                @CompanyHasRole(['show_orders'])
                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="{{ route('company:orders') }}" class="side-menu__item {{ isActive(url('company/orders')) }}">
                            <i class="bx bx-shopping-bag side-menu__icon"></i>
                            <span class="side-menu__label">@lang('company::home.Company Orders')</span>
                        </a>
                    </li>
                    <!-- End::slide -->
                @endCompanyHasRole

                @CompanyHasRole(['show_drivers'])
                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="{{ route('company:drivers') }}" class="side-menu__item {{ (isActive(url('company/drivers')) || request()->routeIs('company:drivers.show')) ? 'active' : '' }}">
                            <i class="bx bxs-truck side-menu__icon"></i>
                            <span class="side-menu__label">@lang('company::home.drivers')</span>
                        </a>
                    </li>
                    <!-- End::slide -->
                @endCompanyHasRole

                @CompanyHasRole(['track_drivers'])
                    <li class="slide">
                        <a href="{{ url('company/drivers/tracking') }}" class="side-menu__item {{ isActive(url('company/drivers/tracking')) }}">
                            <i class="bx bxs-map side-menu__icon"></i>
                            <span class="side-menu__label">@lang('company::home.trackingDrivers')</span>
                        </a>
                    </li>
                @endCompanyHasRole

                @CompanyHasRole(['show_roles'])
                    <li class="slide">
                        <a href="{{ url('company/roles') }}" class="side-menu__item {{ isActive(url('company/roles')) }}">
                            <i class="bx bxs-tag-alt side-menu__icon"></i>
                            <span class="side-menu__label">@lang('company::home.roles')</span>
                        </a>
                    </li>
                @endCompanyHasRole

                @CompanyHasRole(['show_employees'])
                    <li class="slide">
                        <a href="{{ url('company/employees') }}" class="side-menu__item {{ isActive(url('company/employees')) }}">
                            <i class="bx bxs-user side-menu__icon"></i>
                            <span class="side-menu__label">@lang('company::home.employees')</span>
                        </a>
                    </li>
                @endCompanyHasRole


            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>