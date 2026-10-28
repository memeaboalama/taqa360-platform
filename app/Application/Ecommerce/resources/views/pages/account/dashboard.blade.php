@extends('ecommerce::layout.layout')

@section('title', __('ecommerce::ecommerce.my_account'))

@section('content')

<style>
.account-dashboard {
    background: #f8f9fa;
    padding: 40px 0;
    min-height: 70vh;
}

/* Profile Header - Simple Flat Design Matching Tabs */
.profile-header-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    padding: 20px 25px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
}

.user-section {
    text-align: start;
}

.user-info h3 {
    font-size: 20px;
    font-weight: 700;
    color: #3e3e3e;
    margin: 0 0 5px 0;
}

.user-info p {
    font-size: 14px;
    color: #777;
    margin: 0 0 8px 0;
}

.user-balance {
    font-size: 16px;
    color: #3e3e3e;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: baseline;
    gap: 6px;
}

.user-balance i {
    color: #306FAA;
    font-size: 17px;
}

.user-balance .amount {
    color: #306FAA;
    font-weight: 700;
    font-size: 18px;
}

.user-balance small {
    color: #999;
    font-size: 13px;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-header {
    padding: 10px 18px;
    border: 1px solid #e0e0e0;
    background: #fff;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
    color: #666;
    text-decoration: none;
}

.btn-header:hover {
    background: #f8f9fa;
    color: #3e3e3e;
}

.btn-header.btn-primary {
    background: #306FAA;
    border-color: #306FAA;
    color: #fff;
}

.action-buttons a.btn-header.btn-primary,
.action-buttons a.btn-header.btn-primary:visited,
.action-buttons a.btn-header.btn-primary:hover,
.action-buttons a.btn-header.btn-primary:focus,
.action-buttons a.btn-header.btn-primary:active {
    color: #fff !important;
    text-decoration: none;
}

.btn-header.btn-primary:hover {
    background: #245C8E;
    border-color: #245C8E;
}

.btn-header i {
    font-size: 15px;
}

@media (max-width: 768px) {
    .profile-header-card {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
        padding: 18px;
    }
    
    .user-section {
        order: 1;
    }
    
    .user-info h3 {
        font-size: 17px;
    }
    
    .user-info p {
        font-size: 13px;
    }
    
    .user-balance {
        font-size: 15px;
    }
    
    .user-balance .amount {
        font-size: 16px;
    }
    
    .action-buttons {
        order: 2;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-header {
        width: 100%;
        justify-content: center;
    }
}

[dir="rtl"] .user-section {
    text-align: right;
}

@media (max-width: 768px) {
    [dir="rtl"] .user-section {
        text-align: right;
    }
}

.account-nav-tabs {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-bottom: 3px solid #306FAA;
    margin-bottom: 30px;
    padding: 0;
    display: flex;
    overflow-x: auto;
}

.account-nav-tabs::-webkit-scrollbar {
    height: 3px;
}

.account-nav-tabs::-webkit-scrollbar-thumb {
    background: #306FAA;
}

.account-tab-link {
    padding: 18px 30px;
    color: #666;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -3px;
    transition: all 0.3s;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
}

.account-tab-link,
.account-tab-link:visited,
.account-tab-link:hover,
.account-tab-link:focus,
.account-tab-link:active {
    text-decoration: none !important;
}

.account-tab-link:hover {
    color: #3e3e3e;
    background: #f8f9fa;
}

.account-tab-link.active {
    color: #306FAA;
    border-bottom-color: #306FAA;
    background: #F1F6FB;
}

.account-tab-link i {
    font-size: 18px;
}

.account-content-area {
    background: #fff;
    border: 1px solid #e0e0e0;
    padding: 30px;
    min-height: 400px;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.content-header h2 {
    font-size: 22px;
    font-weight: 700;
    color: #3e3e3e;
    margin: 0;
    text-transform: uppercase;
}

.profile-form-group {
    margin-bottom: 20px;
}

.profile-form-group label {
    font-size: 12px;
    color: #777;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: block;
}

.profile-form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    background: #f8f9fa;
    font-size: 15px;
    color: #3e3e3e;
    font-weight: 500;
}

.profile-form-group input:disabled {
    background: #f8f9fa;
    cursor: not-allowed;
}

.profile-form-group input:focus {
    outline: none;
    border-color: #306FAA;
    background: #fff;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table thead th {
    background: #f8f9fa;
    padding: 15px;
    text-align: start;
    font-weight: 600;
    color: #666;
    font-size: 13px;
    text-transform: uppercase;
    border-bottom: 2px solid #e0e0e0;
}

.orders-table tbody td {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    color: #3e3e3e;
}

.orders-table tbody tr:hover {
    background: #f8f9fa;
}

.addresses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.address-card {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    padding: 20px;
    position: relative;
    transition: all 0.3s;
}

.address-card:hover {
    border-color: #306FAA;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.address-delete-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: transparent;
    border: none;
    color: #dc3545;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s;
    z-index: 10;
    padding: 5px;
}

.address-delete-btn:hover {
    color: #bb2d3b;
    transform: scale(1.1);
}

.address-actions {
    margin-top: 15px;
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.address-card-header {
    padding-right: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.address-card-header h4 {
    font-size: 16px;
    font-weight: 700;
    color: #3e3e3e;
    margin: 0;
}

.address-default-badge {
    background: #306FAA;
    color: #ffffff;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.address-card-body p {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
    line-height: 1.6;
}

.add-address-card {
    background: #fff;
    border: 2px dashed #ccc;
    padding: 40px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.add-address-card:hover {
    border-color: #306FAA;
    background: #F1F6FB;
}

.add-address-card i {
    font-size: 40px;
    color: #ccc;
    margin-bottom: 10px;
}

.add-address-card:hover i {
    color: #306FAA;
}

.add-address-card span {
    font-size: 14px;
    font-weight: 600;
    color: #666;
}

/* RTL Support */
[dir="rtl"] .profile-form-group label {
    text-align: right;
}

[dir="rtl"] .orders-table thead th {
    text-align: right;
}

[dir="rtl"] .orders-table tbody td {
    text-align: right;
}

[dir="rtl"] .profile-text {
    text-align: right;
}

[dir="rtl"] .content-header h2 {
    text-align: right;
}

[dir="rtl"] .content-header .btn {
    float: left;
}

/* Flip arrow icons in RTL.
   .arrow-start / .arrow-end / .arrow-next are excluded: direction-utils owns
   those, and two competing transforms on the same element would break them. */
[dir="rtl"] .ti-arrow-left:not(.arrow-start):not(.arrow-end):not(.arrow-next) {
    transform: rotate(180deg);
    display: inline-block;
}

[dir="rtl"] .ti-arrow-right:not(.arrow-start):not(.arrow-end):not(.arrow-next) {
    transform: rotate(180deg);
    display: inline-block;
}

[dir="rtl"] .account-tab-link {
    text-align: right;
}

[dir="rtl"] .address-card-header {
    text-align: right;
}

[dir="rtl"] .address-card-body {
    text-align: right;
}

[dir="rtl"] .modal-header .close {
    margin: -1rem auto -1rem -1rem;
    float: left;
}

[dir="rtl"] .profile-header-card {
    text-align: right;
}

[dir="rtl"] .profile-info-section {
    text-align: right;
}

[dir="rtl"] .account-nav-tabs {
}

[dir="rtl"] .account-tab-link {
    text-align: right;
    flex-direction: row;
}

[dir="rtl"] .account-tab-link i {
    margin-left: 8px;
    margin-right: 0;
}

.account-tab-link i {
    margin-right: 8px;
}

[dir="rtl"] .account-tab-link i {
    margin-right: 0;
    margin-left: 8px;
}

@media (max-width: 768px) {
    .account-dashboard {
        padding: 15px 0 !important;
    }

    .profile-header-card {
        padding: 15px;
        margin-bottom: 20px;
        align-items: center;
    }

    .profile-avatar {
        width: 50px;
        height: 50px;
        font-size: 20px;
        margin-left: 10px !important;
        margin-right: 10px !important;
    }

    .profile-text h3 {
        font-size: 16px;
        margin-bottom: 2px;
    }

    .profile-text p {
        font-size: 12px;
    }

    .logout-btn {
        padding: 5px 15px;
        font-size: 12px;
        border-width: 1px;
    }

    .account-tab-link {
        padding: 10px 15px;
        font-size: 13px;
    }

    .account-tab-link i {
        font-size: 16px;
    }

    .account-content-area {
        padding: 15px;
        min-height: auto;
    }

    .content-header {
        margin-bottom: 15px;
        padding-bottom: 10px;
    }

    .content-header h2 {
        font-size: 18px;
    }

    .profile-form-group {
        margin-bottom: 15px;
    }

    .profile-form-group label {
        font-size: 11px;
        margin-bottom: 5px;
    }

    .profile-form-group input {
        padding: 8px 12px;
        font-size: 13px;
        height: 40px;
    }

    .btn {
        padding: 8px 15px;
        font-size: 13px;
    }

    .address-card {
        padding: 15px;
    }

    .address-card-header h4 {
        font-size: 15px;
    }

    .address-card-body p {
        font-size: 13px;
        line-height: 1.4;
    }

    .add-address-card {
        padding: 20px;
    }

    .orders-table th, .orders-table td {
        padding: 10px;
        font-size: 12px;
    }

    body {
        overflow-x: hidden;
    }

}


.account-nav-tabs {
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.account-nav-tabs::-webkit-scrollbar {
    display: none;
}

[dir="rtl"] .account-dashboard {
    direction: rtl;
    text-align: right;
}

[dir="rtl"] .profile-header-card {
    flex-direction: row;
}

[dir="rtl"] .profile-info-section {
    flex-direction: row;
    text-align: right;
}

[dir="rtl"] .profile-avatar {
    margin-left: 20px;
    margin-right: 0;
}

[dir="rtl"] .account-nav-tabs {
    flex-direction: row;
}

[dir="rtl"] .account-tab-link {
    flex-direction: row;
    margin-left: 0;
}

[dir="rtl"] .ti-angle-right:before { content: "\e64a"; }
[dir="rtl"] .ti-angle-left:before { content: "\e649"; }

[dir="rtl"] .addresses-grid {
    direction: rtl;
}

[dir="rtl"] .address-card .address-delete-btn {
    right: auto;
    left: 15px;
}

[dir="rtl"] .address-card-header {
    text-align: right;
    padding-right: 0;
    padding-left: 30px;
}

/* Wallet Header Card */
.wallet-header-card {
    background: #fcfcfc;
    border: 1px solid #eef2f5;
    border-radius: 12px;
    padding: 15px 25px;
    margin: 0 20px;
    min-width: 250px;
}
.wallet-icon {
    width: 42px; height: 42px;
    background: #fff8e1; color: #306FAA;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
}
.btn-deposit-sm {
    width: 32px; height: 32px;
    background: #306FAA; color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
    transition: all 0.2s;
    margin-inline-start: auto;
    text-decoration: none;
}
.btn-deposit-sm:hover { background: #245C8E; color: #fff; transform: scale(1.1); }

/* Mobile Adjustments */
@media (max-width: 768px) {
    .profile-header-card { flex-direction: column; align-items: stretch; gap: 20px; }
    .wallet-header-card { margin: 0; padding: 12px 15px; width: 100%; }
    /* .profile-info-section { justify-content: center; text-align: center; } */ /* Removed center to keep standard look */

    [dir="rtl"] .profile-header-card { text-align: right; }
    [dir="rtl"] .wallet-header-card { text-align: right; }
}

</style>
<div class="bread_crumb shop_bread_crumb p-0">
    <div class="container">
        <div class="row">
            <div class="col-md-6 sec_low">
                <div class="functionalities">
                    <ul id="breadcrumb" class="breadcrumb">
                        <li><a title="@lang('ecommerce::ecommerce.home')" href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                        <li><strong><i class="fa {{ app()->getLocale() == 'ar' ? 'fa-angle-double-left' : 'fa-angle-double-right' }}" aria-hidden="true"></i> @lang('ecommerce::ecommerce.my_account')</strong></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-title">
                    <h2>@lang('ecommerce::ecommerce.my_account')</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="account-dashboard section-b-space">
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header-card">
            <!-- User Info with Balance -->
            <div class="user-section">
                <div class="user-info">
                    <h3>{{ $user->name }}</h3>
                    <p class="ltr">{{ $user->email }}</p>
                    <div class="user-balance">
                        <i class="ti-wallet"></i>
                        <span class="amount">{{ number_format($user->balance, 2) }}</span>
                        <small>SAR</small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('account.wallet.deposit') }}" up-target=".dashboard-content" class="btn-header btn-primary">
                    <i class="ti-plus"></i> @lang('ecommerce::ecommerce.deposit_funds')
                </a>
                <form action="{{ route('logout') }}" method="POST" up-target=".main-body" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-header">
                        <i class="ti-power-off"></i> @lang('ecommerce::ecommerce.logout')
                    </button>
                </form>
            </div>
        </div>

        <div class="account-nav-tabs" id="account-tabs">
            <a href="{{ route('account.profile') }}"
               class="account-tab-link"
               up-target=".dashboard-content"
               up-alias="{{ route('account.index') }}"
               onclick="setActiveTab(this)">
                <i class="ti-user"></i>
                <span>@lang('ecommerce::ecommerce.personal_info')</span>
            </a>
            <a href="{{ route('account.orders') }}"
               class="account-tab-link"
               up-target=".dashboard-content"
               onclick="setActiveTab(this)">
                <i class="ti-package"></i>
                <span>@lang('ecommerce::ecommerce.orders')</span>
            </a>
            <a href="{{ route('account.service_requests') }}"
               class="account-tab-link"
               up-target=".dashboard-content"
               onclick="setActiveTab(this)">
                <i class="ti-settings"></i>
                <span>@lang('ecommerce::ecommerce.service_requests')</span>
            </a>
            <a href="{{ route('account.addresses') }}"
               class="account-tab-link"
               up-target=".dashboard-content"
               onclick="setActiveTab(this)">
                <i class="ti-location-pin"></i>
                <span>@lang('ecommerce::ecommerce.addresses')</span>
            </a>
            <a href="{{ route('account.wallet.index') }}"
               class="account-tab-link"
               up-target=".dashboard-content"
               onclick="setActiveTab(this)">
                <i class="ti-wallet"></i>
                <span>@lang('ecommerce::ecommerce.my_wallet')</span>
            </a>
        </div>

        <div class="dashboard-content">
            @if(isset($page))
                @if($page == 'profile')
                    @include('ecommerce::pages.account._profile')
                @elseif($page == 'orders')
                    @include('ecommerce::pages.account._orders')
                @elseif($page == 'service_requests')
                    @include('ecommerce::pages.account._service_requests')
                @elseif($page == 'service_request_details')
                    @include('ecommerce::pages.account._service_request_details')
                @elseif($page == 'addresses')
                    @include('ecommerce::pages.account._addresses')
                @elseif($page == 'edit_profile')
                    @include('ecommerce::pages.account._edit_profile')
                @elseif($page == 'create_address')
                    @include('ecommerce::pages.account._create_address')
                @elseif($page == 'wallet')
                    @include('ecommerce::pages.account.wallet._index')
                @elseif($page == 'wallet_deposit')
                    @include('ecommerce::pages.account.wallet._deposit')
                @elseif($page == 'wallet_withdraw')
                    @include('ecommerce::pages.account.wallet._withdraw')
                @else
                    @include('ecommerce::pages.account._profile')
                @endif
            @else
                @include('ecommerce::pages.account._profile')
            @endif
        </div>
    </div>
</section>

<script>
    function setActiveTab(clickedElement) {
        document.querySelectorAll('.account-tab-link').forEach(link => link.classList.remove('active'));
        clickedElement.classList.add('active');
    }
    function updateTabsFromUrl() {
        const currentPath = window.location.pathname;
        const tabLinks = document.querySelectorAll('.account-tab-link');

        let matched = false;
        tabLinks.forEach(link => {
            link.classList.remove('active');

            const linkUrl = new URL(link.getAttribute('href'), window.location.origin);
            if (currentPath === linkUrl.pathname || (currentPath === '/account' && linkUrl.pathname === '/account/profile')) {
                link.classList.add('active');
                matched = true;
            } else {
                if (currentPath.startsWith(linkUrl.pathname) && linkUrl.pathname !== '/account/profile') {
                    link.classList.add('active');
                    matched = true;
                }
            }
        });

        if (!matched) {
            if (currentPath === '/account' || currentPath === '/account/') {
                const profileTab = document.querySelector('a[href*="profile"]');
                if(profileTab) profileTab.classList.add('active');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', updateTabsFromUrl);
    if (typeof up !== 'undefined') {
        up.on('up:location:changed', updateTabsFromUrl);
    }
</script>

@endsection
