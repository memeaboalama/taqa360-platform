@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.page_not_found'))

@section('page-styles')
    <style>
        .error-page {
            display: grid;
            min-height: 55vh;
            place-items: center;
            padding-block: 48px;
        }

        .error-card {
            width: min(100%, 620px);
            padding: 48px 32px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .error-code {
            margin: 0;
            color: var(--yellow);
            font-size: clamp(72px, 16vw, 128px);
            font-weight: 800;
            line-height: 1;
        }

        .error-card h1 {
            margin: 18px 0 8px;
            color: var(--ink);
            font-size: clamp(22px, 4vw, 30px);
        }

        .error-card p:not(.error-code) {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 28px;
        }

        .error-actions a,
        .error-actions button {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 18px;
            border: 1px solid var(--yellow);
            border-radius: 5px;
            background: var(--yellow);
            color: #fff;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .error-actions .error-home {
            background: #fff;
            color: var(--yellow-deep);
        }

        .error-actions a:focus-visible,
        .error-actions button:focus-visible {
            outline: 3px solid var(--yellow-deep);
            outline-offset: 3px;
        }

        @media (max-width: 520px) {
            .error-card {
                padding: 36px 20px;
            }

            .error-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <section class="error-page container" aria-labelledby="error-title">
        <div class="error-card">
            <p class="error-code" aria-hidden="true">404</p>
            <h1 id="error-title">{{ __('ecommerce::ecommerce.page_not_found') }}</h1>
            <p>{{ __('ecommerce::ecommerce.page_not_found_message') }}</p>
            <div class="error-actions">
                <button type="button" id="error-go-back" data-home-url="{{ route('ecommerce.static') }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    {{ __('ecommerce::ecommerce.back_to_previous_page') }}
                </button>
                <a class="error-home" href="{{ route('ecommerce.static') }}">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    {{ __('ecommerce::ecommerce.go_to_homepage') }}
                </a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.getElementById('error-go-back').addEventListener('click', function () {
            var fallbackUrl = this.dataset.homeUrl;
            var referrer = document.referrer;

            if (referrer && new URL(referrer).origin === window.location.origin) {
                window.history.back();
                return;
            }

            window.location.assign(fallbackUrl);
        });
    </script>
@endsection
