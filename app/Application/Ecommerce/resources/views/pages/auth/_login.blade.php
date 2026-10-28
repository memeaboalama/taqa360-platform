@extends('ecommerce::layout.layout')

@section('title', __('ecommerce::ecommerce.auth.login'))

@section('content')

<style>
    .login-page .btn-solid,
    .login-page .btn-solid:hover,
    .login-page .btn-solid:focus {
        background-color: #306FAA !important;
        border-color: #306FAA !important;
        color: #ffffff !important;
    }
    .login-page .btn-solid:hover {
        background-color: #245C8E !important;
        border-color: #245C8E !important;
        color: #ffffff !important;
    }
    .login-page .form-check {
        align-items: center;
    }
    .login-page .form-check-input {
        width: 16px !important;
        height: 16px !important;
        padding: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        flex-shrink: 0;
    }
    .login-page .form-check-input:checked,
    .login-page .form-check-input:checked:focus {
        background-color: #306FAA !important;
        border-color: #306FAA !important;
    }
    .login-page .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(48, 111, 170, 0.25);
    }
    .login-page .theme-form .form-group {
        margin-bottom: 30px;
    }
    .login-page .theme-form .form-group:last-of-type {
        margin-bottom: 0;
    }
    .login-page .theme-form .form-group .form-control {
        margin-bottom: 0 !important;
    }
    .login-page .theme-form .form-group .invalid-feedback {
        margin-top: 10px;
        display: block;
    }
    .bread_crumb .functionalities ul li a,
    .bread_crumb .functionalities ul li a i,
    .bread_crumb .functionalities ul li strong i {
        color: #ffffff !important;
    }
    .bread_crumb .functionalities ul li strong i {
        display: inline-block;
    }
    [dir="rtl"] .bread_crumb .functionalities ul li strong i {
        transform: scaleX(-1);
    }
</style>

<!-- Breadcrumb Start -->
<div class="bread_crumb shop_bread_crumb">
    <div class="container">
        <div class="row">
            <div class="col-md-6 sec_low">
                <div class="functionalities">
                    <ul id="breadcrumb" class="breadcrumb">
                        <li><a title="@lang('ecommerce::ecommerce.auth.home')" href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i> @lang('ecommerce::ecommerce.auth.home')</a></li>
                        <li><strong><i class="fa fa-angle-double-right" aria-hidden="true"></i> @lang('ecommerce::ecommerce.auth.login')</strong></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-title">
                    <h2>@lang('ecommerce::ecommerce.auth.login')</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Start -->

<!-- section start -->
<section class="login-page section-b-space cmn_bg_yellow">
    <ul class="plus_decore">
        <li></li>
        <li></li>
    </ul>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3>@lang('ecommerce::ecommerce.auth.login')</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="theme-card">
                    <form class="theme-form" method="POST" action="{{ route('login.post') }}" id="loginForm" novalidate>
                        @csrf
                        <div class="form-group">
                            <label class="p-2" for="email">@lang('ecommerce::ecommerce.auth.email')</label>
                            <input type="email" dir="ltr" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email"
                                   placeholder="@lang('ecommerce::ecommerce.auth.email')"
                                   value="{{ old('email') }}" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="email-error" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label class="p-2" for="password">@lang('ecommerce::ecommerce.auth.password')</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password"
                                   placeholder="@lang('ecommerce::ecommerce.auth.enter_password')">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="password-error" style="display: none;"></div>
                        </div>
                        <div class="form-group form-check d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">@lang('ecommerce::ecommerce.auth.remember_me')</label>
                            </div>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size:12px;color:#EA993E;">
                                    @lang('ecommerce::ecommerce.auth.forgot_password')
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-solid" id="loginBtn">
                            <span class="btn-text">@lang('ecommerce::ecommerce.auth.login')</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fa fa-spinner fa-spin"></i> @lang('ecommerce::ecommerce.auth.logging_in')
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 right-login">
                <h3>@lang('ecommerce::ecommerce.auth.new_customer')</h3>
                <div class="theme-card authentication-right">
                    <h6 class="title-font">@lang('ecommerce::ecommerce.auth.create_account')</h6>
                    <p>@lang('ecommerce::ecommerce.auth.register_description')</p>
                    <a href="{{ route('auth.register') }}" class="btn btn-solid">
                        @lang('ecommerce::ecommerce.auth.create_account')
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function initLoginForm() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    if (!loginForm || !loginBtn) return;

    const btnText = loginBtn.querySelector('.btn-text');
    const btnLoading = loginBtn.querySelector('.btn-loading');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');

    const messages = {
        emailRequired: '@lang('ecommerce::ecommerce.auth.email_required')',
        emailInvalid: '@lang('ecommerce::ecommerce.auth.email_invalid')',
        passwordRequired: '@lang('ecommerce::ecommerce.auth.password_required')'
    };

    function setError(input, errorEl, message) {
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

    emailInput.addEventListener('input', function() {
        setError(emailInput, emailError, '');
    });
    passwordInput.addEventListener('input', function() {
        setError(passwordInput, passwordError, '');
    });

    loginForm.addEventListener('submit', function(e) {
        let hasError = false;

        if (!emailInput.value.trim()) {
            setError(emailInput, emailError, messages.emailRequired);
            hasError = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
            setError(emailInput, emailError, messages.emailInvalid);
            hasError = true;
        } else {
            setError(emailInput, emailError, '');
        }

        if (!passwordInput.value) {
            setError(passwordInput, passwordError, messages.passwordRequired);
            hasError = true;
        } else {
            setError(passwordInput, passwordError, '');
        }

        if (hasError) {
            e.preventDefault();
            return;
        }

        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-block';
        loginBtn.disabled = true;
    });

    @if(session('success') || session('error'))
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 300);
    @endif
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoginForm);
} else {
    initLoginForm();
}
</script>

@endsection
