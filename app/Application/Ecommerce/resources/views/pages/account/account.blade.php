@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.my_account'))

@section('page-styles')
    <style>
        :root {
            --ink: #20252d;
            --muted: #747b84;
            --line: #e9edf1;
            --yellow: #306FAA;
            --accent-blue: #306FAA;
            --soft: #f6f7f9;
            --shadow: 0 8px 24px rgba(36, 43, 53, .07)
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            color: var(--ink);
            background: var(--soft);
            font: 13px 'Cairo', Tahoma, sans-serif
        }

        a {
            text-decoration: none;
            color: inherit
        }

        button {
            font: inherit;
            border: 0;
            cursor: pointer
        }

        .container {
            width: min(1260px, calc(100% - 40px));
            margin: auto
        }

        .topbar {
            height: 74px;
            background: #fff;
            border-bottom: 1px solid #f0f1f3;
            display: flex;
            align-items: center
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px
        }

        .brand {
            min-width: 172px
        }

        .brand img {
            display: block;
            width: 175px;
            height: 76px;
            object-fit: contain
        }

        .search {
            height: 42px;
            max-width: 535px;
            flex: 1;
            display: flex;
            direction: ltr;
            border: 1px solid var(--line);
            border-radius: 9px;
            overflow: hidden
        }

        .search input {
            flex: 1;
            border: 0;
            outline: 0;
            direction: inherit;
            padding: 0 18px;
            font: inherit
        }

        .search button {
            width: 48px;
            background: var(--yellow);
            font-size: 18px
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 18px;
            color: #5d6269;
            direction: ltr
        }

        .action {
            font-size: 19px
        }

        .service {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            cursor: pointer;
            padding: 8px 13px;
            border-radius: 5px;
            background: var(--yellow);
            font-size: 11px;
            font-weight: 700;
            gap: 6px
        }

        .nav {
            height: 51px;
            background: #fff;
            border-bottom: 1px solid #f3f3f3;
            box-shadow: 0 3px 12px #00000008
        }

        .nav ul {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            list-style: none;
            margin: 0;
            padding: 0
        }

        .nav a {
            font-size: 12px
        }

        .nav a.active,
        .nav a:hover {
            color: var(--accent-blue);
            font-weight: 700
        }

        .account-hero {
            padding: 32px 0 42px;
            background: linear-gradient(135deg, #eef4fa, #fff);
            border-bottom: 1px solid #d8e6f2
        }

        .account-hero h1 {
            margin: 0 0 7px;
            font-size: 28px
        }

        .account-hero p {
            margin: 0;
            color: var(--muted);
            font-size: 13px
        }

        .account-main {
            padding: 28px 0 70px
        }

        .account-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 25px 30px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow)
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 16px
        }

        .user-avatar {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #eef4fa;
            color: var(--accent-blue);
            font-size: 23px
        }

        .user-info h2 {
            margin: 0 0 4px;
            font-size: 20px
        }

        .user-info p {
            margin: 0;
            color: var(--muted);
            font-size: 12px
        }

        .balance {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            border-right: 1px solid var(--line);
            color: var(--muted)
        }

        .balance i {
            color: var(--accent-blue);
            font-size: 20px
        }

        .balance strong {
            display: block;
            color: var(--ink);
            font-size: 16px
        }

        .balance small {
            font-size: 10px
        }

        .account-layout {
            display: grid;
            grid-template-columns: 230px minmax(0, 1fr);
            gap: 22px;
            margin-top: 22px
        }

        .account-tabs,
        .account-content {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow)
        }

        .account-tabs {
            padding: 10px
        }

        .account-tab {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 14px;
            border-radius: 5px;
            color: #68717b;
            font-size: 12px;
            text-decoration: none !important
        }

        .account-tab:link,
        .account-tab:visited,
        .account-tab:hover,
        .account-tab:focus,
        .account-tab:active {
            text-decoration: none !important
        }

        .account-tab i {
            width: 20px;
            text-align: center;
            font-size: 16px
        }

        .account-tab:hover,
        .account-tab.active {
            background: #eef4fa;
            color: var(--accent-blue);
            font-weight: 700
        }

        .account-content {
            padding: 27px 30px;
            min-height: 290px
        }

        .content-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--line);
            padding-bottom: 17px;
            margin-bottom: 23px
        }

        .content-heading h2 {
            margin: 0;
            font-size: 20px
        }

        .edit-link {
            padding: 8px 13px;
            border: 1px solid #e3e6e9;
            border-radius: 4px;
            color: #777;
            font-size: 11px
        }

        .edit-link:hover {
            border-color: var(--yellow);
            color: var(--accent-blue)
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px
        }

        .profile-field {
            padding-bottom: 12px;
            border-bottom: 1px dashed #e1e5e8
        }

        .profile-field span {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 11px
        }

        .profile-field strong {
            font-size: 14px;
            font-weight: 600
        }

        .empty-message {
            padding: 30px;
            text-align: center;
            color: var(--muted);
            background: #fafafa;
            border: 1px dashed #e1e5e8;
            border-radius: 6px
        }

        footer {
            padding: 24px 0 18px;
            background: #252b31;
            color: #fff;
            text-align: center
        }

        .footer-logo {
            width: 160px;
            height: 65px;
            object-fit: contain
        }

        .footer-copy {
            margin: 5px 0 0;
            color: #d2d4d6;
            font-size: 11px
        }

        @media(max-width:800px) {
            .container {
                width: calc(100% - 24px)
            }

            .topbar {
                height: auto;
                padding: 10px 0
            }

            .topbar-inner {
                flex-wrap: wrap;
                gap: 10px
            }

            .brand {
                min-width: 130px
            }

            .brand img {
                width: 125px;
                height: 58px
            }

            .top-actions {
                margin-right: auto;
                gap: 12px
            }

            .search {
                order: 3;
                flex-basis: 100%;
                max-width: none
            }

            .nav {
                overflow: auto
            }

            .nav ul {
                justify-content: flex-start;
                gap: 27px;
                width: max-content;
                padding: 0 10px
            }

            .account-summary {
                align-items: flex-start;
                flex-direction: column;
                padding: 22px
            }

            .balance {
                border-right: 0;
                border-top: 1px solid var(--line);
                width: 100%;
                padding: 12px 0 0
            }

            .account-layout {
                grid-template-columns: 1fr
            }

            .account-tabs {
                display: grid;
                grid-template-columns: repeat(2, 1fr)
            }

            .account-tab {
                padding: 11px 8px;
                font-size: 10px
            }

            .account-content {
                padding: 22px 18px
            }

            .profile-grid {
                grid-template-columns: 1fr
            }
        }

        .account-hero {
            color: #fff;
            background: linear-gradient(110deg, #102d49 0%, #173f65 52%, #245c8e 100%)
        }

        .account-hero h1 {
            color: #fff
        }

        .account-hero p {
            color: #fff
        }

        .account-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: auto
        }

        .account-actions-source {
            display: none
        }

        .account-action {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            height: 42px;
            padding: 0 16px;
            border: 1px solid #e0e3e6;
            border-radius: 5px;
            background: #fff;
            color: #69717a;
            font-size: 12px
        }

        .account-action.primary {
            border-color: var(--yellow);
            background: var(--yellow);
            color: #fff;
            font-weight: 700
        }

        .account-action.primary:hover {
            color: #fff
        }

        .account-action:hover {
            border-color: var(--accent-blue);
            color: #245C8E
        }

        .footer-grid {
            display: flex;
            justify-content: center
        }

        .footer-brand {
            text-align: center
        }

        .footer-brand p {
            margin: 0;
            font-size: 14px;
            line-height: 1.8
        }

        .copyright {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            border-top: 1px solid #3d4348;
            color: #afb4b8;
            font-size: 11px;
            margin-top: 15px;
            padding-top: 10px
        }

        .footer-links {
            display: flex;
            gap: 18px
        }

        .footer-links a,
        .socials a {
            color: #d2d4d6;
            font-size: 10px;
            text-decoration: none
        }

        .socials {
            display: flex;
            gap: 14px
        }

        @media(max-width:800px) {
            .copyright {
                flex-wrap: wrap;
                justify-content: center;
                text-align: center
            }

            .footer-links {
                gap: 10px
            }
        }
    </style>
