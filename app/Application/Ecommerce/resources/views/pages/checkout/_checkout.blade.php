@extends('ecommerce::layout.layout')

@section('content')

{{-- @include('ecommerce::layout.breadcrumb' , ['title' => __('ecommerce::ecommerce.checkout')]) --}}

<section class="section-b-space">
    <div class="checkout-container">
        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" up-submit up-fail-target=".checkout-container">
            @csrf

            {{-- Errors and Success messages are handled by layout script or auto-toast below --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification("{{ session('success') }}", 'success');
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showNotification("{{ session('error') }}", 'danger');
                    });
                </script>
            @endif

            <div class="checkout-layout">

                <div class="checkout-main">

                    <div class="checkout-section">
                        <div class="section-header">
                            <h4 class="section-title">@lang('admin::main.lang.products')</h4>
                        </div>
                        <div class="section-content-card">
                            <div class="product-rows">
                                @foreach($cart->cartItems as $item)
                                    <div class="product-row">
                                        <div class="product-remove">
                                            <button type="submit" form="remove-item-{{ $item->item_id }}" class="btn-remove" title="Remove">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="product-img">
                                            <img src="{{ $item->item?->image_path ?? asset('assets/images/pro/b1.jpg') }}"
                                                 alt="{{ $item->item?->title ?? $item->item_id }}"
                                                 onerror="this.src='{{ asset('assets/images/pro/b1.jpg') }}'">
                                        </div>
                                        <div class="product-info">
                                            <h5 class="product-name">{{ $item->item?->title ?? '#' }}</h5>
                                            @if($item->cartChoices->count() > 0)
                                                <div class="product-extras">
                                                    @foreach($item->cartChoices as $choice)
                                                        <span class="extra-tag">
                                                            {{ $choice->choice->title ?? $choice->group->title }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="product-qty">
                                            x {{ $item->qty }}
                                        </div>
                                        <div class="product-price">
                                            <small>{{ \App\Domain\AppBase\Models\Setting\Currency::format($item->subtotal) }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="checkout-section">
                        <div class="section-header">
                            <h4 class="section-title">@lang('ecommerce::ecommerce.shipping_details')</h4>
                        </div>

                        <div class="section-content-card">

                            <div class="group-part">
                                <h5 class="sub-title">@lang('ecommerce::ecommerce.select_address')</h5>
                                @if($addresses->isEmpty())
                                    <div class="empty-address">
                                        <p>@lang('ecommerce::ecommerce.no_address_found')</p>
                                        <a href="{{ route('account.addresses') }}">@lang('ecommerce::ecommerce.add_new_address')</a>
                                    </div>
                                @else
                                    <div class="address-grid">
                                        @foreach($addresses as $address)
                                            <label class="address-item">
                                                <input type="radio" name="address_id" value="{{ $address->id }}"
                                                    {{ old('address_id', $addresses->firstWhere('is_default', 1)?->id ?? $addresses->first()?->id) == $address->id ? 'checked' : '' }}>
                                                <div class="address-box">
                                                    <div class="address-header">
                                                        <span class="addr-title">
                                                            {{ $address->title }}
                                                            @if($address->is_default) <span class="badge badge-success" style="font-size: 9px; padding: 2px 6px;">@lang('ecommerce::ecommerce.default')</span> @endif
                                                        </span>
                                                        <span class="addr-phone">{{ $address->phone }}</span>
                                                    </div>
                                                    <div class="address-details">
                                                        {{ $address->city?->title }}
                                                        @if($address->district) - {{ $address->district?->title }} @endif
                                                        - {{ $address->address }}
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach

                                        <label class="address-item address-item-new">
                                            <input type="radio" name="address_id" value="new" id="new-address-radio" {{ old('address_id') == 'new' ? 'checked' : '' }}>
                                            <div class="address-box address-box-new">
                                                <div class="new-address-trigger">
                                                    <i class="fa fa-plus-circle"></i>
                                                    <span>@lang('ecommerce::ecommerce.add_new_address')</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="new-address-form" id="new-address-form"
                                         style="{{ old('address_id') == 'new' || $errors->any() ? '' : 'display: none;' }}">
                                        <div class="form-grid">
                                            <div class="form-field form-field-full">
                                                <label>@lang('ecommerce::ecommerce.address_title') *</label>
                                                <input type="text" name="new_address_title" class="compact-input {{ $errors->has('new_address_title') ? 'is-invalid' : '' }}"
                                                       value="{{ old('new_address_title') }}" placeholder="@lang('ecommerce::ecommerce.address_title_placeholder')">
                                                @error('new_address_title') <span class="error-msg">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-field">
                                                <label>@lang('ecommerce::ecommerce.country') *</label>
                                                <select name="new_address_country_id" class="compact-input {{ $errors->has('new_address_country_id') ? 'is-invalid' : '' }}">
                                                    <option value="">@lang('ecommerce::ecommerce.select_country')</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('new_address_country_id') == $country->id ? 'selected' : '' }}>{{ $country->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('new_address_country_id') <span class="error-msg">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-field">
                                                <label>@lang('ecommerce::ecommerce.city') *</label>
                                                <select name="new_address_city_id" class="compact-input {{ $errors->has('new_address_city_id') ? 'is-invalid' : '' }}" id="new-address-city">
                                                    <option value="">@lang('ecommerce::ecommerce.select_city')</option>
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->id }}" {{ old('new_address_city_id') == $city->id ? 'selected' : '' }}>{{ $city->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('new_address_city_id') <span class="error-msg">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-field">
                                                <label>@lang('ecommerce::ecommerce.district')</label>
                                                <select name="new_address_district_id" class="compact-input {{ $errors->has('new_address_district_id') ? 'is-invalid' : '' }}" id="new-address-district">
                                                    <option value="">@lang('ecommerce::ecommerce.select_district')</option>
                                                </select>
                                                @error('new_address_district_id') <span class="error-msg">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="form-field form-field-full">
                                                <label>@lang('ecommerce::ecommerce.street_address') *</label>
                                                <textarea name="new_address_address" class="compact-input {{ $errors->has('new_address_address') ? 'is-invalid' : '' }}"
                                                          rows="2" placeholder="@lang('ecommerce::ecommerce.street_address')">{{ old('new_address_address') }}</textarea>
                                                @error('new_address_address') <span class="error-msg">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @error('address_id')
                                    <div class="error-msg">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="section-divider">

                            <div class="group-part">
                                <h5 class="sub-title">@lang('ecommerce::ecommerce.order_notes')</h5>
                                <textarea class="compact-input" name="note" rows="3" placeholder="@lang('ecommerce::ecommerce.order_notes_placeholder')"></textarea>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="checkout-sidebar">

                    <div class="sidebar-box">
                        <h4 class="sidebar-title">@lang('ecommerce::ecommerce.order_summary')</h4>

                        <div class="sidebar-group">
                            <label class="sidebar-label">@lang('ecommerce::ecommerce.promo_code')</label>

                            @if($cart->promocode)
                                <div class="applied-coupon-info">
                                    <div class="coupon-badge">
                                        <i class="fa fa-ticket"></i>
                                        <span class="coupon-code ltr">{{ $cart->promocode }}</span>
                                    </div>
                                    <button type="submit" form="remove-coupon-form" class="btn-remove-coupon-text" title="@lang('ecommerce::ecommerce.remove_coupon')">
                                        @lang('ecommerce::ecommerce.remove')
                                    </button>
                                </div>
                                @if(session('coupon_success'))
                                    <div class="coupon-message coupon-success">
                                        <i class="fa fa-check-circle"></i> {{ session('coupon_success') }}
                                    </div>
                                @endif
                            @else
                                <div class="coupon-field">
                                    <input type="text" class="compact-input" name="promocode" form="apply-coupon-form"
                                           value="{{ old('promocode') }}"
                                           placeholder="@lang('ecommerce::ecommerce.inter_promo_code')">
                                    <button type="submit" form="apply-coupon-form" class="btn-apply-text" title="@lang('ecommerce::ecommerce.apply')">
                                        @lang('ecommerce::ecommerce.apply')
                                    </button>
                                </div>

                                @if(session('coupon_error'))
                                    <div class="coupon-message coupon-error">
                                        <i class="fa fa-exclamation-circle"></i> {{ session('coupon_error') }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="sidebar-group">
                            <label class="sidebar-label">@lang('ecommerce::ecommerce.payment_method')</label>
                            <div class="payment-options">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="wallet"
                                        {{ old('payment_method') == 'wallet' ? 'checked' : '' }}>
                                    <div class="payment-content">
                                        <div class="pay-info">
                                            <span class="pay-name">@lang('ecommerce::ecommerce.pay_wallet')</span>
                                            <small class="d-block" style="color: #28a745; font-weight: bold;">
                                                @lang('ecommerce::ecommerce.balance'): {{ \App\Domain\AppBase\Models\Setting\Currency::format(auth()->user()->balance) }}
                                            </small>
                                        </div>
                                        <i class="fa fa-money pay-icon" style="color: var(--primary);"></i>
                                    </div>
                                </label>

                             {{--     <label class="payment-option">
                                    <input type="radio" name="payment_method" value="{{ \App\Support\Enum\PaymentMethod::MADA->value }}"
                                        {{ old('payment_method') == \App\Support\Enum\PaymentMethod::MADA->value ? 'checked' : '' }}>
                                    <div class="payment-content">
                                        <div class="pay-info">
                                            <span class="pay-name">@lang('ecommerce::ecommerce.pay_online')</span>
                                            <small class="text-muted d-block">Mada, Visa, MasterCard</small>
                                        </div>
                                        <div class="payment-icons-container">
                                              <i class="fa fa-cc-visa" style="color: #1a1f71; font-size: 24px;"></i>
                                              <i class="fa fa-cc-mastercard" style="color: #eb001b; font-size: 24px;"></i>
                                              <i class="fa fa-credit-card" style="color: #333; font-size: 24px;"></i>
                                        </div>
                                    </div>
                                </label>--}}

                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="{{ \App\Support\Enum\PaymentMethod::COD->value }}"
                                        {{ old('payment_method', \App\Support\Enum\PaymentMethod::COD->value) == \App\Support\Enum\PaymentMethod::COD->value ? 'checked' : '' }}>
                                    <div class="payment-content">
                                        <div class="pay-info">
                                            <span class="pay-name">@lang('ecommerce::ecommerce.cod')</span>
                                        </div>
                                        <i class="fa fa-truck pay-icon"></i>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="error-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>@lang('ecommerce::ecommerce.subtotal')</span>
                                <span>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->subtotal) }}</span>
                            </div>
                            @if($cart->delivery_fee > 0)
                            <div class="total-row">
                                <span>@lang('ecommerce::ecommerce.delivery_fee')</span>
                                <span>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->delivery_fee) }}</span>
                            </div>
                            @endif
                            <div class="total-row">
                                <span>@lang('ecommerce::ecommerce.vat')</span>
                                <span>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->tax) }}</span>
                            </div>
                            @if($cart->discount > 0)
                            <div class="total-row discount">
                                <span>@lang('ecommerce::ecommerce.discount')</span>
                                <span>-{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->discount) }}</span>
                            </div>
                            @endif
                            <div class="total-row grand-total">
                                <span>@lang('ecommerce::ecommerce.total')</span>
                                <span>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->total_after_vat) }}</span>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" class="submit-btn" id="place-order-btn">
                            @lang('ecommerce::ecommerce.place_order')
                        </button>

                        <div class="secure-badge">
                            <i class="fa fa-lock"></i> @lang('ecommerce::ecommerce.secure_checkout')
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</section>

