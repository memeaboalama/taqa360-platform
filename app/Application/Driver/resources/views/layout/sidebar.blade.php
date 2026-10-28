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
                <li class="slide__category"><span class="category-name">{{ __('driver::home.home') }}</span></li>
                <!-- End::slide__category -->
                <li class="slide">
                    <a href="{{ route('driver:home') }}" class="side-menu__item {{ isActive(route('driver:home')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('driver::home.home') }}</span>
                    </a>
                </li>

                @DriverHasRole(['show_menu', 'show_menu choices', 'show_menu addition groups'])
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="
                        bx bx-list-check side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('driver::home.menu_management') }}</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1"
                        style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(120px, 314px); box-sizing: border-box; display: none;"
                        data-popper-placement="bottom">

                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">{{ __('driver::home.menu_management') }}</a>
                        </li>

                        @DriverHasRole(['show_menu'])
                        <li class="slide">
                            <a href="{{ route('driver:services') }} {{  isActive(route('driver:services')) }}"
                                class="side-menu__item">{{ __('driver::home.services') }}</a>
                        </li>
                        @endDriverHasRole

                        @DriverHasRole(['show_menu choices'])
                        <li class="slide">
                            <a href="{{ route('driver:choices') }}"
                                class="side-menu__item {{  isActive(route('driver:choices')) }}">{{ __('driver::home.choices') }}</a>
                        </li>
                        @endDriverHasRole

                        @DriverHasRole(['show_menu addition groups'])
                        <li class="slide">
                            <a href="{{ route('driver:additions-groups') }}"
                                class="side-menu__item {{  isActive(route('driver:additions-groups')) }}">{{ __('driver::home.additions_groups') }}</a>
                        </li>
                        @endDriverHasRole
                    </ul>
                </li>
                @endDriverHasRole



                @DriverHasRole(['show_branches'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('driver:branches') }}"
                        class="side-menu__item {{ isActive(route('driver:branches')) }}">
                        <i class="bx bx-home side-menu__icon "></i>
                        <span class="side-menu__label">{{ __('driver::home.branches') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endDriverHasRole


                @DriverHasRole(['show orders_general'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('driver:orders') }}"
                        class="side-menu__item {{ isActive(route('driver:orders')) }}">
                        <i class="bx bx-shopping-bag side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('driver::home.orders') }}</span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('driver:finance.reports.orders.index') }}"
                        class="side-menu__item {{ isActive(route('driver:finance.reports.orders.index')) }}">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('driver::home.Finance Reports') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endDriverHasRole

                @DriverHasRole(['show_roles'])
                <li class="slide">
                    <a href="{{ route('driver:coupons') }}" class="side-menu__item">
                        <i class="bx bxs-coupon side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::home.promocodes') }}</span>
                    </a>
                </li>
                @endDriverHasRole

                @DriverHasRole(['show_roles'])
                <li class="slide">
                    <a href="{{ route('driver:cashbacks') }}" class="side-menu__item">
                        <i class="bx bxs-coupon side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::home.Cash back') }}</span>
                    </a>
                </li>
                @endDriverHasRole

                @DriverHasRole(['show_roles'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('driver:roles') }}"
                        class="side-menu__item {{  isActive(route('driver:roles')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('driver::home.roles') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endDriverHasRole

                @DriverHasRole(['show_employees'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('driver:employees') }}"
                        class="side-menu__item {{  isActive(route('driver:employees')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('driver::home.employees') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endDriverHasRole

                @DriverHasRole([
                    'show basic info_profile',
                    'show files_profile',
                    'show iban info_profile',
                    'show packages_profile',
                ])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('driver:profile') }}"
                        class="side-menu__item {{  isActive(route('driver:profile')) }}">
                        <i class="bx bx-user-circle side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('driver::home.profile') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endDriverHasRole

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