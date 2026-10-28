@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.order_placed_successfully'))

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
                <div class="checkout-success-icon" aria-hidden="true">
                    <i class="fa fa-check"></i>
                </div>
                <h1>@lang('ecommerce::ecommerce.order_placed_successfully')</h1>
                <p>@lang('ecommerce::ecommerce.start_shopping_message')</p>
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
