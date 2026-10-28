@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    /* PRECISE FORM CARD */
    .precise-form-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        padding: 30px;
        margin-bottom: 30px;
    }

    .form-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a202c;
        margin: 0;
    }

    /* INFO BOX */
    .info-box {
        background: #ebf8ff;
        border: 1px solid #bee3f8;
        color: #2c5282;
        padding: 15px 20px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 25px;
        display: flex;
        gap: 12px;
        line-height: 1.6;
    }

    /* INPUTS */
    .precise-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #2d3748;
        background: #fff;
        transition: all 0.2s;
        outline: none;
    }
    .precise-input:focus {
        border-color: #306FAA;
        box-shadow: 0 0 0 3px rgba(48, 111, 170, 0.1);
    }
    .label-title { font-size: 13px; font-weight: 600; color: #5d6778; margin-bottom: 8px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }

    /* CURRENCY SUFFIX INSIDE AMOUNT INPUT */
    .amount-input-group {
        display: flex;
        width: 100%;
    }
    .amount-input-group .precise-input {
        font-weight: 700;
        font-size: 15px;
        flex: 1 1 auto;
        width: 1%;
        min-width: 0;
        direction: ltr;
        text-align: start;
    }
    /* LTR: input on the left -> square on the right (touching currency), rounded on the left */
    .amount-input-group .precise-input {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    /* RTL: input on the right -> square on the left (touching currency), rounded on the right */
    [dir="rtl"] .amount-input-group .precise-input {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .amount-currency {
        display: flex;
        align-items: center;
        padding: 0 16px;
        color: #718096;
        font-weight: 700;
        font-size: 13px;
        background: #f8f9fa;
        border: 1px solid #e2e8f0;
        margin-inline-start: -1px;
        border-start-start-radius: 0;
        border-end-start-radius: 0;
        border-start-end-radius: 8px;
        border-end-end-radius: 8px;
        white-space: nowrap;
    }

    .btn-withdraw-submit {
        width: 100%;
        padding: 14px;
        background: #e53e3e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center; justify-content: center; gap: 8px;
        margin-top: 10px;
    }
    .btn-withdraw-submit:hover { background: #c53030; transform: translateY(-1px); color: #fff; }

    /* MOBILE RESPONSIVE */
    @media (max-width: 768px) {
        .precise-form-card {
            padding: 20px 16px;
            margin-bottom: 20px;
        }
        .wallet-summary-card {
            padding: 18px;
            margin-bottom: 20px;
        }
        .balance-display { font-size: 24px; }
        .form-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .info-box {
            padding: 12px 14px;
            font-size: 12px;
        }
    }

    /* WALLET SUMMARY CARD */
    .wallet-summary-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 30px;
    }
    .summary-title { font-size: 16px; font-weight: 800; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px dashed #eee; color: #1a202c; }
    .balance-display { font-size: 28px; font-weight: 800; color: #306FAA; margin-bottom: 10px; }

    /* INLINE VALIDATION ERRORS */
    .sr-field-error {
        color: #c62828;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .sr-field-error::before {
        content: '\f06a';
        font-family: 'FontAwesome';
        font-size: 0.85rem;
    }
    #withdrawForm .precise-input.is-invalid {
        border-color: #c62828 !important;
    }
</style>

<div class="account-content-area w-100">
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('account.wallet.index') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1 mb-2" up-target=".dashboard-content">
                <i class="ti-arrow-left arrow-start"></i> @lang('ecommerce::ecommerce.back_to_wallet')
            </a>
            @if(empty($hide_header))
            <h2 class="fw-bold m-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.withdraw_funds')</h2>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4 rounded-3 d-flex align-items-center gap-2">
            <i class="ti-check-box"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4 rounded-3 d-flex align-items-center gap-2">
            <i class="ti-alert"></i> {{ session('error') }}
        </div>
    @endif
    @foreach($errors->all() as $error)
        <div class="alert alert-danger mb-4 rounded-3 d-flex align-items-center gap-2">
            <i class="ti-alert"></i> {{ $error }}
        </div>
    @endforeach

    <div class="row">
        <!-- Main: Withdraw Form -->
        <div class="col-lg-8 order-2 order-lg-1">
            <div class="precise-form-card">
                <div class="form-header">
                    <h4 class="form-title">@lang('ecommerce::ecommerce.submit_withdraw_request')</h4>
                </div>

                <div class="info-box">
                    <i class="ti-info-alt mt-1"></i>
                    <div>
                        <strong>@lang('ecommerce::ecommerce.note'):</strong>
                        @lang('ecommerce::ecommerce.withdraw_note')
                    </div>
                </div>

                <form id="withdrawForm" action="{{ route('account.wallet.withdraw.process') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label class="label-title">@lang('ecommerce::ecommerce.amount_to_withdraw')</label>
                        <div class="amount-input-group">
                            <input type="number" dir="ltr" class="precise-input" name="amount" min="1" max="{{ auth()->user()->balance }}" step="0.01" required placeholder="0.00">
                            <span class="amount-currency">{{ \App\Domain\AppBase\Models\Setting\Currency::symbol() }}</span>
                        </div>
                        <small class="text-muted d-block mt-2" style="font-size: 12px;">
                            @lang('ecommerce::ecommerce.available_balance'): <span id="availableBalanceHint">{{ number_format((float) auth()->user()->balance, 2) }}</span> {{ \App\Domain\AppBase\Models\Setting\Currency::symbol() }}
                        </small>
                    </div>

                    <div class="section-divider border-top pt-4 mt-0 mb-4 opacity-25"></div>

                    <h5 class="fw-bold mb-3 fs-6">@lang('ecommerce::ecommerce.bank_details')</h5>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="label-title">@lang('ecommerce::ecommerce.bank_name')</label>
                            <input type="text" class="precise-input" name="bank_name" required placeholder="@lang('ecommerce::ecommerce.bank_placeholder')">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="label-title">@lang('ecommerce::ecommerce.account_number')</label>
                            <input type="text" class="precise-input" name="account_number" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="label-title">@lang('ecommerce::ecommerce.iban')</label>
                            <input type="text" class="precise-input" name="iban" required placeholder="SA..">
                        </div>
                    </div>

                    <button type="submit" id="withdrawSubmitBtn" class="btn-withdraw-submit">
                        <span id="withdrawSubmitSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <i class="ti-arrow-up" id="withdrawSubmitIcon"></i>
                        <span>@lang('ecommerce::ecommerce.submit_withdraw_request')</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar: Balance -->
        <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
            <div class="wallet-summary-card">
                <h4 class="summary-title">@lang('ecommerce::ecommerce.wallet_summary')</h4>

                <h6 class="text-muted text-uppercase small fw-bold mb-2">@lang('ecommerce::ecommerce.available_balance')</h6>
                <div class="balance-display">
                    <span id="walletBalanceValue" data-value="{{ (float) auth()->user()->balance }}">{{ number_format((float)auth()->user()->balance, 2) }}</span> <small style="font-size: 14px; color: #a0aec0;">{{ \App\Domain\AppBase\Models\Setting\Currency::symbol() }}</small>
                </div>

                <div class="text-center mt-3 text-muted" style="font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 5px;">
                    <i class="ti-lock"></i> @lang('ecommerce::ecommerce.secure_payment')
                </div>
            </div>
        </div>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif

<script>
    (function () {
        const form = document.getElementById('withdrawForm');
        if (!form) return;

        const submitBtn = document.getElementById('withdrawSubmitBtn');
        const spinner = document.getElementById('withdrawSubmitSpinner');
        const icon = document.getElementById('withdrawSubmitIcon');
        const maxBalance = parseFloat(form.querySelector('[name="amount"]').max) || Infinity;
        const messages = {
            amount_required: @json(__('ecommerce::ecommerce.amount_required')),
            amount_number: @json(__('ecommerce::ecommerce.amount_must_be_number')),
            amount_min: @json(__('ecommerce::ecommerce.amount_min')),
            bank_name_required: @json(__('ecommerce::ecommerce.bank_name_required')),
            account_number_required: @json(__('ecommerce::ecommerce.account_number_required')),
            iban_required: @json(__('ecommerce::ecommerce.iban_required')),
        };

        function clearFieldErrors() {
            form.querySelectorAll('.sr-field-error').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
        }

        function setFieldError(field, message) {
            if (!field) return;
            const wrapper = field.closest('.col-12, .col-md-6, .mb-4') || field.parentElement;
            field.classList.add('is-invalid');
            let err = wrapper.querySelector('.sr-field-error');
            if (!err) {
                err = document.createElement('div');
                err.className = 'sr-field-error';
                wrapper.appendChild(err);
            }
            err.textContent = message;
        }

        form.querySelectorAll('.precise-input').forEach(function (field) {
            ['input', 'change'].forEach(function (evt) {
                field.addEventListener(evt, function () {
                    field.classList.remove('is-invalid');
                    const err = field.closest('.col-12, .col-md-6, .mb-4')?.querySelector('.sr-field-error');
                    if (err) err.remove();
                });
            });
        });

        function validateForm() {
            clearFieldErrors();
            let valid = true;
            let firstInvalid = null;

            const amount = form.querySelector('[name="amount"]');
            if (!amount.value.trim()) {
                setFieldError(amount, messages.amount_required);
                valid = false; firstInvalid = firstInvalid || amount;
            } else if (isNaN(amount.value)) {
                setFieldError(amount, messages.amount_number);
                valid = false; firstInvalid = firstInvalid || amount;
            } else if (Number(amount.value) < 1) {
                setFieldError(amount, messages.amount_min);
                valid = false; firstInvalid = firstInvalid || amount;
            } else if (Number(amount.value) > maxBalance) {
                setFieldError(amount, @json(__('ecommerce::ecommerce.withdraw_insufficient_funds')));
                valid = false; firstInvalid = firstInvalid || amount;
            }

            [['bank_name', messages.bank_name_required],
             ['account_number', messages.account_number_required],
             ['iban', messages.iban_required]].forEach(function ([name, msg]) {
                const field = form.querySelector('[name="' + name + '"]');
                if (!field.value.trim()) {
                    setFieldError(field, msg);
                    valid = false; firstInvalid = firstInvalid || field;
                }
            });

            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus({ preventScroll: true });
            }
            return valid;
        }

        function setLoading(loading) {
            submitBtn.disabled = loading;
            if (spinner) spinner.classList.toggle('d-none', !loading);
            if (icon) icon.classList.toggle('d-none', loading);
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!validateForm()) return;

            clearFieldErrors();
            setLoading(true);

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(async function (response) {
                const data = await response.json().catch(function () { return {}; });

                function notify(msg, type) {
                    if (typeof toastr !== 'undefined' && toastr[type]) {
                        toastr[type](msg);
                    } else if (typeof showNotification === 'function') {
                        showNotification(msg, type);
                    } else {
                        alert(msg);
                    }
                }

                if (response.ok && data.success) {
                    notify(data.message || @json(__('ecommerce::ecommerce.withdraw_request_submitted')), 'success');

                    // Update the wallet summary in place
                    const amountField = form.querySelector('[name="amount"]');
                    const amount = parseFloat(amountField.value) || 0;
                    const balEl = document.getElementById('walletBalanceValue');
                    if (balEl) {
                        const oldVal = parseFloat(balEl.dataset.value) || 0;
                        const newVal = Math.max(0, oldVal - amount);
                        balEl.dataset.value = newVal;
                        balEl.textContent = newVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        amountField.max = newVal;
                    }
                    const hint = document.getElementById('availableBalanceHint');
                    if (hint) {
                        hint.textContent = Number(Math.max(0, (parseFloat(hint.textContent.replace(/,/g, '')) || 0) - amount))
                            .toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }

                    // Reset the form for another request
                    form.reset();
                    clearFieldErrors();
                } else if (response.status === 422 && data.errors) {
                    Object.keys(data.errors).forEach(function (name) {
                        const msgs = Array.isArray(data.errors[name]) ? data.errors[name] : [data.errors[name]];
                        setFieldError(form.querySelector('[name="' + name + '"]'), msgs[0]);
                    });
                    const firstErr = form.querySelector('.is-invalid');
                    if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    throw { handledMessage: data.message || @json(__('ecommerce::ecommerce.unexpected_error')) };
                }
            })
            .catch(function (error) {
                if (error && error.handledMessage) {
                    if (typeof toastr !== 'undefined' && toastr.error) {
                        toastr.error(error.handledMessage);
                    } else if (typeof showNotification === 'function') {
                        showNotification(error.handledMessage, 'error');
                    } else {
                        alert(error.handledMessage);
                    }
                } else {
                    if (typeof toastr !== 'undefined' && toastr.error) {
                        toastr.error(@json(__('ecommerce::ecommerce.server_error')));
                    } else {
                        alert(@json(__('ecommerce::ecommerce.server_error')));
                    }
                }
            })
            .finally(function () {
                setLoading(false);
            });
        });
    })();
</script>
