@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
    @endif

    <style>
        /* PRECISE FORM CARD */
        .precise-form-card {
            background: #fff;
            border: 1px solid #eef2f5;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
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

        /* PAYMENT OPTIONS */
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 25px;
        }

        .payment-option {
            cursor: pointer;
            margin: 0;
            display: block;
        }

        .payment-option input {
            display: none;
        }

        .payment-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px 20px;
            transition: all 0.2s;
        }

        /* Active State */
        .payment-option input:checked+.payment-content {
            border-color: #306FAA;
            background: #eef4fa;
            box-shadow: 0 0 0 2px #306FAA inset;
        }

        .pay-info {
            display: flex;
            flex-direction: column;
        }

        .pay-name {
            font-weight: 700;
            color: #2d3748;
            font-size: 15px;
        }

        .pay-desc {
            font-size: 13px;
            color: #718096;
            margin-top: 2px;
        }

        .pay-icon {
            font-size: 20px;
            color: #a0aec0;
        }

        .payment-option input:checked+.payment-content .pay-icon {
            color: #306FAA;
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

        .label-title {
            font-size: 13px;
            font-weight: 600;
            color: #5d6778;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

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

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #306FAA;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: #245C8E;
            transform: translateY(-1px);
            color: #fff;
        }

        /* WALLET SUMMARY CARD */
        .wallet-summary-card {
            background: #fff;
            border: 1px solid #eef2f5;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            margin-bottom: 30px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eee;
            color: #1a202c;
        }

        .balance-display {
            font-size: 28px;
            font-weight: 800;
            color: #306FAA;
            margin-bottom: 10px;
        }
    </style>

    <div class="account-content-area w-100">
        <div class="content-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('account.wallet.index') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1 mb-2" up-target=".dashboard-content">
                    <i class="ti-arrow-left arrow-start"></i> @lang('ecommerce::ecommerce.back_to_wallet')
                </a>
                @if(empty($hide_header))
                <h2 class="fw-bold m-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.deposit_funds')</h2>
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
            <!-- Main: Deposit Form -->
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="precise-form-card">
                    <div class="form-header">
                        <h4 class="form-title">@lang('ecommerce::ecommerce.choose_payment_method')</h4>
                    </div>

                    <div class="payment-options">
                        <!-- Reordered: Coupon First -->
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="coupon" checked onchange="toggleForm('coupon')">
                            <div class="payment-content">
                                <div class="pay-info">
                                    <span class="pay-name">@lang('ecommerce::ecommerce.promo_code')</span>
                                    <small class="pay-desc">Redeem a voucher code</small>
                                </div>
                                <i class="ti-ticket pay-icon"></i>
                            </div>
                        </label>

                        <!-- <label class="payment-option">
                        <input type="radio" name="payment_method" value="online" onchange="toggleForm('online')">
                        <div class="payment-content">
                            <div class="pay-info">
                                <span class="pay-name">@lang('ecommerce::ecommerce.online_payment')</span>
                                <small class="pay-desc">Mada, Visa, MasterCard</small>
                            </div>
                            <i class="ti-credit-card pay-icon"></i>
                        </div>
                    </label> -->
                    </div>

                    <div class="section-divider border-top pt-4 mt-0 opacity-25"></div>

                    <!-- COUPON FORM (Default) -->
                    <div id="coupon-form">
                        <form action="{{ route('account.wallet.coupon.apply') }}" method="POST" up-submit up-target=".dashboard-content">
                            @csrf
                            <div class="mb-4">
                                <label class="label-title">@lang('ecommerce::ecommerce.promo_code')</label>
                                <input type="text" class="precise-input" name="walletCoupon" required placeholder="@lang('ecommerce::ecommerce.inter_promo_code')">
                            </div>
                            <button type="submit" class="btn-submit">
                                @lang('ecommerce::ecommerce.apply')
                            </button>
                        </form>
                    </div>

                    <!-- ONLINE FORM -->
                    <div id="online-form" style="display: none;">
                        <form action="{{ route('account.wallet.deposit.process') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="label-title">@lang('ecommerce::ecommerce.enter_amount')</label>
                                <div class="amount-input-group">
                                    <input type="number" dir="ltr" class="precise-input" name="amount" min="1" step="0.01" required placeholder="0.00">
                                    <span class="amount-currency">{{ \App\Domain\AppBase\Models\Setting\Currency::symbol() }}</span>
                                </div>
                            </div>
                            <button type="submit" class="btn-submit">
                                @lang('ecommerce::ecommerce.continue') <i class="ti-arrow-left arrow-start ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Balance -->
            <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
                <div class="wallet-summary-card">
                    <h4 class="summary-title">@lang('ecommerce::ecommerce.wallet_summary')</h4>

                    <h6 class="text-muted text-uppercase small fw-bold mb-2">@lang('ecommerce::ecommerce.current_balance')</h6>
                    <div class="balance-display">
                        {{ number_format((float)auth()->user()->balance, 2) }} <small style="font-size: 14px; color: #a0aec0;">SAR</small>
                    </div>

                    <div class="mt-3 text-muted" style="font-size: 13px; line-height: 1.6;">
                        <i class="ti-lock mb-1 d-block" style="font-size: 18px;"></i>
                        @lang('ecommerce::ecommerce.secure_payment')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleForm(type) {
            document.getElementById('online-form').style.display = type === 'online' ? 'block' : 'none';
            document.getElementById('coupon-form').style.display = type === 'coupon' ? 'block' : 'none';
        }
    </script>

    @if(request()->hasHeader('X-Up-Target'))
</div>
@endif