@endsection

@section('content')
    <section class="account-hero">
        <div class="container">
            <h1>@lang('ecommerce::ecommerce.my_account')</h1>
            <p>@lang('ecommerce::ecommerce.account_hero_desc')</p>
        </div>
    </section>
    <div class="page-content account-main">
        <div class="container">
            <section class="account-summary">
                <div class="user-info">
                    <div class="user-avatar"><i class="fa fa-user-o"></i></div>
                    <div>
                        <h2>{{ $user->name }}</h2>
                        <p class="ltr">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="balance"><i class="ti-wallet"></i>
                    <div><strong>{{ number_format($user->balance, 2) }}</strong><small>SAR</small></div>
                </div>
            </section>
            <div class="account-layout">
                <aside class="account-tabs"><a class="account-tab active" href="{{ route('account.index') }}"><i
                            class="ti-user"></i><span>@lang('ecommerce::ecommerce.personal_info')</span></a><a
                        class="account-tab" href="{{ route('account.index', ['section' => 'orders']) }}"><i
                            class="ti-package"></i><span>@lang('ecommerce::ecommerce.orders')</span></a><a
                        class="account-tab" href="{{ route('account.index', ['section' => 'service_requests']) }}"><i
                            class="ti-settings"></i><span>@lang('ecommerce::ecommerce.service_requests')</span></a><a
                        class="account-tab" href="{{ route('account.index', ['section' => 'addresses']) }}"><i
                            class="ti-location-pin"></i><span>@lang('ecommerce::ecommerce.addresses')</span></a><a
                        class="account-tab" href="{{ route('account.wallet.index') }}"><i
                            class="ti-wallet"></i><span>@lang('ecommerce::ecommerce.my_wallet')</span></a></aside>
                <section class="account-content">
                    <div class="content-heading">
                        <h2>@lang('ecommerce::ecommerce.personal_info')</h2><a class="edit-link"
                            href="{{ route('account.edit-profile') }}"><i class="ti-pencil"></i>
                            @lang('ecommerce::ecommerce.edit_profile')</a>
                    </div>
                    <div class="profile-grid">
                        <div class="profile-field">
                            <span>@lang('ecommerce::ecommerce.name')</span><strong>{{ $user->name }}</strong></div>
                        <div class="profile-field">
                            <span>@lang('ecommerce::ecommerce.email')</span><strong class="ltr">{{ $user->email }}</strong></div>
                        <div class="profile-field">
                            <span>@lang('ecommerce::ecommerce.phone')</span><strong class="num">{{ $user->phone ?? '-' }}</strong></div>
                        <div class="profile-field">
                            <span>@lang('ecommerce::ecommerce.date_of_birth')</span><strong>{{ $user->date_of_birth ? $user->date_of_birth->format('d M Y') : '-' }}</strong>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <div class="account-actions account-actions-source"><a class="account-action primary"
            href="{{ route('account.wallet.deposit') }}"><i class="ti-plus"></i>
            @lang('ecommerce::ecommerce.deposit_funds')</a>
        <form action="{{ route('logout') }}" method="POST">@csrf<button class="account-action" type="submit"><i
                    class="ti-power-off"></i> @lang('ecommerce::ecommerce.logout')</button></form>
    </div>
    <script>document.addEventListener('DOMContentLoaded', function () { var actions = document.querySelector('.account-actions-source'), summary = document.querySelector('.account-summary'); if (actions && summary) { actions.classList.remove('account-actions-source'); summary.appendChild(actions); } });</script>
@endsection