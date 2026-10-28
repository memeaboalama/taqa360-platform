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
                <li class="slide__category"><span class="category-name">{{ __('marketplace::home.home') }}</span></li>
                <!-- End::slide__category -->
                <li class="slide">
                    <a href="{{ route('marketplace:home') }}"
                        class="side-menu__item {{ isActive(route('marketplace:home')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('marketplace::home.home') }}</span>
                    </a>
                </li>

                <!-- @MerchantHasRole(['show_menu', 'show_menu choices', 'show_menu addition groups'])
                    <li class="slide has-sub">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="
                            bx bx-list-check side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('marketplace::home.menu_management') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1"
                            style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(120px, 314px); box-sizing: border-box; display: none;"
                            data-popper-placement="bottom">

                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0)">{{ __('marketplace::home.menu_management') }}</a>
                            </li>

                            @MerchantHasRole(['show_menu'])
                                <li class="slide">
                                    <a href="{{ route('marketplace:services') }} {{  isActive(route('marketplace:services')) }}"
                                        class="side-menu__item">{{ __('marketplace::home.services') }}</a>
                                </li>
                            @endMerchantHasRole

                            @MerchantHasRole(['show_menu choices'])
                                <li class="slide">
                                    <a href="{{ route('marketplace:choices') }}"
                                        class="side-menu__item {{  isActive(route('marketplace:choices')) }}">{{ __('marketplace::home.choices') }}</a>
                                </li>
                            @endMerchantHasRole

                            @MerchantHasRole(['show_menu addition groups'])
                                <li class="slide">
                                    <a href="{{ route('marketplace:additions-groups') }}"
                                        class="side-menu__item {{  isActive(route('marketplace:additions-groups')) }}">{{ __('marketplace::home.additions_groups') }}</a>
                                </li>
                            @endMerchantHasRole
                        </ul>
                    </li>
                @endMerchantHasRole -->

                <li class="slide">
                    <a href="{{ route('marketplace:products') }}"
                        class="side-menu__item {{ isActive(route('marketplace:products')) }}">
                        <i class="bx bxs-coupon side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::main.lang.products') }}</span>
                    </a>
                </li>



                @MerchantHasRole(['show_branches'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('marketplace:branches') }}"
                        class="side-menu__item {{ isActive(route('marketplace:branches')) }}">
                        <i class="bx bx-home side-menu__icon "></i>
                        <span class="side-menu__label">{{ __('marketplace::home.branches') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endMerchantHasRole


                @MerchantHasRole(['show orders_general'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('marketplace:orders') }}"
                        class="side-menu__item {{ isActive(route('marketplace:orders')) }}">
                        <i class="bx bx-shopping-bag side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('marketplace::home.orders') }}</span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('marketplace:finance.reports.orders.index') }}"
                        class="side-menu__item {{ isActive(route('marketplace:finance.reports.orders.index')) }}">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('marketplace::home.Finance Reports') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endMerchantHasRole

                @MerchantHasRole(['show_roles'])
                <li class="slide">
                    <a href="{{ route('marketplace:coupons') }}" class="side-menu__item {{ isActive(route('marketplace:coupons')) }}">
                        <i class="bx bxs-coupon side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::home.promocodes') }}</span>
                    </a>
                </li>
                @endMerchantHasRole

                @MerchantHasRole(['show_roles'])
                <li class="slide">
                    <a href="{{ route('marketplace:cashbacks') }}" class="side-menu__item {{ isActive(route('marketplace:cashbacks')) }}">
                        <i class="bx bxs-coupon side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::home.Cash back') }}</span>
                    </a>
                </li>
                @endMerchantHasRole

                @MerchantHasRole(['show_roles'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('marketplace:roles') }}"
                        class="side-menu__item {{  isActive(route('marketplace:roles')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('marketplace::home.roles') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endMerchantHasRole

                @MerchantHasRole(['show_employees'])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('marketplace:employees') }}"
                        class="side-menu__item {{  isActive(route('marketplace:employees')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('marketplace::home.employees') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endMerchantHasRole

                @MerchantHasRole([
                'show basic info_profile',
                'show files_profile',
                'show iban info_profile',
                'show packages_profile',
                ])
                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('marketplace:profile') }}"
                        class="side-menu__item {{  isActive(route('marketplace:profile')) }}">
                        <i class="bx bx-user-circle side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('marketplace::home.profile') }}</span>
                    </a>
                </li>
                <!-- End::slide -->
                @endMerchantHasRole

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