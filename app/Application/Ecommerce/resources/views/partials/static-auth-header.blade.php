@php
    $authPage = $authPage ?? 'login';
    $authNavItems = [
        'login'    => ['label' => __('ecommerce::ecommerce.auth.login'), 'url' => route('auth.login')],
        'register' => ['label' => __('ecommerce::ecommerce.auth.register'), 'url' => route('auth.register')],
        // [DISABLED - email verification, comment-only] the "verify email" entry of the
        // auth nav. Kept commented (not deleted): uncomment to show it again. On the
        // verify page the nav now falls back to the login entry below.
        // 'verify'   => ['label' => __('ecommerce::ecommerce.auth.verify_email'), 'url' => route('verify')],
    ];
    $authNavCurrent = $authNavItems[$authPage] ?? $authNavItems['login'];
@endphp
<header><div class="topbar"><div class="container topbar-inner"><a class="brand" href="{{ route('ecommerce.static') }}"><img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="@lang('ecommerce::ecommerce.site_name')"></a><form class="search" method="get" action="{{ route('categories.index') }}"><button type="submit" aria-label="@lang('ecommerce::ecommerce.search')">⌕</button><input name="q" value="{{ request('q') }}" type="search" placeholder="@lang('ecommerce::ecommerce.search_store_placeholder')"></form><div class="top-actions"><button type="button" class="service" onclick="handleServiceRequestClick()"><i class="fa fa-plus-circle"></i> @lang('ecommerce::ecommerce.request_service')</button><a class="action" href="{{ route('account.index') }}" aria-label="@lang('ecommerce::ecommerce.my_account')"><i class="fa fa-user-o"></i></a>@include('ecommerce::partials.wishlist-widget')@include('ecommerce::partials.cart-widget')</div></div></div></header>
<nav class="nav"><ul><li><a href="{{ route('ecommerce.static') }}">@lang('ecommerce::ecommerce.home')</a></li><li><a href="{{ route('categories.index') }}">@lang('ecommerce::ecommerce.categories.categories')</a></li><li><a href="{{ route('merchants.index') }}">@lang('ecommerce::ecommerce.merchants_list')</a></li><li><a class="active" href="{{ $authNavCurrent['url'] }}">{{ $authNavCurrent['label'] }}</a></li></ul></nav>
@include('ecommerce::partials.static-auth-modals')