<script>
    up.compiler('.checkout-container', function(container) {
        // ... existing compiler code ...
        // Auto-show notifications if flashed in session
        @if(session('success'))
            showNotification("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showNotification("{{ session('error') }}", 'danger');
        @endif
        @if($errors->any())
             // Also show generic error if validation fails
             showNotification("@lang('ecommerce::ecommerce.please_fix_errors')", 'danger');
        @endif

        const newAddressRadio = container.querySelector('#new-address-radio');
        const newAddressForm = container.querySelector('#new-address-form');
        const allAddressRadios = container.querySelectorAll('input[name="address_id"]');
        const citySelect = container.querySelector('#new-address-city');
        const districtSelect = container.querySelector('#new-address-district');

        function toggleNewAddressForm() {
            if (newAddressRadio && newAddressRadio.checked) {
                if(newAddressForm) {
                    newAddressForm.style.display = 'block';
                    toggleRequired(newAddressForm, true);
                }
            } else {
                if(newAddressForm) {
                    newAddressForm.style.display = 'none';
                    toggleRequired(newAddressForm, false);
                }
            }
        }

        function toggleRequired(formContainer, isRequired) {
            const inputs = formContainer.querySelectorAll('input[name^="new_address_"], select[name^="new_address_"], textarea[name^="new_address_"]');
            inputs.forEach(el => {
                if (el.name !== 'new_address_district_id') {
                    el.required = isRequired;
                }
            });
        }

        if (allAddressRadios.length) {
            allAddressRadios.forEach(radio => {
                radio.addEventListener('change', toggleNewAddressForm);
            });
            // Initialize state
            toggleNewAddressForm();
        }

        // Place Order button: disable + show spinner while submitting
        const placeOrderBtn = document.getElementById('place-order-btn');
        if (placeOrderBtn) {
            if (!placeOrderBtn.dataset.label) {
                placeOrderBtn.dataset.label = placeOrderBtn.innerHTML.trim();
            }
            placeOrderBtn.innerHTML = placeOrderBtn.dataset.label;
            placeOrderBtn.disabled = false;
        }
        const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm && placeOrderBtn) {
            checkoutForm.addEventListener('submit', function () {
                placeOrderBtn.disabled = true;
                placeOrderBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>' + placeOrderBtn.dataset.label;
            });
        }

        if (!window.__checkoutButtonBound) {
            window.__checkoutButtonBound = true;
            up.on('up:request:end', function () {
                const btn = document.getElementById('place-order-btn');
                if (btn && btn.dataset.label) {
                    btn.disabled = false;
                    btn.innerHTML = btn.dataset.label;
                }
            });
            up.on('up:request:loadfailed', function () {
                const btn = document.getElementById('place-order-btn');
                if (btn && btn.dataset.label) {
                    btn.disabled = false;
                    btn.innerHTML = btn.dataset.label;
                }
            });
        }

        if (citySelect && districtSelect) {
            citySelect.addEventListener('change', function() {
                const cityId = this.value;
                districtSelect.innerHTML = '<option value="">@lang('ecommerce::ecommerce.select_district')</option>';

                if (cityId) {
                    // Use up.request or fetch? fetch is fine.
                    fetch(`/api/cities/${cityId}/districts`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length) {
                                data.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district.id;
                                    option.textContent = district.title;
                                    districtSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching districts:', error));
                }
            });
        }
    });
</script>
@foreach($cart->cartItems as $item)
    <form id="remove-item-{{ $item->item_id }}" action="{{ route('cart.remove') }}" method="POST" up-submit up-target="body">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->item_id }}">
    </form>
