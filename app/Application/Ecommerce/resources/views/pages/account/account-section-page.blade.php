@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.my_account'))

@section('active-nav', 'account')

@section('page-styles')
    <style>
        .account-hero {
            padding: 32px 0 42px;
            color: #fff;
            background: linear-gradient(110deg, #102d49 0%, #173f65 52%, #245c8e 100%)
        }

        .account-hero h1 {
            margin: 0 0 7px;
            color: #fff;
            font-size: 28px
        }

        .account-hero p {
            margin: 0
        }

        .account-main {
            padding: 28px 0 70px
        }

        .account-summary,
        .account-tabs,
        .account-content {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(36, 43, 53, .07)
        }

        .account-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 28px
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 14px
        }

        .avatar {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #eef4fa;
            color: #306FAA;
            font-size: 22px
        }

        .user-info h2 {
            margin: 0 0 4px;
            font-size: 17px
        }

        .user-info p {
            margin: 0;
            color: var(--muted)
        }

        .balance {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #306FAA;
            font-size: 22px
        }

        .balance strong {
            display: block;
            font-size: 17px
        }

        .balance small {
            font-size: 10px
        }

        .account-layout {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            gap: 22px;
            margin-top: 22px
        }

        .account-tabs {
            padding: 10px;
            height: max-content
        }

        .account-tab {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 12px;
            border-radius: 5px;
            color: var(--muted);
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

        .account-tab.active,
        .account-tab:hover {
            background: #eef4fa;
            color: #245C8E;
            font-weight: 700
        }

        .account-content {
            padding: 22px;
            min-height: 300px
        }

        .content-heading {
            border-bottom: 1px solid var(--line);
            margin-bottom: 18px
        }

        .content-heading h2 {
            margin: 0 0 14px;
            font-size: 19px
        }

        .content-heading.orders-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .account-form-group {
            margin-bottom: 18px
        }

        .account-form-group label {
            display: block;
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 600
        }

        .account-form-group input {
            width: 100%;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 5px;
            color: var(--foreground);
            background: #fff
        }

        .account-form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px
        }

        .account-form-actions button,
        .account-form-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 9px 18px;
            border: 1px solid var(--line);
            border-radius: 5px;
            font-weight: 700
        }

        .account-form-actions button {
            border-color: var(--yellow);
            color: #fff;
            background: var(--yellow)
        }

        .data-table {
            width: 100%;
            border-collapse: collapse
        }

        .account-table-wrap {
            width: 100%;
            overflow-x: auto
        }

        .data-table.orders-table {
            min-width: 620px
        }

        .data-table th,
        .data-table td {
            padding: 12px 10px;
            text-align: start;
            border-bottom: 1px solid var(--line)
        }

        .data-table th {
            color: var(--muted);
            font-size: 11px
        }

        .status {
            color: #245C8E
        }

        .order-count {
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 2px 9px;
            border-radius: 12px;
            background: #eef4fa;
            color: #245C8E;
            font-size: 12px;
            font-weight: 700
        }

        .order-details-link {
            color: #245C8E;
            font-weight: 700;
            white-space: nowrap
        }

        .order-details-link:hover {
            color: #604400;
            text-decoration: underline
        }

        .order-detail-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .order-detail-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(260px, .8fr);
            gap: 18px
        }

        .order-detail-main,
        .order-detail-sidebar {
            display: grid;
            align-content: start;
            gap: 16px;
            min-width: 0
        }

        .order-detail-panel {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff
        }

        .order-detail-panel h3 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 0;
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
            font-size: 15px;
            font-weight: 800
        }

        .order-detail-count {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600
        }

        .order-detail-items {
            padding: 0 16px
        }

        .order-detail-item {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #f0f1f3
        }

        .order-detail-item:last-child {
            border-bottom: 0
        }

        .order-detail-item img {
            width: 52px;
            height: 52px;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: var(--soft);
            object-fit: cover
        }

        .order-detail-item-name {
            margin: 0 0 4px;
            font-weight: 700
        }

        .order-detail-item-meta {
            color: var(--muted);
            font-size: 12px
        }

        .order-detail-item-price {
            font-weight: 700;
            white-space: nowrap
        }

        .order-detail-body {
            padding: 15px 16px;
            color: var(--muted);
            line-height: 1.8
        }

        .order-detail-body strong {
            display: block;
            color: var(--ink);
            font-weight: 700
        }

        .order-detail-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 9px 16px;
            border-bottom: 1px solid #f0f1f3
        }

        .order-detail-row:last-child {
            border-bottom: 0
        }

        .order-detail-label {
            color: var(--muted)
        }

        .order-detail-value {
            text-align: end;
            font-weight: 700
        }

        .order-detail-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 12px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700
        }

        .order-detail-topline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px
        }

        .order-detail-back {
            color: #245C8E;
            font-weight: 700
        }

        .order-detail-date {
            color: var(--muted);
            font-size: 12px
        }

        .order-cancel {
            padding: 12px 16px;
            border-top: 1px solid var(--line)
        }

        .order-cancel summary {
            color: #b42318;
            font-weight: 700;
            cursor: pointer
        }

        .order-cancel textarea {
            width: 100%;
            min-height: 82px;
            margin-top: 12px;
            padding: 10px;
            border: 1px solid var(--line);
            border-radius: 5px;
            resize: vertical
        }

        .order-cancel button {
            margin-top: 8px;
            padding: 9px 14px;
            border-radius: 5px;
            background: #b42318;
            color: #fff;
            font-weight: 700
        }

        .order-cancel-note {
            padding: 12px 16px;
            color: var(--muted);
            font-size: 12px
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 40px 10px
        }

        .pagination_sec {
            margin-top: 18px;
            display: flex;
            justify-content: center
        }

        .pagination_sec .cdp {
            font-size: 0
        }

        .pagination_sec .cdp nav {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            justify-content: center
        }

        .pagination_sec .cdp .cdp_i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 34px;
            height: 34px;
            padding: 0 12px;
            margin: 0;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: #fff;
            color: var(--ink);
            font-size: 13px;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            box-sizing: border-box
        }

        .pagination_sec .cdp .cdp_i:first-child,
        .pagination_sec .cdp .cdp_i:last-child {
            padding: 0 14px
        }

        .pagination_sec .cdp a.cdp_i:hover {
            border-color: var(--yellow);
            background: #fffdf3;
            color: var(--yellow-deep)
        }

        .pagination_sec .cdp .cdp_i.active {
            background-color: var(--yellow) !important;
            border-color: var(--yellow) !important;
            color: var(--ink) !important
        }

        .pagination_sec .cdp .cdp_i.disabled {
            opacity: .45;
            cursor: not-allowed
        }

        .pagination_sec .cdp .cdp_i i {
            font-size: 14px
        }

        .address-card {
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 10px
        }

        .address-card p {
            margin: 6px 0 0;
            color: var(--muted)
        }

        @media(max-width:800px) {
            .account-layout {
                grid-template-columns: 1fr
            }

            .account-tabs {
                display: flex;
                overflow: auto
            }

            .account-tab {
                white-space: nowrap
            }

            .account-summary {
                gap: 15px;
                padding: 18px
            }

            .order-detail-layout {
                grid-template-columns: minmax(0, 1fr)
            }
        }

        @media(max-width:520px) {
            .order-detail-topline {
                align-items: flex-start;
                flex-direction: column
            }

            .order-detail-item {
                grid-template-columns: 44px minmax(0, 1fr);
                gap: 10px
            }

            .order-detail-item img {
                width: 44px;
                height: 44px
            }

            .order-detail-item-price {
                grid-column: 2;
                font-size: 12px
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
    <main class="account-main">
        <div class="container">
            <section class="account-summary">
                <div class="user-info">
                    <div class="avatar"><i class="fa fa-user-o"></i></div>
                    <div>
                        <h2>{{ $user->name }}</h2>
                        <p class="ltr">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="balance">
                    <i class="ti-wallet"></i>
                    <div>
                        <strong class="num">{{ number_format($user->balance, 2) }}</strong>
                        <small>@lang('ecommerce::ecommerce.currency_symbol')</small>
                    </div>
                </div>
            </section>
            <div class="account-layout">
                <aside class="account-tabs">
                    <a class="account-tab {{ in_array($section, ['profile', 'edit_profile'], true) ? 'active' : '' }}" href="{{ route('account.index') }}"><i class="ti-user"></i><span>@lang('ecommerce::ecommerce.personal_info')</span></a>
                    <a class="account-tab {{ in_array($section, ['orders', 'order_details'], true) ? 'active' : '' }}" href="{{ route('account.index', ['section' => 'orders']) }}"><i class="ti-package"></i><span>@lang('ecommerce::ecommerce.orders')</span></a>
                    <a class="account-tab {{ $section === 'service_requests' ? 'active' : '' }}" href="{{ route('account.index', ['section' => 'service_requests']) }}"><i class="ti-settings"></i><span>@lang('ecommerce::ecommerce.service_requests')</span></a>
                    <a class="account-tab {{ $section === 'addresses' ? 'active' : '' }}" href="{{ route('account.index', ['section' => 'addresses']) }}"><i class="ti-location-pin"></i><span>@lang('ecommerce::ecommerce.addresses')</span></a>
                    <a class="account-tab {{ in_array($section, ['wallet', 'wallet_deposit', 'wallet_withdraw'], true) ? 'active' : '' }}" href="{{ route('account.wallet.index') }}"><i class="ti-wallet"></i><span>@lang('ecommerce::ecommerce.my_wallet')</span></a>
                </aside>
                <section class="account-content">
                    <div class="content-heading {{ $section === 'orders' ? 'orders-heading' : '' }}">
                        <h2>
                            @if($section === 'edit_profile')
                                @lang('ecommerce::ecommerce.personal_info')
                            @elseif($section === 'orders')
                                @lang('ecommerce::ecommerce.orders')
                            @elseif($section === 'order_details')
                                @lang('ecommerce::ecommerce.order_details')
                            @elseif($section === 'service_requests')
                                @lang('ecommerce::ecommerce.service_requests')
                            @elseif($section === 'wallet')
                                @lang('ecommerce::ecommerce.my_wallet')
                            @elseif($section === 'wallet_deposit')
                                @lang('ecommerce::ecommerce.deposit_funds')
                            @elseif($section === 'wallet_withdraw')
                                @lang('ecommerce::ecommerce.withdraw_funds')
                            @else
                                @lang('ecommerce::ecommerce.addresses')
                            @endif
                        </h2>
                        @if($section === 'orders')
                            <span class="order-count">{{ $orders->total() }} @lang('ecommerce::ecommerce.orders')</span>
                        @endif
                    </div>

                    @if($section === 'edit_profile')
                        <form action="{{ route('account.update-profile') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="account-form-group">
                                        <label for="account-name">@lang('ecommerce::ecommerce.name') *</label>
                                        <input id="account-name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="account-form-group">
                                        <label for="account-email">@lang('ecommerce::ecommerce.email') *</label>
                                        <input id="account-email" type="email" dir="ltr" name="email" value="{{ old('email', $user->email) }}" readonly required style="background-color:#f4f6f9;cursor:not-allowed;">
                                        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="account-form-group">
                                        <label for="account-phone">@lang('ecommerce::ecommerce.phone')</label>
                                        <input id="account-phone" type="text" dir="ltr" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="account-form-group">
                                        <label for="account-date-of-birth">@lang('ecommerce::ecommerce.date_of_birth')</label>
                                        <input id="account-date-of-birth" type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                                        @error('date_of_birth')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h3 class="mb-3">@lang('ecommerce::ecommerce.change_password')</h3>
                            <div class="account-form-group">
                                <label for="account-current-password">@lang('ecommerce::ecommerce.current_password')</label>
                                <input id="account-current-password" type="password" name="current_password" autocomplete="current-password">
                                @error('current_password')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="account-form-group">
                                        <label for="account-password">@lang('ecommerce::ecommerce.new_password')</label>
                                        <input id="account-password" type="password" name="password" autocomplete="new-password">
                                        @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="account-form-group">
                                        <label for="account-password-confirmation">@lang('ecommerce::ecommerce.confirm_password')</label>
                                        <input id="account-password-confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                            <div class="account-form-actions">
                                <button type="submit"><i class="ti-check"></i> @lang('ecommerce::ecommerce.save_changes')</button>
                                <a href="{{ route('account.index') }}">@lang('ecommerce::ecommerce.cancel')</a>
                            </div>
                        </form>
                    @elseif($section === 'orders')
                        @if($orders->count())
                            <div class="account-table-wrap">
                                <table class="data-table orders-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('ecommerce::ecommerce.order_number')</th>
                                            <th>@lang('ecommerce::ecommerce.date')</th>
                                            <th>@lang('ecommerce::ecommerce.status')</th>
                                            <th>@lang('ecommerce::ecommerce.amount')</th>
                                            <th>@lang('ecommerce::ecommerce.details')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td class="num">#{{ $order->code ?? $order->order_number ?? $order->id }}</td>
                                                <td class="num" dir="ltr">{{ optional($order->created_at)->format('d/m/Y - H:i') }}</td>
                                                <td><span class="status-badge {{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                                <td>{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->total_after_vat ?: $order->total) }}</td>
                                                <td><a class="order-details-link" href="{{ route('account.order', $order->id) }}">@lang('ecommerce::ecommerce.view_details')</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty">@lang('ecommerce::ecommerce.no_orders_yet')</div>
                        @endif
                        @if($orders->hasPages())
                            <div class="pagination_sec">
                                <div class="content_detail__pagination cdp">
                                    {!! $orders->links('vendor.pagination.custom') !!}
                                </div>
                            </div>
                        @endif
                    @elseif($section === 'order_details')
                        @php
                            $addressRaw = $order->addressInOrder;
                            $address = is_string($addressRaw) ? json_decode($addressRaw, true) : $addressRaw;
                            $statusValue = $order->status instanceof \BackedEnum ? $order->status->value : $order->status;
                            $promoValue = $order->getAttribute('promocode');
                            $promoCode = is_array($promoValue) ? ($promoValue['code'] ?? '') : ($promoValue ?: '');
                            $promoCode = is_string($promoCode) ? $promoCode : '';
                            $forbiddenStatuses = [
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::READY->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::CANCELLED->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::ON_WAY->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::EXPIRED->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::PREVIOUS->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::CURRENT->value,
                            ];
                            $isCancellable = !in_array($statusValue, $forbiddenStatuses, true);
                        @endphp

                        <div class="order-detail-topline">
                            <a class="order-detail-back" href="{{ route('account.orders') }}"><i class="ti-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i> @lang('ecommerce::ecommerce.back_to_orders')</a>
                            <span class="order-detail-date" dir="ltr">{{ optional($order->created_at)->format('d/m/Y - H:i') }}</span>
                        </div>

                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                        <div class="order-detail-layout">
                            <div class="order-detail-main">
                                <section class="order-detail-panel">
                                    <h3>@lang('ecommerce::ecommerce.items') <span class="order-detail-count">{{ $order->orderItems->count() }}</span></h3>
                                    <div class="order-detail-items">
                                        @foreach($order->orderItems as $item)
                                            <article class="order-detail-item">
                                                <img src="{{ $item->item?->image_path ?? asset('assets/images/pro/b1.jpg') }}" alt="{{ $item->item_name ?? $item->item?->title ?? __('ecommerce::ecommerce.product_label') }}">
                                                <div>
                                                    <p class="order-detail-item-name">{{ $item->item_name ?? $item->item?->title ?? __('ecommerce::ecommerce.product_label') }}</p>
                                                    <span class="order-detail-item-meta">@lang('ecommerce::ecommerce.quantity'): {{ $item->qty }}</span>
                                                </div>
                                                <span class="order-detail-item-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($item->subtotal) }}</span>
                                            </article>
                                        @endforeach
                                    </div>
                                </section>

                                <section class="order-detail-panel">
                                    <h3>@lang('ecommerce::ecommerce.shipping_details')</h3>
                                    @if($address && is_array($address))
                                        <div class="order-detail-body">
                                            <strong>{{ $order->client_name ?? $order->user?->name ?? '-' }}</strong>
                                            @if($order->client_phone ?? $order->user?->phone)
                                                <span>{{ $order->client_phone ?? $order->user?->phone }}</span><br>
                                            @endif
                                            <span>{{ $address['city_name'] ?? '' }}@if(!empty($address['district_name']))، {{ $address['district_name'] }}@endif</span>
                                            @if(!empty($address['address']))<br><span>{{ $address['address'] }}</span>@endif
                                        </div>
                                    @else
                                        <div class="order-detail-body">@lang('ecommerce::ecommerce.no_address_found')</div>
                                    @endif
                                </section>
                            </div>

                            <aside class="order-detail-sidebar">
                                <section class="order-detail-panel">
                                    <h3>@lang('ecommerce::ecommerce.order_status')</h3>
                                    <div class="order-detail-row">
                                        <span class="order-detail-label">@lang('ecommerce::ecommerce.order_status')</span>
                                        <span class="order-detail-badge {{ $order->status_color }}">{{ $order->status_label }}</span>
                                    </div>
                                    <div class="order-detail-row">
                                        <span class="order-detail-label">@lang('ecommerce::ecommerce.payment_status')</span>
                                        <span class="order-detail-badge {{ $order->payment_color }}">{{ $order->payment_label }}</span>
                                    </div>
                                    @if($isCancellable)
                                        <details class="order-cancel">
                                            <summary>@lang('ecommerce::ecommerce.cancel_order')</summary>
                                            <form action="{{ route('account.order.cancel', $order->id) }}" method="POST">
                                                @csrf
                                                <label class="sr-only" for="cancel-reason">@lang('ecommerce::ecommerce.confirm_cancel_order')</label>
                                                <textarea id="cancel-reason" name="cancellation_reason" placeholder="@lang('ecommerce::ecommerce.cancel_reason_placeholder')" required></textarea>
                                                <button type="submit">@lang('ecommerce::ecommerce.submit_cancel')</button>
                                            </form>
                                        </details>
                                    @elseif($statusValue === \App\Application\Marketplace\Enums\MerchantOrderStatuses::CANCELLED->value)
                                        <div class="order-cancel-note">@lang('ecommerce::ecommerce.order_cancelled')</div>
                                        @php $cancelHistory = $order->orders_history->sortByDesc('created_at')->first(); @endphp
                                        @if($cancelHistory?->reason)
                                            <div class="order-detail-body"><strong>@lang('ecommerce::ecommerce.cancellation_reason')</strong>{{ $cancelHistory->reason }}</div>
                        @endif
                    @else
                                        <div class="order-cancel-note">@lang('ecommerce::ecommerce.cannot_cancel_order_in_progress')</div>
                                    @endif
                                </section>

                                <section class="order-detail-panel">
                                    <h3>@lang('ecommerce::ecommerce.order_summary')</h3>
                                    <div class="order-detail-row"><span class="order-detail-label">@lang('ecommerce::ecommerce.subtotal')</span><span class="order-detail-value">{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->subtotal) }}</span></div>
                                    @if($order->delivery_fee > 0)
                                        <div class="order-detail-row"><span class="order-detail-label">@lang('ecommerce::ecommerce.delivery_fee')</span><span class="order-detail-value">{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->delivery_fee) }}</span></div>
                                    @endif
                                    @if($order->vat > 0)
                                        <div class="order-detail-row"><span class="order-detail-label">@lang('ecommerce::ecommerce.vat')</span><span class="order-detail-value">{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->vat) }}</span></div>
                                    @endif
                                    @if($order->discount > 0)
                                        <div class="order-detail-row"><span class="order-detail-label">@lang('ecommerce::ecommerce.discount') @if($promoCode)<small>({{ $promoCode }})</small>@endif</span><span class="order-detail-value">-{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->discount) }}</span></div>
                                    @endif
                                    <div class="order-detail-row"><strong>@lang('ecommerce::ecommerce.grand_total')</strong><strong class="order-detail-value">{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->total_after_vat ?: $order->total) }}</strong></div>
                                    <div class="order-detail-row"><span class="order-detail-label">@lang('ecommerce::ecommerce.payment_method')</span><span class="order-detail-value">{{ $order->payment_method }}</span></div>
                                </section>
                            </aside>
                        </div>
                    @elseif($section === 'service_requests')
                        @forelse($serviceRequests as $requestItem)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('ecommerce::ecommerce.academic_major_label')</th>
                                        <th>@lang('ecommerce::ecommerce.attachments')</th>
                                        <th>@lang('ecommerce::ecommerce.date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="num">{{ $requestItem->id }}</td>
                                        <td>{{ optional($requestItem->academicMajor)->name ?? '-' }}</td>
                                        <td class="num">{{ $requestItem->attachments_count ?? 0 }}</td>
                                        <td class="num">{{ optional($requestItem->created_at)->format('Y-m-d') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @empty
                            <div class="empty">@lang('ecommerce::ecommerce.no_service_requests_message')</div>
                        @endforelse
                        @if($serviceRequests->hasPages())
                            <div class="pagination_sec">
                                <div class="content_detail__pagination cdp">
                                    {!! $serviceRequests->links('vendor.pagination.custom') !!}
                                </div>
                            </div>
                        @endif
                    @elseif($section === 'wallet')
                        @include('ecommerce::pages.account.wallet._index', ['hide_header' => true])
                    @elseif($section === 'wallet_deposit')
                        @include('ecommerce::pages.account.wallet._deposit', ['hide_header' => true])
                    @elseif($section === 'wallet_withdraw')
                        @include('ecommerce::pages.account.wallet._withdraw', ['hide_header' => true])
                    @else
                        @forelse($addresses as $address)
                            <article class="address-card">
                                <strong>{{ $address->name ?? __('ecommerce::ecommerce.address') }}</strong>
                                <p>{{ $address->address ?? $address->street ?? '-' }}</p>
                            </article>
                        @empty
                            <div class="empty">@lang('ecommerce::ecommerce.no_addresses_message')</div>
                        @endforelse
                    @endif
                </section>
            </div>
        </div>
    </main>
@endsection
