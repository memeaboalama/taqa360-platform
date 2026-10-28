@extends('ecommerce::layout.store-layout')

{{--
    Login extends the shared store layout, so the header, nav, footer and
    modals come from store-layout instead of being duplicated in a standalone
    HTML document. The login-specific stylesheet is still loaded through
    @section('page-styles') to keep the exact same visual design.
--}}
@php
    $dirIsRtl = $dirIsRtl ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true);
    $searchPlaceholder = __('ecommerce::ecommerce.search_store_placeholder');
@endphp

@section('title', __('ecommerce::ecommerce.auth.login'))

@section('active-nav', 'auth')

@section('page-styles')
    @include('ecommerce::partials.static-auth-css')
@endsection

@section('content')
    @include('ecommerce::partials.static-auth-hero', ['heroTitle' => __('ecommerce::ecommerce.auth.login')])

    <div class="login-shell">
        <div class="container login-layout">

            <section class="login-panel login-card">
                <div class="panel-heading">
                    <h2>@lang('ecommerce::ecommerce.auth.login')</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('auth.login.post') }}">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ request('redirect', session('url.intended', route('home'))) }}">

                    <div class="field">
                        <label for="email">@lang('ecommerce::ecommerce.auth.email')</label>
                        <input id="email" name="email" type="email" dir="ltr" value="{{ old('email') }}" autocomplete="username"
                            autofocus required>
                        @error('email')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password">@lang('ecommerce::ecommerce.auth.password')</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                        @error('password')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="login-options">
                        <label class="remember">
                            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            @lang('ecommerce::ecommerce.auth.remember_me')
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}">@lang('ecommerce::ecommerce.auth.forgot_password')</a>
                        @endif
                    </div>

                    <button class="submit" type="submit">@lang('ecommerce::ecommerce.auth.login')</button>
                </form>
            </section>

            <section class="login-panel register-panel">
                <div class="panel-heading">
                    <h2>@lang('ecommerce::ecommerce.auth.new_customer')</h2>
                </div>
                <p class="register-copy">@lang('ecommerce::ecommerce.auth.register_description')</p>
                <a class="register-button" href="{{ route('auth.register') }}">@lang('ecommerce::ecommerce.auth.create_account')</a>
            </section>

        </div>
    </div>
@endsection