@endforeach
<form id="apply-coupon-form" action="{{ route('checkout.apply-coupon') }}" method="POST" up-submit up-target=".checkout-sidebar">
    @csrf
</form>

<form id="remove-coupon-form" action="{{ route('checkout.remove-coupon') }}" method="POST" up-submit up-target=".checkout-sidebar">
    @csrf
</form>

<style>
    /* Reset & Base */
    :root {
        --primary: #306FAA;
        --primary-hover: #245C8E;
        --content-bg: rgba(252, 252, 252, 0.9);
        --glass-border: 1px solid rgba(0, 0, 0, 0.04);
        --radius: 12px;
    }

    .error-msg {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
        display: block;
        font-weight: 500;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }

    .checkout-container {
        max-width: 1250px; /* Wider layout as requested */
        margin: 0 auto;
        padding: 30px 20px;
        direction: ltr;
    }
    /* RTL Override */
    [dir="rtl"] .checkout-container {
        direction: rtl;
    }

    .checkout-layout {
        display: flex;
        gap: 40px; /* More spacing between main and sidebar */
        align-items: flex-start;
    }
    .checkout-main {
        flex: 1;
    }
    .checkout-sidebar {
        width: 380px; /* Slightly wider sidebar */
        flex-shrink: 0;
        position: sticky;
        top: 30px;
    }

    /* Sections */
    .section-header {
        margin-bottom: 12px;
        padding-inline-start: 5px;
    }
    .section-title {
        font-size: 16px;
        font-weight: 800;
        margin: 0;
        color: #333;
        display: inline-block;
        position: relative;
    }
    .checkout-section {
        margin-bottom: 35px;
    }

    /* Content Card Wrapper */
    .section-content-card {
        background: var(--content-bg);
        border: var(--glass-border);
        border-radius: var(--radius);
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
    }

    .sub-title {
        font-size: 14px;
        font-weight: 700;
        color: #555;
        margin-bottom: 15px;
        display: block;
    }

    /* Product Rows */
    .product-rows {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .product-row {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }
    .product-row:hover {
        border-color: #e0e0e0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }

    /* Button Remove Style */
    .product-remove {
        margin-inline-end: 15px;
    }
    .btn-remove {
        background: transparent;
        border: none;
        color: #999;
        width: auto;
        height: auto;
        padding: 0;
        cursor: pointer;
        font-size: 14px;
        transition: transform 0.2s, color 0.2s;
    }
    .btn-remove:hover {
        color: #ff4d4d;
        background: transparent;
        border-color: transparent;
        transform: scale(1.2);
    }

    .product-img {
        width: 60px;
        height: 60px;
        margin-inline-end: 20px;
        flex-shrink: 0;
    }
    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Contain to show full product */
        background: #fff;
        padding: 2px;
        border-radius: 6px;
        border: 1px solid #f9f9f9;
    }
    .product-info {
        flex: 1;
    }
    .product-name {
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #222;
        line-height: 1.3;
    }
    .product-extras {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .extra-tag {
        font-size: 11px;
        background: rgba(48, 111, 170, 0.1);
        color: #245C8E;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .product-qty {
        font-size: 13px;
        color: #888;
        margin: 0 20px;
        font-weight: 500;
    }
    .product-price {
        font-weight: 800;
        font-size: 16px;
        color: #333;
        min-width: 80px;
        text-align: end;
    }

    /* Address Grid (New Layout) */
    .address-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }
    .address-item {
        margin: 0;
        cursor: pointer;
    }
    .address-item input { display: none; }
    /* Address Box with Checkmark */
    .address-box {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        height: 100%;
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }
    .address-item input:checked + .address-box {
        border-color: var(--primary);
        background: rgba(245, 179, 36, 0.04);
        box-shadow: 0 0 0 1px var(--primary) inset;
    }
    /* Add Checkmark Badge */
    .address-item input:checked + .address-box::before {
        content: "\f00c"; /* FontAwesome Check */
        font-family: "FontAwesome";
        position: absolute;
        top: 0;
        left: 0; /* LTR default, will flip with RTL logic if needed or use logic based on dir */
        background: var(--primary);
        color: #fff;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border-bottom-right-radius: 8px; /* LTR */
    }
    [dir="rtl"] .address-item input:checked + .address-box::before {
        left: auto;
        right: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 8px;
    }
    .address-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #333;
    }
    .address-details {
        font-size: 12px;
        color: #666;
        line-height: 1.5;
    }

    /* New Address Card & Form Styles */
    .address-box-new {
        border: 2px dashed #ddd;
        background: #fafafa;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100px;
    }
   .address-box-new:hover {
        border-color: var(--primary);
        background: rgba(245, 179, 36, 0.05);
    }
    .new-address-trigger {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
    }
    .new-address-trigger i {
        font-size: 28px;
    }
    .new-address-form {
        margin-top: 20px;
        padding: 20px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 10px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .form-field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
    }
    .form-field-full {
        grid-column: 1 / -1;
    }
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-field-full {
            grid-column: 1;
        }
    }

    .section-divider {
        margin: 25px 0;
        border: 0;
        border-top: 1px dashed #ddd;
    }

    /* Note Input */
    .compact-input {
        width: 100%;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 12px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
    }
    .compact-input:focus { border-color: var(--primary); }

    /* Sidebar Box */
    .sidebar-box {
        background: #fff;
        border-radius: var(--radius);
        padding: 30px 25px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    .sidebar-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f5f5f5;
    }
    .sidebar-group {
        margin-bottom: 25px;
    }
    .sidebar-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 10px;
        display: block;
        letter-spacing: 0.5px;
    }

    /* Coupon */
    .coupon-field {
        display: flex;
        position: relative;
    }
    .coupon-field .compact-input {
        padding-inline-end: 80px; /* Increased for text button */
    }
    .btn-apply-text {
        position: absolute;
        inset-inline-end: 4px;
        top: 4px;
        bottom: 4px;
        padding: 0 15px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s;
    }
    .btn-apply-text:hover { background: var(--primary-hover); }

    /* Applied Coupon Styles */
    .applied-coupon-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(48, 111, 170, 0.1);
        border: 1px solid var(--primary);
        border-radius: 8px;
        padding: 10px 12px;
    }
    .coupon-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--primary);
    }
    .coupon-badge i {
        font-size: 16px;
    }
    .coupon-code {
        font-weight: 700;
        font-size: 13px;
        color: #333;
        text-transform: uppercase;
    }
    .btn-remove-coupon-text {
        background: transparent;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.2s;
        text-transform: uppercase;
    }
    .btn-remove-coupon-text:hover {
        background: #fee;
        text-decoration: underline;
    }

    /* Coupon Messages */
    .coupon-message {
        margin-top: 10px;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .coupon-message i {
        font-size: 14px;
    }
    .coupon-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .coupon-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Payment Options */
    .payment-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .payment-option {
        cursor: pointer;
        margin: 0;
    }
    .payment-option input { display: none; }
    .payment-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f9f9f9;
        border: 1px solid transparent;
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .payment-option input:checked + .payment-content {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 1px var(--primary) inset;
    }
    .pay-name { font-size: 13px; font-weight: 700; color: #333; }
    .pay-icons { height: 20px; vertical-align: middle; }
    .payment-icons-container {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    /* Colored Payment Icons */
    .pay-icon { font-size: 18px; color: #2ecc71; } /* Green for money/cash */
    .payment-option input:checked + .payment-content .pay-icon { color: #27ae60; }

    .text-muted { font-size: 11px; color: #888; margin-top: 2px; }

    /* Totals */
    .order-totals {
        border-top: 1px dashed #ddd;
        padding-top: 20px;
        margin-top: 20px;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        margin-bottom: 12px;
        color: #666;
    }
    .total-row.grand-total {
        font-size: 18px;
        font-weight: 800;
        color: #222;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f5f5f5;
    }
    .discount { color: #2ecc71; }

    .submit-btn {
        width: 100%;
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 14px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        margin-top: 25px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 5px 15px rgba(245, 179, 36, 0.2);
    }
    .submit-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }

    .secure-badge {
        text-align: center;
        font-size: 11px;
        color: #999;
        margin-top: 15px;
    }

    .error-msg { color: #e74c3c; font-size: 11px; margin-top: 5px; display: block; }

    /* Responsive */
    @media (max-width: 991px) {
        .checkout-layout {
            flex-direction: column;
        }
        .checkout-sidebar {
            width: 100%;
            position: static;
        }
        .checkout-container {
            padding: 20px 15px;
        }
    }
</style>
@endsection
