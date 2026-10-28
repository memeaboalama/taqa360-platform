<!doctype html>
@php $dirIsRtl = in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp
<html lang="{{ app()->getLocale() }}" dir="{{ $dirIsRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('ecommerce::ecommerce.auth.verify_email') - {{ __('ecommerce::ecommerce.site_name') }}</title>
    <link rel="icon" href="{{ asset('assets/ecommerce/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/themify.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('ecommerce::partials.static-auth-css')
    <style>
        #otp-inputs-wrapper {
            display: flex;
            direction: ltr;
            gap: 16px;
            margin: 30px 0;
            position: relative;
            z-index: 1;
            justify-content: center;
        }

        #otp-inputs-wrapper .otp-input {
            width: 60px;
            height: 60px;
            text-align: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 32px !important;
            font-weight: 500 !important;
            color: #1d1d1f !important;
            background: #fff !important;
            border: 1px solid #d1d1d6;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);
            padding: 0 !important;
            margin: 0 !important;
            line-height: normal !important;
            text-indent: 0 !important;
            box-shadow: none !important;
            text-shadow: none !important;
            opacity: 1 !important;
            -webkit-text-fill-color: #1d1d1f !important;
            -webkit-text-security: none !important;
            -webkit-appearance: none;
            -moz-appearance: textfield;
            appearance: none;
            caret-color: #0071e3;
        }

        #otp-inputs-wrapper .otp-input:focus {
            border-color: #0071e3;
            outline: none;
            box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1) !important;
            transform: scale(1.05);
            z-index: 2;
        }

        #otp-inputs-wrapper .otp-input.filled {
            border-color: #86868b;
            background-color: #fff !important;
            color: #1d1d1f !important;
        }

        #otp-inputs-wrapper .otp-input::-webkit-outer-spin-button,
        #otp-inputs-wrapper .otp-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #resendOtpBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            color: #86868b;
        }

        @media (max-width: 800px) {
            #otp-inputs-wrapper {
                gap: 10px;
            }
            #otp-inputs-wrapper .otp-input {
                width: 48px;
                height: 48px;
                font-size: 26px !important;
            }
        }
    </style>
</head>
<body>
@include('ecommerce::partials.static-auth-header', ['authPage' => 'verify'])
@include('ecommerce::partials.static-auth-hero', ['heroTitle' => __('ecommerce::ecommerce.auth.verify_email')])
<main class="login-shell">
    <div class="container login-layout auth-layout auth-layout-narrow">
        <section class="login-panel login-card">
            <div class="panel-heading"><h2>@lang('ecommerce::ecommerce.auth.verify_email_title')</h2></div>

            @if(session('debugOtp'))
                <div class="alert alert-info" style="background:#eef6ff;color:#1c5fb0"><strong>@lang('ecommerce::ecommerce.auth.debug_otp'):</strong> <b>{{ session('debugOtp') }}</b></div>
            @endif
            @if(session('success'))
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif

            <p class="auth-hint">@lang('ecommerce::ecommerce.auth.otp_sent_to')</p>
            <strong class="auth-email">{{ session('ecommerce_user_email_for_verification') }}</strong>

            <form method="POST" action="{{ route('verify.post') }}" id="verifyForm">
                @csrf
                <div class="otp-container" id="otp-inputs-wrapper" dir="ltr">
                    <input type="text" class="otp-input" maxlength="1" data-index="0" autocomplete="off" inputmode="numeric" pattern="[0-9]">
                    <input type="text" class="otp-input" maxlength="1" data-index="1" autocomplete="off" inputmode="numeric" pattern="[0-9]">
                    <input type="text" class="otp-input" maxlength="1" data-index="2" autocomplete="off" inputmode="numeric" pattern="[0-9]">
                    <input type="text" class="otp-input" maxlength="1" data-index="3" autocomplete="off" inputmode="numeric" pattern="[0-9]">
                    <input type="text" class="otp-input" maxlength="1" data-index="4" autocomplete="off" inputmode="numeric" pattern="[0-9]">
                    <input type="text" class="otp-input" maxlength="1" data-index="5" autocomplete="off" inputmode="numeric" pattern="[0-9]">
                </div>
                <input type="hidden" name="otp" id="fullOtp" value="{{ old('otp') }}" required>
                @error('otp')<span class="error" style="text-align:center">{{ $message }}</span>@enderror

                <button class="submit block" type="submit" id="verifyBtn">
                    <span class="submit-text">@lang('ecommerce::ecommerce.auth.verify_account')</span>
                    <span class="loading-spinner" style="display: none;"><i class="fa fa-spinner fa-spin"></i> @lang('ecommerce::ecommerce.auth.verifying')</span>
                </button>
            </form>

            <div class="auth-switch">
                <p style="margin:0 0 4px">@lang('ecommerce::ecommerce.auth.didnt_receive_code')</p>
                <form method="POST" action="{{ route('resend.otp') }}" id="resendForm">
                    @csrf
                    <button type="submit" class="auth-resend" id="resendOtpBtn">@lang('ecommerce::ecommerce.auth.resend_otp')</button>
                </form>
                <span id="countdown" class="small" style="display:block;margin-top:6px;color:#aaa"></span>
            </div>
        </section>
    </div>
