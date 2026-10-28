@extends('ecommerce::layout.store-layout')

{{--
    Register extends the shared store layout, so the header, nav, footer and
    modals come from store-layout instead of being duplicated in a standalone
    HTML document. The auth-specific stylesheet is still loaded through
    @section('page-styles') to keep the exact same visual design.
--}}
@php
    $dirIsRtl = $dirIsRtl ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true);
    $searchPlaceholder = __('ecommerce::ecommerce.search_store_placeholder');
@endphp

@section('title', __('ecommerce::ecommerce.auth.register'))

@section('active-nav', 'auth')

@section('page-styles')
    @include('ecommerce::partials.static-auth-css')
@endsection

@section('content')
    @include('ecommerce::partials.static-auth-hero', ['heroTitle' => __('ecommerce::ecommerce.auth.register')])

    <div class="login-shell">
        <div class="container login-layout auth-layout auth-layout-narrow">
            <section class="login-panel login-card">
                <div class="panel-heading">
                    <h2>@lang('ecommerce::ecommerce.auth.create_account')</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" id="registerForm" novalidate>
                    @csrf
                    <div class="field-row">
                        <div class="field">
                            <label for="first_name">@lang('ecommerce::ecommerce.auth.first_name') *</label>
                            <input id="first_name" name="first_name" value="{{ old('first_name') }}"
                                placeholder="@lang('ecommerce::ecommerce.auth.first_name')" autofocus>
                            @error('first_name')<span class="error">{{ $message }}</span>@enderror
                            <span class="error" id="first_name-error" style="display: none;"></span>
                        </div>
                        <div class="field">
                            <label for="last_name">@lang('ecommerce::ecommerce.auth.last_name') *</label>
                            <input id="last_name" name="last_name" value="{{ old('last_name') }}"
                                placeholder="@lang('ecommerce::ecommerce.auth.last_name')">
                            @error('last_name')<span class="error">{{ $message }}</span>@enderror
                            <span class="error" id="last_name-error" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="field">
                        <label for="email">@lang('ecommerce::ecommerce.auth.email') *</label>
                        <input id="email" name="email" type="email" dir="ltr" value="{{ old('email') }}"
                            placeholder="@lang('ecommerce::ecommerce.auth.email')">
                        @error('email')<span class="error">{{ $message }}</span>@enderror
                        <span class="error" id="email-error" style="display: none;"></span>
                    </div>

                    <div class="field">
                        <label for="password">@lang('ecommerce::ecommerce.auth.password') *</label>
                        <input id="password" name="password" type="password"
                            placeholder="@lang('ecommerce::ecommerce.auth.enter_password')">
                        @error('password')<span class="error">{{ $message }}</span>@enderror
                        <span class="error" id="password-error" style="display: none;"></span>
                    </div>

                    <button class="submit block" type="submit" id="registerBtn">
                        <span class="btn-text">@lang('ecommerce::ecommerce.auth.create_account')</span>
                        <span class="btn-loading" style="display: none;"><i class="fa fa-spinner fa-spin"></i>
                            @lang('ecommerce::ecommerce.auth.creating_account')</span>
                    </button>
                </form>

                <p class="auth-switch">@lang('ecommerce::ecommerce.auth.already_have_account')
                    <a href="{{ route('auth.login') }}">@lang('ecommerce::ecommerce.auth.login_here')</a>
                </p>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            if (!registerForm || !registerBtn) return;

            const btnText = registerBtn.querySelector('.btn-text');
            const btnLoading = registerBtn.querySelector('.btn-loading');

            const fields = [
                { input: 'first_name', error: 'first_name-error', required: @json(__('ecommerce::ecommerce.auth.first_name_required')) },
                { input: 'last_name', error: 'last_name-error', required: @json(__('ecommerce::ecommerce.auth.last_name_required')) },
                { input: 'email', error: 'email-error', required: @json(__('ecommerce::ecommerce.auth.email_required')), invalid: @json(__('ecommerce::ecommerce.auth.email_invalid')) },
                { input: 'password', error: 'password-error', required: @json(__('ecommerce::ecommerce.auth.password_required')), min: @json(__('ecommerce::ecommerce.auth.password_min_length')) }
            ];

            function setError(name, message) {
                const input = document.getElementById(name);
                const errorEl = document.getElementById(name + '-error');
                if (message) {
                    input.classList.add('is-invalid');
                    errorEl.textContent = message;
                    errorEl.style.display = 'block';
                } else {
                    input.classList.remove('is-invalid');
                    errorEl.textContent = '';
                    errorEl.style.display = 'none';
                }
            }

            fields.forEach(function (field) {
                const input = document.getElementById(field.input);
                input.addEventListener('input', function () {
                    setError(field.input, '');
                });
            });

            registerForm.addEventListener('submit', function (e) {
                let hasError = false;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!document.getElementById('first_name').value.trim()) {
                    setError('first_name', fields[0].required);
                    hasError = true;
                }

                if (!document.getElementById('last_name').value.trim()) {
                    setError('last_name', fields[1].required);
                    hasError = true;
                }

                const email = document.getElementById('email').value.trim();
                if (!email) {
                    setError('email', fields[2].required);
                    hasError = true;
                } else if (!emailPattern.test(email)) {
                    setError('email', fields[2].invalid);
                    hasError = true;
                }

                const password = document.getElementById('password').value;
                if (!password) {
                    setError('password', fields[3].required);
                    hasError = true;
                } else if (password.length < 8) {
                    setError('password', fields[3].min);
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    return;
                }

                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-block';
                registerBtn.disabled = true;
            });

            @if(session('success') || session('error'))
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 300);
            @endif
        });
    </script>
@endsection
