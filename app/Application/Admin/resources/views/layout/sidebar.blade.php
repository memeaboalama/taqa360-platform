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
                <li class="slide__category"><span class="category-name">{{ __('admin::lang.main') }}</span></li>
                <!-- End::slide__category -->


                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ url('admin/home') }}" class="side-menu__item {{ isActive(url('admin/home')) }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::home.home') }}</span>
                    </a>
                </li>
                <!-- Banner Management System - Two Separate Sections -->

                <li class="slide has-sub {{ isActiveSection([url('admin/banner-spaces'), url('admin/ads-content')]) }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([url('admin/banner-spaces'), url('admin/ads-content')]) ? 'active' : '' }}">
                        <i class="bx bx-layout side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::lang.Banner Spaces') }}</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="{{ route('admin:banner-spaces.index') }}" class="side-menu__item {{ isActive(route('admin:banner-spaces.index')) }}">
                                {{-- <i class="bx bx-layout side-menu__icon"></i> --}}
                                <!-- <i class='bx bx-slider-alt'></i> -->
                                <span class="side-menu__label">{{ __('admin::lang.Spaces Settings') }}</span>
                            </a>
                        </li>

                        <li class="slide">
                            <a href="{{ route('admin:ads-content.index') }}" class="side-menu__item {{ isActive(route('admin:ads-content.index')) }}">
                                {{-- <i class="bx bx-images side-menu__icon"></i> --}}
                                <span class="side-menu__label">{{ __('admin::lang.Ads Content') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @AdminHasRole(['show_merchants'])
                    <li class="slide has-sub {{ isActiveSection([url('admin/merchants'), url('admin/packages'), url('admin/typeservices')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([url('admin/merchants'), url('admin/packages'), url('admin/typeservices')]) ? 'active' : '' }}">
                            <i class="bx bx-restaurant side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.Merchants') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ url('admin/merchants') }}"
                                    class="side-menu__item {{ isActive(url('admin/merchants')) }}">{{ __('admin::home.Merchants All') }} </a>
                            </li>

                            <li class="slide">
                                <a href="{{ url('admin/merchants/pending') }}"
                                    class="side-menu__item {{ isActive(url('admin/merchants/pending')) }}">{{ __('admin::home.pending') }} </a>
                            </li>
                            @AdminHasRole(['show_merchant packages'])
                                <li class="slide">
                                    <a href="{{ url('admin/packages') }}" class="side-menu__item {{ isActive(url('admin/packages')) }}">
                                        <span class="side-menu__label">{{ __('admin::home.Merchant Packages') }}</span>
                                    </a>
                                </li>


                            @endAdminHasRole
                            @AdminHasRole(['track_merchants'])
                                <li class="slide">
                                    <a href="{{ url('admin/merchants/tracking') }}" class="side-menu__item {{ isActive(url('admin/merchants/tracking')) }}">


                                        {{ __('admin::home.Tracking Merchants') }}
                                    </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['show_merchant packages'])
                                <li class="slide">
                                    <a href="{{ url('admin/typeservices') }}" class="side-menu__item {{ isActive(url('admin/typeservices')) }}">
                                        <span class="side-menu__label">{{ __('admin::home.TypeService bind city') }}</span>
                                    </a>
                                </li>


                            @endAdminHasRole
                        </ul>
                    </li>
                @endAdminHasRole



                {{-- @AdminHasRole(['show_operating companies'])
                <li class="slide">
                    <a href="{{ url('admin/companies') }}" class="side-menu__item">
                        <i class="bx bx-building-house side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::home.operatingCompanies') }}</span>
                    </a>
                </li>
                @endAdminHasRole

                @AdminHasRole(['show_drivers'])
                    <li class="slide has-sub {{ isActiveSection([url('admin/drivers')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([url('admin/drivers')]) ? 'active' : '' }}">
                            <i class="bx bxs-truck side-menu__icon"></i>
                            <!-- <i class='bx bx-slider-alt'></i> -->
                            <span class="side-menu__label">{{ __('admin::home.Drivers') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @AdminHasRole(['show_drivers'])
                                <li class="slide">
                                    <a href="{{ url('admin/drivers') }}" class="side-menu__item {{ isActive(url('admin/drivers')) }}">{{ __('admin::home.Drivers') }}
                                    </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['show_drivers'])
                                <li class="slide">
                                    <a href="{{ url('admin/drivers/pending') }}"
                                        class="side-menu__item {{ isActive(url('admin/drivers/pending')) }}">{{ __('admin::home.pending') }} </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['track_drivers'])
                                <li class="slide">
                                    <a href="{{ url('admin/drivers/tracking') }}" class="side-menu__item {{ isActive(url('admin/drivers/tracking')) }}">
                                        <span class="side-menu__label">{{ __('admin::home.Tracking Drivers') }}</span>
                                    </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['track_drivers'])
                                <li class="slide">
                                    <a href="{{ url('admin/drivers/history') }}" class="side-menu__item {{ isActive(url('admin/drivers/history')) }}">
                                        <span class="side-menu__label">{{ __('admin::home.Actions Drivers') }}</span>
                                    </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['track_drivers'])
                                <li class="slide">
                                    <a href="{{ url('admin/drivers/register') }}" class="side-menu__item {{ isActive(url('admin/drivers/register')) }}">
                                        <span class="side-menu__label">{{ __('admin::home.Register Drivers') }}</span>
                                    </a>
                                </li>
                            @endAdminHasRole
                        </ul>
                    </li>
                @endAdminHasRole


                @AdminHasRole(['show_educators'])
                    <li class="slide has-sub {{ isActiveSection([url('admin/educators')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([url('admin/educators')]) ? 'active' : '' }}">
                            <i class="bx bxs-truck side-menu__icon"></i>
                            <!-- <i class='bx bx-slider-alt'></i> -->
                            <span class="side-menu__label">{{ __('admin::home.Educators') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @AdminHasRole(['show_educators'])
                                <li class="slide">
                                    <a href="{{ url('admin/educators') }}"
                                        class="side-menu__item {{ isActive(url('admin/educators')) }}">{{ __('admin::home.Educators') }} </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['show_educators'])
                                <li class="slide">
                                    <a href="{{ url('admin/educators/pending') }}"
                                        class="side-menu__item {{ isActive(url('admin/educators/pending')) }}">{{ __('admin::home.pending') }} </a>
                                </li>
                            @endAdminHasRole
                        </ul>
                    </li>
                @endAdminHasRole

                @AdminHasRole(['show_salesagent'])
                    <li class="slide has-sub {{ isActiveSection([url('admin/salesagents')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([url('admin/salesagents')]) ? 'active' : '' }}">
                            <i class="bx bx-line-chart-down side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.Salse Agent') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ url('admin/salesagents') }}"
                                    class="side-menu__item {{ isActive(url('admin/salesagents')) }}">{{ __('admin::home.Salse Agent') }} </a>
                            </li>

                            <li class="slide">
                                <a href="{{ url('admin/salesagents/pending') }}"
                                    class="side-menu__item {{ isActive(url('admin/salesagents/pending')) }}">{{ __('admin::home.pending') }} </a>
                            </li>
                        </ul>
                    </li>
                @endAdminHasRole





                @AdminHasRole(['show_orders'])
                    <li class="slide">
                        <a href="{{ url('admin/merchants/orders/get') }}" class="side-menu__item {{ isActive(url('admin/merchants/orders/get')) }}">
                            <i class="bx bxs-shopping-bag side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.Orders') }}</span>
                        </a>
                    </li>
                @endAdminHasRole

                @AdminHasRole(['show_customers'])
                    <li class="slide">
                        <a href="{{ url('admin/customers') }}" class="side-menu__item {{ isActive(url('admin/customers')) }}">
                            <i class="bx bxs-user side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.customers') }}</span>
                        </a>
                    </li>
                @endAdminHasRole

                {{-- @AdminHasRole([
                        'show merchant order report_reports',
                        'show driver order report_reports',
                        'show invoice delivery report_reports'
                    ])
                    <li class="slide has-sub {{ isActiveSection([route('admin:finance.reports.orders.merchant.index'), route('admin:finance.reports.orders.driver.index'), route('admin:finance.reports.drivers.invoice.delivery')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([route('admin:finance.reports.orders.merchant.index'), route('admin:finance.reports.orders.driver.index'), route('admin:finance.reports.drivers.invoice.delivery')]) ? 'active' : '' }}">
                            <i class="bx bxs-report side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.Finance Reports') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @AdminHasRole(['show merchant order report_reports'])
                                <li class="slide">
                                    <a href="{{ route('admin:finance.reports.orders.merchant.index') }}"
                                        class="side-menu__item {{ isActive(route('admin:finance.reports.orders.merchant.index')) }}">{{ __('admin::home.Merchant Order Report') }} </a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show driver order report_reports'])
                                <li class="slide">
                                    <a href="{{ route('admin:finance.reports.orders.driver.index') }}"
                                        class="side-menu__item {{ isActive(route('admin:finance.reports.orders.driver.index')) }}">{{ __('admin::home.Driver Order Report') }} </a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show invoice delivery report_reports'])
                                <li class="slide">
                                    <a href="{{ route('admin:finance.reports.drivers.invoice.delivery') }}"
                                        class="side-menu__item {{ isActive(route('admin:finance.reports.drivers.invoice.delivery')) }}">{{ __('admin::home.Invoice Delivery') }} </a>
                                </li>
                            @endAdminHasRole

                        </ul>
                    </li>
                @endAdminHasRole --}}

                @AdminHasRole(['show_messages'])
                    <li class="slide">
                        <a href="{{ url('admin/messages') }}" class="side-menu__item {{ isActive(url('admin/messages')) }}">
                            <i class="bx bxs-inbox side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.Messages') }}</span>
                        </a>
                    </li>
                @endAdminHasRole




                @AdminHasRole(['show_categories', 'show_tags'])
                    <li class="slide has-sub {{ isActiveSection([route('admin:categories.marketplace'), route('admin:categories.main')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([route('admin:categories.marketplace'), route('admin:categories.main')]) ? 'active' : '' }}">
                            <i class="bx bx-tag-alt side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.categories') }} &
                                {{ __('admin::home.cats') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @AdminHasRole(['show_categories'])
                                <li class="slide">
                                    <a href="{{ route('admin:categories.marketplace') }}"
                                        class="side-menu__item {{ isActive(route('admin:categories.marketplace')) }}">{{ __('admin::home.categories') }} </a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show_tags'])
                                <li class="slide">
                                    <a href="{{ route('admin:categories.main') }}"
                                        class="side-menu__item {{ isActive(route('admin:categories.main')) }}">{{ __('admin::home.cats') }} </a>
                                </li>
                            @endAdminHasRole
                        </ul>
                    </li>
                @endAdminHasRole

                @AdminHasRole([
                        'show_banners',
                        'show_cities',
                        'show_countries',
                        'show_regions',
                        'show_districts',
                        'show_car_types',
                        'show_privacy policy',
                        'show_terms & conditions'
                    ])
                    <li class="slide has-sub {{ isActiveSection([route('admin:vars.specifications'), route('admin:vars.brands.index'), route('admin:vars.car-types'), route('admin:vars.banners'), route('admin:countries.index'), route('admin:cities.index'), route('admin:regions.index'), route('admin:districts.index'), route('admin:terms.update'), route('admin:policies.update')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([route('admin:vars.specifications'), route('admin:vars.brands.index'), route('admin:vars.car-types'), route('admin:vars.banners'), route('admin:countries.index'), route('admin:cities.index'), route('admin:regions.index'), route('admin:districts.index'), route('admin:terms.update'), route('admin:policies.update')]) ? 'active' : '' }}">
                            <i class="bx bx-atom side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.Vars') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @AdminHasRole(['show_specifications'])
                                <li class="slide">
                                    <a href="{{ route('admin:vars.specifications') }}"
                                        class="side-menu__item {{ isActive(route('admin:vars.specifications')) }}">{{ __('admin::home.Specifications') }} </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['show_brands'])
                                <li class="slide">
                                    <a href="{{ route('admin:vars.brands.index') }}"
                                        class="side-menu__item {{ isActive(route('admin:vars.brands.index')) }}">{{ __('admin::lang.Brands') }} </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['show_car_types'])
                                <li class="slide">
                                    <a href="{{ route('admin:vars.car-types') }}"
                                        class="side-menu__item {{ isActive(route('admin:vars.car-types')) }}">{{ __('admin::lang.Car Types') }} </a>
                                </li>
                            @endAdminHasRole
                            @AdminHasRole(['show_banners'])
                                <li class="slide">
                                    <a href="{{ route('admin:vars.banners') }}"
                                        class="side-menu__item {{ isActive(route('admin:vars.banners')) }}">{{ __('admin::home.Banners') }} </a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show_countries'])
                                <li class="slide">
                                    <a href="{{ route('admin:countries.index') }}"
                                        class="side-menu__item {{ isActive(route('admin:countries.index')) }}">{{ __('admin::home.Countries') }}</a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show_cities'])
                                <li class="slide">
                                    <a href="{{ route('admin:cities.index') }}"
                                        class="side-menu__item {{ isActive(route('admin:cities.index')) }}">{{ __('admin::home.Cities') }}</a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show_regions'])
                                <li class="slide">
                                    <a href="{{ route('admin:regions.index') }}"
                                        class="side-menu__item {{ isActive(route('admin:regions.index')) }}">{{ __('admin::home.Regions') }}</a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show_districts'])
                                <li class="slide">
                                    <a href="{{ route('admin:districts.index') }}"
                                        class="side-menu__item {{ isActive(route('admin:districts.index')) }}">{{ __('admin::home.Districts') }}</a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show_terms & conditions'])
                                <li class="slide">
                                    <a href="{{ route('admin:terms.update') }}"
                                        class="side-menu__item {{ isActive(route('admin:terms.update')) }}">{{ __('admin::home.Terms & Conditions') }}</a>
                                </li>
                            @endAdminHasRole

                            @AdminHasRole(['show_privacy policy'])
                                <li class="slide">
                                    <a href="{{ route('admin:policies.update') }}"
                                        class="side-menu__item {{ isActive(route('admin:policies.update')) }}">{{ __('admin::home.Privacy Policy') }}</a>
                                </li>
                            @endAdminHasRole

                        </ul>
                    </li>
                @endAdminHasRole

                {{-- @AdminHasRole(['show_specifications']) --}}
                <li class="slide has-sub {{ isActiveSection([route('admin:catalogs.categories'), route('admin:catalogs.subcategories'), route('admin:catalogs.chaildCategories')]) }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([route('admin:catalogs.categories'), route('admin:catalogs.subcategories'), route('admin:catalogs.chaildCategories')]) ? 'active' : '' }}">
                        <i class="bx bx-tag-alt side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::catalog.categories') }}</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        @AdminHasRole(['show_specifications'])
                            <li class="slide">
                                <a href="{{ route('admin:catalogs.categories') }}"
                                    class="side-menu__item {{ isActive(route('admin:catalogs.categories')) }}">{{ __('admin::catalog.categories') }} </a>
                            </li>
                        @endAdminHasRole
                        @AdminHasRole(['show_specifications'])
                            <li class="slide">
                                <a href="{{ route('admin:catalogs.subcategories') }}"
                                    class="side-menu__item {{ isActive(route('admin:catalogs.subcategories')) }}">{{ __('admin::catalog.subcategories') }} </a>
                            </li>
                        @endAdminHasRole
                        @AdminHasRole(['show_specifications'])
                            <li class="slide">
                                <a href="{{ route('admin:catalogs.chaildCategories') }}"
                                    class="side-menu__item {{ isActive(route('admin:catalogs.chaildCategories')) }}">{{ __('admin::catalog.child_categories') }} </a>
                            </li>
                        @endAdminHasRole
                    </ul>
                </li>
                {{-- @endAdminHasRole --}}

                @AdminHasRole(['send notifications_general'])
                    <li class="slide">
                        <a href="{{ url('admin/notification') }}" class="side-menu__item {{ isActive(url('admin/notification')) }}">
                            <i class="bx bxs-alarm-exclamation side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.notifications') }}</span>
                        </a>
                    </li>
                @endAdminHasRole










                @AdminHasRole(['show_coupons'])
                    <li class="slide has-sub {{ isActiveSection([url('admin/coupons'), url('admin/merchantCoupons'), url('admin/offer'), url('admin/cashBack'), url('admin/merchantsCashBack')]) }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([url('admin/coupons'), url('admin/merchantCoupons'), url('admin/offer'), url('admin/cashBack'), url('admin/merchantsCashBack')]) ? 'active' : '' }}">
                            <i class="bx bxs-coupon side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.Offers') }}</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>

                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ url('admin/coupons') }}" class="side-menu__item {{ isActive(url('admin/coupons')) }}">
                                    <span class="side-menu__label">{{ __('admin::home.promocodes') }}</span>
                                </a>
                            </li>
                            @AdminHasRole(['show_merchant coupons'])
                                <li class="slide">
                                    <a href="{{ url('admin/merchantCoupons') }}" class="side-menu__item {{ isActive(url('admin/merchantCoupons')) }}">
                                        <span class="side-menu__label">{{ __('admin::home.promocodes Merchants') }}</span>
                                    </a>
                                </li>
                            @endAdminHasRole
                            <li class="slide">
                                <a href="{{ url('admin/offer') }}" class="side-menu__item {{ isActive(url('admin/offer')) }}">
                                    <span class="side-menu__label">{{ __('admin::home.offers') }}</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ url('admin/cashBack') }}" class="side-menu__item {{ isActive(url('admin/cashBack')) }}">
                                    <span class="side-menu__label">{{ __('admin::home.Cash back') }}</span>
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ url('admin/merchantsCashBack') }}" class="side-menu__item {{ isActive(url('admin/merchantsCashBack')) }}">
                                    <span class="side-menu__label">{{ __('admin::home.Cash back Merchants') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endAdminHasRole




                <li class="slide has-sub {{ isActiveSection([url('admin/wallet/coupon'), url('admin/wallet/users')]) }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ isActiveSection([url('admin/wallet/coupon'), url('admin/wallet/users')]) ? 'active' : '' }}">
                        <i class="bx bxs-wallet-alt side-menu__icon"></i>
                        {{-- <i class='bx bx-slider-alt'></i> --}}
                        <span class="side-menu__label">{{ __('admin::home.wallet') }}</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        @AdminHasRole(['show_wallet coupons'])
                            <li class="slide">
                                <a href="{{ url('admin/wallet/coupon') }}" class="side-menu__item {{ isActive(url('admin/wallet/coupon')) }}">
                                    <span class="side-menu__label">{{ __('admin::home.walletcoupons') }}</span>
                                </a>
                            </li>
                        @endAdminHasRole
                        @AdminHasRole(['show user balances_wallet'])
                            <li class="slide">
                                <a href="{{ url('admin/wallet/users') }}" class="side-menu__item {{ isActive(url('admin/wallet/users')) }}">
                                    {{-- <i class="bx bxs-user side-menu__icon"></i> --}}
                                    <span class="side-menu__label">{{ __('admin::home.customers') }}</span>
                                </a>
                            </li>
                        @endAdminHasRole
                    </ul>
                </li>
                <!-- @AdminHasRole(['show_roles']) -->
                <li class="slide">
                    <a href="{{ url('admin/refundPayment') }}" class="side-menu__item {{ isActive(url('admin/refundPayment')) }}">
                        <i class="bx bxs-tag-alt side-menu__icon"></i>
                        <span class="side-menu__label">{{ __('admin::home.Refundpayment') }}</span>
                    </a>
                </li>
                <!-- @endAdminHasRole -->

                @AdminHasRole(['show_roles'])
                    <li class="slide">
                        <a href="{{ url('admin/roles') }}" class="side-menu__item {{ isActive(url('admin/roles')) }}">
                            <i class="bx bxs-tag-alt side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.roles') }}</span>
                        </a>
                    </li>
                @endAdminHasRole

                @AdminHasRole(['show_employees'])
                    <li class="slide">
                        <a href="{{ url('admin/employees') }}" class="side-menu__item {{ isActive(url('admin/employees')) }}">
                            <i class="bx bxs-user side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.employees') }}</span>
                        </a>
                    </li>
                @endAdminHasRole

                @AdminHasRole(['show profile_settings', 'show login/ register page content_settings', 'show search capacity_settings', 'show delivery fees_settings'])
                    <li class="slide">
                        <a href="{{ url('admin/general-settings') }}" class="side-menu__item {{ isActive(url('admin/general-settings')) }}">
                            <i class="bx bx-customize side-menu__icon"></i>
                            <span class="side-menu__label">{{ __('admin::home.General Settings') }}</span>
                        </a>
                    </li>
                @endAdminHasRole
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