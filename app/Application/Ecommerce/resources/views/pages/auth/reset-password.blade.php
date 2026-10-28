<!doctype html>
@php $dirIsRtl = in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp
<html lang="{{ app()->getLocale() }}" dir="{{ $dirIsRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('ecommerce::ecommerce.auth.reset_password_title') - {{ __('ecommerce::ecommerce.site_name') }}</title>
    <link rel="icon" href="{{ asset('assets/ecommerce/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/themify.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--ink:#252525;--muted:#666;--line:#f1f3f7;--yellow:#306FAA;--input:#e8f0ff}*{box-sizing:border-box}body{margin:0;color:var(--ink);background:#f6f7f9;font:13px 'Cairo',Tahoma,sans-serif}a{text-decoration:none;color:inherit}button,input{font:inherit}button{border:0;cursor:pointer}.container{width:min(1260px,calc(100% - 40px));margin:auto}.topbar{height:74px;border-bottom:1px solid #f0f1f3;background:#fff;display:flex;align-items:center}.topbar-inner{display:flex;align-items:center;justify-content:space-between;gap:25px}.brand img{display:block;width:175px;height:76px;object-fit:contain}.nav{height:51px;background:#fff;border-bottom:1px solid #f3f3f3;box-shadow:0 3px 12px #00000008}.nav ul{height:100%;display:flex;align-items:center;justify-content:center;gap:40px;list-style:none;margin:0;padding:0}.nav a{font-size:12px}.nav a:hover{color:#EA993E;font-weight:700}
        .forgot-hero{min-height:138px;padding:0 0 22px;color:#fff;background:linear-gradient(110deg,#102d49 0%,#173f65 52%,#245c8e 100%);display:flex;align-items:flex-end}.forgot-hero-inner{display:flex;align-items:flex-end;justify-content:space-between;gap:24px}.forgot-hero-title{margin:0;color:#fff;font-size:31px;font-weight:700;line-height:1.2}.forgot-hero-breadcrumb{display:flex;align-items:center;gap:10px;margin:0;padding:0;list-style:none;font-size:14px;font-weight:600}.forgot-hero-breadcrumb a:hover{color:var(--yellow)}.forgot-hero-breadcrumb i{font-size:12px}
        .forgot-shell{min-height:480px;padding:40px 0 70px}.forgot-card{max-width:480px;margin:0 auto;background:#fff;border:1px solid #eceef1;border-radius:6px;box-shadow:0 8px 24px rgba(36,43,53,.08);padding:36px 36px 40px}.forgot-card-heading{margin:0 0 8px;font-size:20px;font-weight:700}.forgot-card-sub{margin:0 0 26px;color:#666;font-size:13px;line-height:1.8}.alert{margin:0 0 18px;padding:10px 13px;border-radius:4px;font-size:12px}.alert-success{background:#eaf7ef;color:#237344}.alert-danger{background:#fff0f0;color:#b53a3a}.field{margin-bottom:22px}.field label{display:block;margin-bottom:7px;font-size:13px}.field input{width:100%;height:53px;padding:0 18px;border:1px solid #e1e4e8;border-radius:4px;background:#fafafa;outline:0;direction:{{ $dirIsRtl ? 'rtl' : 'ltr' }};text-align:{{ $dirIsRtl ? 'right' : 'left' }};transition:border-color .2s ease,box-shadow .2s ease}.field input:focus{background:#fff;border-color:#245C8E;box-shadow:0 0 0 3px rgba(48,111,170,.18)}.field input.is-invalid{border-color:#c0392b}.error{display:block;margin-top:6px;color:#c0392b;font-size:11px}.submit{display:inline-flex;align-items:center;justify-content:center;min-width:140px;height:44px;padding:0 24px;background:var(--yellow);color:#ffffff;font-weight:700;border-radius:4px;cursor:pointer;transition:background-color .2s ease,transform .2s ease,box-shadow .2s ease;box-shadow:0 3px 8px rgba(48,111,170,.18)}.submit:hover{background:#245C8E;transform:translateY(-1px);box-shadow:0 5px 12px rgba(48,111,170,.24)}.back-link{display:inline-flex;align-items:center;gap:7px;color:#888;font-size:12px;margin-top:18px;transition:color .2s}.back-link:hover{color:#EA993E}
        footer{padding:24px 0 18px;background:#252b31;color:#fff;text-align:center}.footer-logo{width:160px;height:65px;object-fit:contain}.footer-copy{margin:5px 0 0;color:#d2d4d6;font-size:11px}.footer-grid{display:flex;justify-content:center}.copyright{display:flex;align-items:center;justify-content:space-between;gap:20px;border-top:1px solid #3d4348;color:#afb4b8;font-size:11px;margin-top:15px;padding-top:10px}.footer-links{display:flex;gap:18px}.footer-links a,.socials a{color:#d2d4d6;font-size:10px}.socials{display:flex;gap:14px}
    </style>
</head>
<body>
<header><div class="topbar"><div class="container topbar-inner"><a class="brand" href="{{ route('ecommerce.static') }}"><img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="{{ __('ecommerce::ecommerce.site_name') }}"></a></div></div></header>
<nav class="nav"><ul>
    <li><a href="{{ route('ecommerce.static') }}">@lang('ecommerce::ecommerce.home')</a></li>
    <li><a href="{{ route('categories.index') }}">@lang('ecommerce::ecommerce.categories.categories')</a></li>
    <li><a href="{{ route('auth.login') }}">@lang('ecommerce::ecommerce.auth.login')</a></li>
</ul></nav>

<section class="forgot-hero"><div class="container forgot-hero-inner">
    <h1 class="forgot-hero-title">@lang('ecommerce::ecommerce.auth.reset_password_title')</h1>
    <ul class="forgot-hero-breadcrumb">
        <li><a href="{{ route('ecommerce.static') }}"><i class="fa fa-home" aria-hidden="true"></i> @lang('ecommerce::ecommerce.home')</a></li>
        <li><i class="fa fa-angle-double-left" aria-hidden="true"></i></li>
        <li><strong>@lang('ecommerce::ecommerce.auth.reset_password_title')</strong></li>
    </ul>
</div></section>

<main class="forgot-shell">
    <div class="container">
        <div class="forgot-card">
            <h2 class="forgot-card-heading">@lang('ecommerce::ecommerce.auth.reset_password_title')</h2>
            <p class="forgot-card-sub">@lang('ecommerce::ecommerce.auth.reset_password_description')</p>

            @if(session('success'))
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">@lang('ecommerce::ecommerce.auth.email')</label>
                    <input id="email" name="email" type="email" dir="ltr"
                           value="{{ old('email', $email ?? '') }}"
                           autocomplete="username" autofocus required
                           placeholder="@lang('ecommerce::ecommerce.auth.email')">
                    @error('email')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="password">@lang('ecommerce::ecommerce.auth.new_password')</label>
                    <input id="password" name="password" type="password"
                           autocomplete="new-password" required
                           placeholder="••••••••">
                    @error('password')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">@lang('ecommerce::ecommerce.auth.confirm_password')</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           autocomplete="new-password" required
                           placeholder="••••••••">
                    @error('password_confirmation')<span class="error">{{ $message }}</span>@enderror
                </div>

                <button class="submit" type="submit">@lang('ecommerce::ecommerce.auth.reset_password_button')</button>
            </form>

            <div>
                <a class="back-link" href="{{ route('auth.login') }}">
                    <i class="fa fa-arrow-left arrow-start"></i> @lang('ecommerce::ecommerce.auth.back_to_login')
                </a>
            </div>
        </div>
    </div>
</main>

<footer>
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            <img class="footer-logo" src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="{{ __('ecommerce::ecommerce.site_name') }}">
            <p>@lang('ecommerce::ecommerce.footer_description')</p>
        </div>
    </div>
    <div class="container copyright">
        <div class="footer-links">
            <a href="#">@lang('ecommerce::ecommerce.privacy_policy')</a>
            <a href="#">@lang('ecommerce::ecommerce.terms_conditions')</a>
            <a href="#">@lang('ecommerce::ecommerce.contact_us')</a>
        </div>
        <div class="socials">
            <a href="https://wa.me/" aria-label="@lang('ecommerce::ecommerce.whatsapp')"><i class="fa fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/" aria-label="@lang('ecommerce::ecommerce.facebook')"><i class="fa fa-facebook"></i></a>
            <a href="https://www.instagram.com/" aria-label="@lang('ecommerce::ecommerce.instagram')"><i class="fa fa-instagram"></i></a>
        </div>
        <span>@lang('ecommerce::ecommerce.copyright')</span>
    </div>
</footer>
</body>
</html>