</main>
@include('ecommerce::partials.static-auth-footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const otpInputs = document.querySelectorAll('.otp-input');
    const fullOtpInput = document.getElementById('fullOtp');
    const resendBtn = document.getElementById('resendOtpBtn');
    const countdownEl = document.getElementById('countdown');
    let countdownInterval = null;
    const verifyForm = document.getElementById('verifyForm');
    const verifyBtn = document.getElementById('verifyBtn');
    const submitText = verifyBtn ? verifyBtn.querySelector('.submit-text') : null;
    const loadingSpinner = verifyBtn ? verifyBtn.querySelector('.loading-spinner') : null;

    if (verifyForm) {
        verifyForm.addEventListener('submit', function () {
            if (verifyBtn) {
                submitText.style.display = 'none';
                loadingSpinner.style.display = 'inline-block';
                verifyBtn.disabled = true;
            }
        });
    }

    if (fullOtpInput.value) {
        const initialOtp = fullOtpInput.value.slice(0, otpInputs.length).split('');
        initialOtp.forEach((char, index) => {
            otpInputs[index].value = char;
            otpInputs[index].classList.add('filled');
        });

        const firstEmptyIndex = initialOtp.length < otpInputs.length ? initialOtp.length : otpInputs.length - 1;
        setTimeout(() => otpInputs[firstEmptyIndex].focus(), 100);
    } else {
        setTimeout(() => otpInputs[0].focus(), 100);
    }

    otpInputs.forEach((input, index) => {

        input.addEventListener('input', function (e) {
            let value = e.target.value;

            value = value.replace(/[^0-9]/g, '');
            e.target.value = value.slice(0, 1);

            if (e.target.value) {
                e.target.classList.add('filled');
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                e.target.classList.remove('filled');
            }

            updateFullOtp();
        });

        input.addEventListener('keydown', function (e) {
            const key = e.key;

            if (key === 'Backspace') {
                if (!e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                    updateFullOtp();
                    e.preventDefault();
                } else if (e.target.value) {

                    e.target.value = '';
                    e.target.classList.remove('filled');
                    updateFullOtp();
                    e.preventDefault();
                }
            } else if (key === 'ArrowLeft' && index > 0) {
                otpInputs[index - 1].focus();
                e.preventDefault();
            } else if (key === 'ArrowRight' && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
                e.preventDefault();
            }
        });

        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = pasteData.replace(/[^0-9]/g, '').slice(0, otpInputs.length);

            if (numbers) {
                numbers.split('').forEach((char, charIndex) => {
                    const targetIndex = index + charIndex;

                    if (targetIndex < otpInputs.length) {
                        otpInputs[targetIndex].value = char;
                        otpInputs[targetIndex].classList.add('filled');
                    }
                });

                const nextTarget = Math.min(index + numbers.length, otpInputs.length - 1);
                 if (index + numbers.length < otpInputs.length) {
                     otpInputs[index + numbers.length].focus();
                 } else {
                     otpInputs[otpInputs.length - 1].focus();
                 }

                updateFullOtp();
            }
        });

        input.addEventListener('focus', function () {
            this.select();
        });
    });

    function updateFullOtp() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        fullOtpInput.value = otp;
    }

    if (resendBtn) {
        startCountdown();
    }

    function startCountdown() {
        resendBtn.disabled = true;
        let timer = 60;

        if (countdownInterval) clearInterval(countdownInterval);

        countdownInterval = setInterval(() => {
            countdownEl.textContent = `@lang('ecommerce::ecommerce.auth.resend_in') ${timer} @lang('ecommerce::ecommerce.auth.seconds')`;
            timer--;

            if (timer < 0) {
                clearInterval(countdownInterval);
                resendBtn.disabled = false;
                countdownEl.textContent = '';
            }
        }, 1000);
    }

    document.addEventListener('up:fragment:loaded', function() {
         if (document.getElementById('resendOtpBtn')) {
             startCountdown();
         }
    });
});
</script>
</body>
</html>