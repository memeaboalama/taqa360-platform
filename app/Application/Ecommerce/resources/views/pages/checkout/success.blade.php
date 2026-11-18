@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.checkout_confirmation_title'))

@section('active-nav', 'account')

@section('page-styles')
    <style>
        .checkout-success {
            min-height: 48vh;
            padding: 52px 0 68px;
            background: var(--soft);
        }

        .checkout-success-panel {
            width: min(100%, 680px);
            margin: 0 auto;
            padding: 38px 32px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .checkout-success-icon {
            display: grid;
            place-items: center;
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
            border: 1px solid #bde8cd;
            border-radius: 50%;
            background: #effaf3;
            color: #23834b;
            font-size: 28px;
        }

        .checkout-success-icon.is-warning {
            border-color: #f2d89a;
            background: #fff8e6;
            color: #966c00;
        }

        .checkout-success-icon.is-failed {
            border-color: #f0c2c2;
            background: #fff0f0;
            color: #b42318;
        }

        .checkout-success-panel h1 {
            margin: 0 0 10px;
            color: var(--ink);
            font-size: 25px;
            font-weight: 800;
        }

        .checkout-success-panel p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .checkout-confirmation-details {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 12px;
            margin: 24px 0 0;
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fafbfc;
            text-align: start;
        }

        .checkout-confirmation-details div {
            min-width: 0;
        }

        .checkout-confirmation-details dt {
            color: var(--muted);
            font-size: 12px;
        }

        .checkout-confirmation-details dd {
            margin: 3px 0 0;
            color: var(--ink);
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .checkout-confirmation-details .confirmation-total dd {
            font-size: 18px;
        }

        .checkout-success-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 26px;
        }

        .checkout-success-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 9px 17px;
            border: 1px solid var(--line);
            border-radius: 6px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease, border-color .2s ease;
        }

        .checkout-success-actions .success-primary {
            border-color: var(--yellow);
            background: var(--yellow);
            color: #fff;
        }

        .checkout-success-actions .success-primary:visited,
        .checkout-success-actions .success-primary:hover,
        .checkout-success-actions .success-primary:focus,
        .checkout-success-actions .success-primary:active {
            color: #fff;
            text-decoration: none;
        }

        .checkout-success-actions .success-primary:hover {
            border-color: var(--yellow-deep);
            background: var(--yellow-deep);
        }

        .checkout-success-actions .success-secondary {
            background: #fff;
            color: var(--ink);
        }

        .checkout-success-actions .success-secondary:hover {
            border-color: var(--yellow);
            background: #f6f7f9;
        }

        @media(max-width: 520px) {
            .checkout-success {
                padding: 32px 0 46px;
            }

            .checkout-success-panel {
                padding: 30px 20px;
            }

            .checkout-success-panel h1 {
                font-size: 21px;
            }

            .checkout-confirmation-details {
                grid-template-columns: 1fr;
            }

            .checkout-success-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <main class="checkout-success">
        <div class="container">
            <section class="checkout-success-panel" role="status" aria-live="polite">
                @php
                    $isPaid = $confirmationState === 'paid';
                    $isFailed = $confirmationState === 'failed';
                    $isWarning = !$isPaid && !$isFailed;
                @endphp
                <div class="checkout-success-icon {{ $isFailed ? 'is-failed' : ($isWarning ? 'is-warning' : '') }}"
                    aria-hidden="true">
                    <i class="fa {{ $isFailed ? 'fa-times' : ($isPaid ? 'fa-check' : 'fa-clock-o') }}"></i>
                </div>
                <h1>@lang('ecommerce::ecommerce.checkout_confirmation_title')</h1>
                <p>@lang('ecommerce::ecommerce.checkout_confirmation_message_' . $confirmationState, ['order' => $order->code])</p>
                <dl class="checkout-confirmation-details">
                    <div>
                        <dt>@lang('ecommerce::ecommerce.order_number')</dt>
                        <dd>#{{ $order->code }}</dd>
                    </div>
                    <div>
                        <dt>@lang('ecommerce::ecommerce.order_date')</dt>
                        <dd>{{ $order->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>@lang('ecommerce::ecommerce.payment_method')</dt>
                        <dd>{{ $paymentSummary['payment_method'] ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt>@lang('ecommerce::ecommerce.payment_status')</dt>
                        <dd>@lang('ecommerce::ecommerce.checkout_confirmation_state_' . $confirmationState)</dd>
                    </div>
                    <div class="confirmation-total">
                        <dt>@lang('ecommerce::ecommerce.amount_due')</dt>
                        <dd>{{ \App\Domain\AppBase\Models\Setting\Currency::format($paymentSummary['due']) }}</dd>
                    </div>
                    <div class="confirmation-total">
                        <dt>@lang('ecommerce::ecommerce.amount_paid')</dt>
                        <dd>{{ \App\Domain\AppBase\Models\Setting\Currency::format($paymentSummary['collected']) }}</dd>
                    </div>
                    <div>
                        <dt>@lang('ecommerce::ecommerce.order_status')</dt>
                        <dd>{{ $order->status_label }}</dd>
                    </div>
                </dl>
                <div class="checkout-success-actions">
                    <a class="success-primary" href="{{ route('account.orders') }}">
                        @lang('ecommerce::ecommerce.orders')
                    </a>
                    <a class="success-secondary" href="{{ route('ecommerce.static') }}">
                        @lang('ecommerce::ecommerce.continue_shopping')
                    </a>
                </div>
            </section>
        </div>
    </main>
@endsection
