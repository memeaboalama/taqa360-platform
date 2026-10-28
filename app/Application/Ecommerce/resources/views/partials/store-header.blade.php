{{--
    Shared store header: brand, search, service request, language, account, wishlist and cart.
    Optional: $searchAction (default categories page), $searchPlaceholder, $showLanguageSwitcher (default true).
--}}
@php
    $storeSearchAction = $searchAction ?? route('categories.index');
    $storeSearchPlaceholder = $searchPlaceholder ?? __('ecommerce::ecommerce.search_placeholder');
    $storeShowLanguage = $showLanguageSwitcher ?? true;
@endphp
<header>
    <div class="topbar">
        <div class="container topbar-inner">
            <a class="brand" href="{{ route('ecommerce.static') }}"><img class="brand-logo"
                    src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="{{ __('ecommerce::ecommerce.site_name') }}"></a>
            <form class="search" id="store-search" method="get" action="{{ $storeSearchAction }}"><button type="submit"
                    aria-label="{{ __('ecommerce::ecommerce.search') }}">⌕</button><input name="q" type="search"
                    placeholder="{{ $storeSearchPlaceholder }}" aria-label="{{ __('ecommerce::ecommerce.search') }}"></form>
            <div class="top-actions"><button type="button" class="service" onclick="handleServiceRequestClick()"><i
                        class="fa fa-plus-circle"></i>
                    @lang('ecommerce::ecommerce.request_service')</button>@if($storeShowLanguage)@include('ecommerce::partials.language-switcher')@endif<a
                    class="action" href="{{ route('account.index') }}"
                    aria-label="@lang('ecommerce::ecommerce.my_account')"><i
                        class="fa fa-user-o"></i></a>@include('ecommerce::partials.wishlist-widget')@include('ecommerce::partials.cart-widget')
                <button type="button" class="action mobile-search-toggle" aria-label="@lang('ecommerce::ecommerce.search')"
                    aria-controls="store-search" aria-expanded="false" data-mobile-search-toggle>⌕</button>
                <button type="button" class="action mobile-menu-toggle" aria-label="@lang('ecommerce::ecommerce.menu')"
                    aria-controls="store-navigation" aria-expanded="false" data-mobile-menu-toggle>
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</header>
