{{--
    Shared main navigation.
    Optional: $activeNav = home|categories|merchants|wishlist|cart|account|offers|auth
    'auth' is used by the login page: it keeps the standard items and appends
    the login link as the active entry.
--}}
@php $storeActiveNav = $activeNav ?? 'home'; @endphp
<nav class="nav" id="store-navigation" aria-label="@lang('ecommerce::ecommerce.menu')">
    <ul>
        <li class="mobile-nav-close">
            <a class="mobile-nav-brand" href="{{ route('ecommerce.static') }}">
                <img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="{{ __('ecommerce::ecommerce.site_name') }}">
            </a>
            <button type="button" aria-label="@lang('ecommerce::ecommerce.close')" data-mobile-menu-close>&times;</button>
        </li>
        <li><a class="{{ $storeActiveNav === 'home' ? 'active' : '' }}" href="{{ route('ecommerce.static') }}">@lang('ecommerce::ecommerce.home')</a></li>
        <li><a class="{{ $storeActiveNav === 'categories' ? 'active' : '' }}" href="{{ route('categories.index') }}">@lang('ecommerce::ecommerce.categories.categories')</a></li>
        <li><a class="{{ $storeActiveNav === 'merchants' ? 'active' : '' }}" href="{{ route('merchants.index') }}">@lang('ecommerce::ecommerce.merchants_list')</a></li>
        {{-- [TEMP DISABLED] تبويب العروض الخاصة معلق مؤقتاً
        <li><a class="{{ $storeActiveNav === 'offers' ? 'active' : '' }}" href="{{ route('offers.index') }}">@lang('ecommerce::ecommerce.special_offers')</a></li>
        --}}
        @if($storeActiveNav === 'auth')
            <li><a class="active" href="{{ route('auth.login') }}">@lang('ecommerce::ecommerce.auth.login')</a></li>
        @endif
        <li class="mobile-service-item">
            <button type="button" class="service" onclick="handleServiceRequestClick()">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                @lang('ecommerce::ecommerce.request_service')
            </button>
        </li>
    </ul>
</nav>
