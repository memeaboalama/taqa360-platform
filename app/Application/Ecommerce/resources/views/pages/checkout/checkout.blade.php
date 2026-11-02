@extends('ecommerce::layout.store-layout')

{{-- @section bodies are evaluated BEFORE the parent layout runs, so $isRTL has to be
     resolved here rather than relying on the value store-layout computes. --}}
@php $isRTL = $isRTL ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp

@section('title', __('ecommerce::ecommerce.checkout'))

@section('page-styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
        :root{--ink:#20252d;--muted:#747b84;--line:#e9edf1;--yellow:#306FAA;--gold:#EA993E;--soft:#f6f7f9;--shadow:0 8px 24px rgba(36,43,53,.07)}
        *{box-sizing:border-box}
        body{margin:0;color:var(--ink);background:var(--soft);font:13px 'Cairo',Tahoma,sans-serif}a{text-decoration:none;color:inherit}button,input,select,textarea{font:inherit}button{border:0;cursor:pointer}
        .container{width:min(1260px,calc(100% - 40px));margin:auto}
        .topbar{height:74px;background:#fff;border-bottom:1px solid #f0f1f3;display:flex;align-items:center}.topbar-inner{display:flex;align-items:center;justify-content:space-between;gap:25px}.brand{min-width:172px}.brand img{display:block;width:175px;height:76px;object-fit:contain}.search{height:42px;max-width:535px;flex:1;display:flex;direction:ltr;border:1px solid var(--line);border-radius:9px;overflow:hidden}.search input{flex:1;border:0;outline:0;direction:{{ $isRTL ? 'rtl' : 'ltr' }};text-align:{{ $isRTL ? 'right' : 'left' }};padding:0 18px}.search button{width:48px;background:var(--yellow);color:#fff;font-size:18px}.top-actions{display:flex;align-items:center;gap:18px;color:#5d6269;direction:ltr}.action{font-size:19px}.service{padding:8px 13px;border-radius:5px;background:var(--yellow);color:#fff;font-size:11px;font-weight:700}
        .nav{height:51px;background:#fff;border-bottom:1px solid #f3f3f3;box-shadow:0 3px 12px #00000008}.nav ul{height:100%;display:flex;align-items:center;justify-content:center;gap:40px;list-style:none;margin:0;padding:0}.nav a{font-size:12px}.nav a.active,.nav a:hover{color:var(--gold);font-weight:700}
        .checkout-hero{padding:32px 0 42px;color:#fff;background:linear-gradient(110deg,#102d49 0%,#173f65 52%,#245c8e 100%)}.checkout-hero h1{margin:0 0 7px;color:#fff;font-size:28px}.checkout-hero p{margin:0}
        .checkout-main{padding:28px 0 70px}.checkout-layout{display:grid;grid-template-columns:minmax(0,1fr) 330px;gap:22px;direction:inherit}.checkout-form,.checkout-sidebar{direction:inherit}
        .checkout-section{background:#fff;border:1px solid #eef0f3;border-radius:12px;padding:22px;box-shadow:var(--shadow);margin-bottom:22px}.section-heading{margin:0 0 18px;font-size:18px;color:#1a1d22}.section-content{display:flex;flex-direction:column;gap:14px}
        .product-row{display:grid;grid-template-columns:52px minmax(0,1fr) 68px 120px;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid #f0f2f5}.product-row:last-child{border-bottom:none}.product-row img{width:52px;height:52px;border-radius:8px;object-fit:cover;background:#f5f5f5}.product-name{margin:0 0 4px;font-size:15px;color:#222}.product-meta{color:var(--muted);font-size:12px}.product-qty{font-weight:700;color:#626c77}.product-price{font-weight:700;color:#111;text-align:end;direction:ltr;unicode-bidi:isolate}.address-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.address-option{display:block}.address-option input{display:none}.address-box{display:block;padding:14px;border:1px solid #e7ebf0;border-radius:10px;background:#fdfdfd;cursor:pointer;transition:.2s}.address-option input:checked + .address-box{border-color:var(--yellow);background:#f1f6fb;box-shadow:0 0 0 1px rgba(48,111,170,.25)}.address-title{display:block;font-size:14px;font-weight:700;margin-bottom:6px}.address-detail{color:var(--muted);font-size:12px;line-height:1.7}.address-option-new{margin-top:12px}.new-address-form{margin-top:14px;padding:14px;background:#fafbfc;border:1px solid #eef0f3;border-radius:10px}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.form-field{display:flex;flex-direction:column;gap:8px}.form-field-full{grid-column:1/-1}.compact-input{width:100%;padding:11px 12px;border:1px solid #e7ecf2;border-radius:8px;background:#fff;color:#1f2630}.compact-input:focus{outline:none;border-color:var(--yellow);box-shadow:0 0 0 3px rgba(48,111,170,.12)}
        .error-msg{display:block;margin-top:4px;color:#d93025;font-size:12px}.coupon-field{display:flex;gap:8px}.coupon-field input,.coupon-field button,.btn-primary,.btn-secondary{border-radius:8px}.coupon-field input{flex:1;padding:10px 12px;border:1px solid #e7ecf2}.btn-apply,.btn-primary,.btn-secondary{padding:10px 16px;font-weight:700}.btn-apply,.btn-primary{background:var(--yellow);color:#fff}.btn-secondary{background:#f0f2f5;color:#222}.summary-row{display:flex;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px dashed #edf0f3}.summary-row:last-child{border-bottom:none}.summary-row.total{font-size:18px;font-weight:800;padding-top:10px}.sidebar-box{background:#fff;border:1px solid #eef0f3;border-radius:12px;padding:22px;box-shadow:var(--shadow)}.sidebar-title{margin:0 0 18px;font-size:20px}.sidebar-group{margin-top:12px}.sidebar-label{display:block;margin-bottom:8px;font-weight:700;color:#2d3742}.applied-coupon-info{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:12px;background:#fff9e5;border:1px solid #f7e7a9;border-radius:8px}.coupon-code{font-weight:700}.coupon-message{margin-top:10px;font-size:12px}.coupon-success{color:#1b7f45}.alert{padding:12px 14px;border-radius:8px;margin-bottom:16px}.alert-danger{background:#fdecec;color:#b42318;border:1px solid #f5c2c7}.alert-success{background:#eafaf1;color:#1d7a45;border:1px solid #bfe7ca}
        .payment-method-card{background:#fff;border:1px solid #e7ebf0;border-radius:12px;padding:15px;transition:all 0.2s}.payment-method-card:hover{border-color:#c1c7d1}.payment-method-card.wallet-method .payment-text{color:#28a745}.payment-method-card.cod-method .payment-text{color:#e86e5a}.payment-text{font-weight:700;font-size:13px;color:#333;display:inline-block;vertical-align:middle;margin-left:8px}.payment-option input{display:none}.payment-method-card+ .payment-method-card{margin-inline-start:0;margin-top:12px}
        .checkout-location-map{height:320px;width:100%;border:1px solid #e7ecf2;border-radius:8px;z-index:0}.location-coordinates{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 12px;border:1px solid #dce8f3;border-radius:8px;background:#f4f8fc}.location-coordinates-label{font-weight:700;color:#245c8e}.location-coordinates-value{direction:ltr;unicode-bidi:isolate;font-family:monospace;font-size:14px;color:#20252d}.location-map-actions{display:flex;align-items:center;gap:12px;flex-wrap:wrap}.location-map-status{margin:0;color:var(--muted);font-size:12px}.location-map-button{align-self:flex-start;padding:9px 13px;border:1px solid #d6e0ea;border-radius:8px;background:#fff;color:#245c8e;font-weight:700}.location-map-button:hover{background:#f1f6fb}.leaflet-container{font:inherit}
        .wallet-balance-error{display:flex;align-items:flex-start;gap:8px;margin-top:12px;padding:10px 12px;border:1px solid #f5c2c7;border-radius:8px;background:#fff5f5;color:#b42318;font-size:12px;line-height:1.6}.wallet-balance-error i{margin-top:2px;flex-shrink:0}.wallet-balance-error a{display:inline-block;margin-top:4px;color:#245c8e;font-weight:700;text-decoration:underline}
        footer{background:#252b31;color:#fff;padding:24px 0 18px;margin-top:0}.footer-grid{display:flex;justify-content:center}.footer-col{width:100%}.footer-brand{text-align:center}.footer-logo{display:block;width:160px;max-height:80px;object-fit:contain;margin:0 auto 8px}.footer-brand p{margin:0;font-size:14px;line-height:1.8;color:#d7dadd}.copyright{display:flex;align-items:center;justify-content:space-between;gap:20px;border-top:1px solid rgba(255,255,255,0.14);color:#afb4b8;font-size:11px;margin-top:15px;padding-top:10px}.footer-links{display:flex;gap:18px;flex-wrap:wrap}.footer-links a,.socials a{color:#d2d4d6;font-size:10px;text-decoration:none}.socials{display:flex;gap:14px;align-items:center}.socials a{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.06)}
        @media(max-width:900px){.checkout-layout{grid-template-columns:1fr}.address-grid{grid-template-columns:1fr}.form-grid{grid-template-columns:1fr}.copyright{flex-wrap:wrap;justify-content:center;text-align:center}.footer-links{gap:10px}}
    </style>
@endsection

@section('content')
@php
    $checkoutVatRate = (float) ($cart->cartItems->first()?->item?->merchant?->country?->vat ?? 0);
    $checkoutDeliveryFee = $cart->service_type_id == 2 ? 0 : (float) $cart->delivery_fee;
    $checkoutVatLabel = rtrim(rtrim(number_format($checkoutVatRate, 2, '.', ''), '0'), '.');
@endphp
<section class="checkout-hero">
    <div class="container">
        <h1>@lang('ecommerce::ecommerce.checkout')</h1>
        <p>@lang('ecommerce::ecommerce.checkout_message')</p>
    </div>
</section>

<div class="page-content checkout-main">
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="checkout-layout">
                <div class="checkout-form">
                    <section class="checkout-section">
                        <h2 class="section-heading">@lang('ecommerce::ecommerce.order_items')</h2>
                        <div class="section-content">
                            @foreach ($cart->cartItems as $item)
                                <div class="product-row">
                                    <img src="{{ $item->item?->image_path ?? asset('assets/images/pro/b1.jpg') }}" alt="{{ $item->item?->title ?? 'Product' }}">
                                    <div>
                                        <strong class="product-name">{{ $item->item?->title ?? '#' }}</strong>
                                        <div class="product-meta">@lang('ecommerce::ecommerce.product_label')</div>
                                    </div>
                                    <span class="product-qty">x {{ $item->qty }}</span>
                                    <strong class="product-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($item->subtotal) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="checkout-section">
                        <h2 class="section-heading">@lang('ecommerce::ecommerce.shipping_details')</h2>
                        <div class="section-content">
                            <h3 class="product-name">@lang('ecommerce::ecommerce.address')</h3>

                            @if ($addresses->isEmpty())
                                <p class="product-meta">@lang('ecommerce::ecommerce.no_address_found')</p>
                            @else
                                <div class="address-grid">
                                    @foreach ($addresses as $address)
                                        <label class="address-option">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" {{ old('address_id', $addresses->firstWhere('is_default', 1)?->id ?? $addresses->first()?->id) == $address->id ? 'checked' : '' }}>
                                            <span class="address-box">
                                                <strong class="address-title">{{ $address->title }}</strong>
                                                <span class="address-detail">{{ $address->city?->title }} @if ($address->district) - {{ $address->district?->title }} @endif - {{ $address->address }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <label class="address-option address-option-new" style="display:block;margin-top:12px">
                                <input type="radio" name="address_id" value="new" id="new-address-radio" {{ old('address_id') === 'new' ? 'checked' : '' }}>
                                <span class="address-box">
                                    <strong class="address-title">@lang('ecommerce::ecommerce.add_new_address')</strong>
                                </span>
                            </label>

                            <div class="new-address-form" id="new-address-form" style="{{ old('address_id') == 'new' || $errors->any() ? '' : 'display: none;' }}">
                                <div class="form-grid">
                                    <div class="form-field form-field-full">
                                        <label>@lang('ecommerce::ecommerce.address_title') *</label>
                                        <input type="text" class="compact-input" name="new_address_title" value="{{ old('new_address_title') }}">
                                        @error('new_address_title')
                                            <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-field">
                                        <label>@lang('ecommerce::ecommerce.country') *</label>
                                        <select class="compact-input" name="new_address_country_id" id="new-address-country">
                                            <option value="">@lang('ecommerce::ecommerce.select_country')</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" data-country-code="{{ strtoupper($country->iso) }}" {{ old('new_address_country_id') == $country->id ? 'selected' : '' }}>{{ $country->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('new_address_country_id')
                                            <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-field">
                                        <label>@lang('ecommerce::ecommerce.city') *</label>
                                        <select class="compact-input" name="new_address_city_id" id="new-address-city">
                                            <option value="">@lang('ecommerce::ecommerce.select_city')</option>
                                            @foreach ($cities as $city)
                                                @if ((string) old('new_address_country_id') === (string) $city->country_id)
                                                    <option value="{{ $city->id }}" {{ old('new_address_city_id') == $city->id ? 'selected' : '' }}>{{ $city->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('new_address_city_id')
                                            <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-field">
                                        <label>@lang('ecommerce::ecommerce.district')</label>
                                        <select class="compact-input" name="new_address_district_id" id="new-address-district">
                                            <option value="">@lang('ecommerce::ecommerce.select_district')</option>
                                        </select>
                                        @error('new_address_district_id')
                                            <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-field form-field-full">
                                        <label>@lang('ecommerce::ecommerce.location_on_map') *</label>
                                        <p class="product-meta">@lang('ecommerce::ecommerce.choose_location_on_map')</p>
                                        <div class="location-coordinates" aria-live="polite">
                                            <span class="location-coordinates-label">@lang('ecommerce::ecommerce.selected_coordinates')</span>
                                            <output class="location-coordinates-value" id="location-coordinates" dir="ltr">@lang('ecommerce::ecommerce.no_location_selected')</output>
                                        </div>
                                        <div id="checkout-location-map" class="checkout-location-map" role="application" aria-label="@lang('ecommerce::ecommerce.location_on_map')"></div>
                                        <div class="location-map-actions">
                                            <button type="button" class="location-map-button" id="use-current-location">@lang('ecommerce::ecommerce.use_current_location')</button>
                                            <p class="location-map-status" id="location-map-status" role="status" aria-live="polite">@lang('ecommerce::ecommerce.location_selection_required')</p>
                                        </div>
                                        <input type="hidden" name="new_address_latitude" id="new-address-latitude" value="{{ old('new_address_latitude') }}">
                                        <input type="hidden" name="new_address_longitude" id="new-address-longitude" value="{{ old('new_address_longitude') }}">
                                        @error('new_address_latitude')
                                            <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                        @error('new_address_longitude')
                                            <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-field form-field-full">
                                        <label>@lang('ecommerce::ecommerce.street_address') *</label>
                                        <textarea class="compact-input" name="new_address_address" rows="2">{{ old('new_address_address') }}</textarea>
                                        @error('new_address_address')
                                            <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @error('address_id')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </section>

                    <section class="checkout-section">
                        <h2 class="section-heading">@lang('ecommerce::ecommerce.order_notes')</h2>
                        <div class="section-content">
                            <textarea class="compact-input" name="note" rows="3" placeholder="@lang('ecommerce::ecommerce.order_notes_placeholder')"></textarea>
                        </div>
                    </section>
                </div>

                <aside class="checkout-sidebar">
                    <div class="sidebar-box">
                        <h4 class="sidebar-title">@lang('ecommerce::ecommerce.order_summary')</h4>

                        <div class="sidebar-group">
                            <label class="sidebar-label">@lang('ecommerce::ecommerce.promo_code')</label>
                            @if ($cart->promocode)
                                <div class="applied-coupon-info">
                                    <div class="coupon-badge"><i class="fa fa-ticket"></i> <span class="coupon-code ltr">{{ $cart->promocode }}</span></div>
                                    <button type="submit" form="remove-coupon-form" class="btn-secondary">@lang('ecommerce::ecommerce.remove')</button>
                                </div>
                            @else
                                <div class="coupon-field">
                                    <input type="text" name="promocode" form="apply-coupon-form" value="{{ old('promocode') }}" placeholder="@lang('ecommerce::ecommerce.inter_promo_code')">
                                    <button type="submit" form="apply-coupon-form" class="btn-apply">@lang('ecommerce::ecommerce.apply')</button>
                                </div>
                            @endif
                            @if(session('coupon_success'))
                                <div class="coupon-message coupon-success" role="status">{{ session('coupon_success') }}</div>
                            @endif
                            @if(session('coupon_error'))
                                <div class="coupon-message alert-danger" role="alert">{{ session('coupon_error') }}</div>
                            @endif
                        </div>

                        <div class="sidebar-group">
                            <div class="summary-row">
                                <span>@lang('ecommerce::ecommerce.subtotal')</span>
                                <strong>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->subtotal ?? 0) }}</strong>
                            </div>
                            @if($cart->promocode && $cart->discount > 0)
                                <div class="summary-row">
                                    <span>@lang('ecommerce::ecommerce.discount') ({{ $cart->promocode }})</span>
                                    <strong>-{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->discount) }}</strong>
                                </div>
                            @endif
                            @if($cart->tax > 0)
                                <div class="summary-row">
                                    <span>@lang('ecommerce::ecommerce.vat') ({{ $checkoutVatLabel }}%)</span>
                                    <strong>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->tax) }}</strong>
                                </div>
                            @endif
                            <div class="summary-row">
                                <span>@lang('ecommerce::ecommerce.delivery_fee')</span>
                                <strong>{{ \App\Domain\AppBase\Models\Setting\Currency::format($checkoutDeliveryFee) }}</strong>
                            </div>
                            <div class="summary-row total">
                                <span>@lang('ecommerce::ecommerce.total')</span>
                                <strong>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->total_after_vat ?? 0) }}</strong>
                            </div>
                        </div>

                        <div class="sidebar-group">
                            <label class="sidebar-label">@lang('ecommerce::ecommerce.payment_method')</label>
                            <!-- Wallet Card -->
                            <div class="payment-method-card wallet-method" style="margin-bottom:0;">
                                <input type="radio" name="payment_method" value="wallet"
                                    {{ old('payment_method', 'wallet') == 'wallet' ? 'checked' : '' }}>
                                <span class="payment-text">@lang('ecommerce::ecommerce.pay_wallet')</span>
                                <small class="d-block" style="color: #28a745; font-weight: bold;">
                                    @lang('ecommerce::ecommerce.balance'): {{ \App\Domain\AppBase\Models\Setting\Currency::format(auth()->user()->balance) }}
                                </small>
                                @error('payment_method')
                                    <div class="wallet-balance-error" role="alert">
                                        <i class="ti-alert" aria-hidden="true"></i>
                                        <div>
                                            <span>{{ $message }}</span>
                                            <a href="{{ route('account.wallet.deposit') }}">@lang('ecommerce::ecommerce.deposit_funds')</a>
                                        </div>
                                    </div>
                                @enderror
                            </div>
                            <!-- COD Card -->
                            <div class="payment-method-card cod-method" style="margin-bottom:0;">
                                <input type="radio" name="payment_method" value="{{ \App\Support\Enum\PaymentMethod::COD->value }}"
                                    {{ old('payment_method', \App\Support\Enum\PaymentMethod::COD->value) === \App\Support\Enum\PaymentMethod::COD->value ? 'checked' : '' }}>
                                <span class="payment-text">@lang('ecommerce::ecommerce.cod')</span>
                            </div>
                        </div>

                        <div class="sidebar-group" style="margin-top:20px;">
                            <button type="submit" class="btn-primary" style="width:100%;">@lang('ecommerce::ecommerce.place_order')</button>
                        </div>
                    </div>
                </aside>
            </div>
        </form>

        <form id="apply-coupon-form" action="{{ route('checkout.apply-coupon') }}" method="POST" style="display:none;">@csrf</form>
        <form id="remove-coupon-form" action="{{ route('checkout.remove-coupon') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ urlencode(config('services.google_maps.api_key')) }}&language={{ app()->getLocale() }}"></script>
<script>
    const citiesByCountry = @json($citiesByCountry);
    const initialCityId = @json(old('new_address_city_id'));

    document.addEventListener('DOMContentLoaded', function () {
        var checkoutMap = null;
        var locationMarker = null;
        var geocodeRequestId = 0;
        var radio = document.getElementById('new-address-radio');
        var form = document.getElementById('new-address-form');
        if (radio && form) {
            var sync = function () {
                form.style.display = radio.checked ? 'block' : 'none';
                if (radio.checked && checkoutMap) {
                    requestAnimationFrame(function () {
                        checkoutMap.invalidateSize();
                    });
                }
            };
            radio.addEventListener('change', sync);
            sync();
        }

        var countrySelect = document.getElementById('new-address-country');
        var citySelect = document.getElementById('new-address-city');
        var districtSelect = document.getElementById('new-address-district');
        var latitudeInput = document.getElementById('new-address-latitude');
        var longitudeInput = document.getElementById('new-address-longitude');
        var mapElement = document.getElementById('checkout-location-map');
        var mapStatus = document.getElementById('location-map-status');
        var coordinatesOutput = document.getElementById('location-coordinates');
        var geocoder = window.google && window.google.maps ? new google.maps.Geocoder() : null;

        function clearMapSelection() {
            latitudeInput.value = '';
            longitudeInput.value = '';
            coordinatesOutput.textContent = @json(__('ecommerce::ecommerce.no_location_selected'));
            if (locationMarker) {
                locationMarker.remove();
                locationMarker = null;
            }
        }

        if (countrySelect && citySelect) {
            var populateCities = function (selectedCityId) {
                var countryCities = citiesByCountry[countrySelect.value] || [];
                citySelect.replaceChildren(new Option(@json(__('ecommerce::ecommerce.select_city')), ''));
                countryCities.forEach(function (city) {
                    citySelect.add(new Option(city.title, city.id));
                });
                citySelect.value = selectedCityId || '';
                citySelect.disabled = countryCities.length === 0;
            };

            populateCities(initialCityId);
        }

        if (!mapElement || !window.L) {
            if (mapStatus) mapStatus.textContent = @json(__('ecommerce::ecommerce.map_load_error'));
            return;
        }

        checkoutMap = L.map(mapElement).setView([20, 0], 2);
        L.tileLayer('https://tile.openstreetmap.de/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(checkoutMap);

        var showGeocodedCenter = function (latitude, longitude, zoom) {
            var position = L.latLng(latitude, longitude);
            checkoutMap.setView(position, zoom);
            if (locationMarker) {
                locationMarker.remove();
            }
            locationMarker = L.marker(position, {draggable: true}).addTo(checkoutMap);
            locationMarker.on('dragend', function (event) {
                var draggedPosition = event.target.getLatLng();
                setLocation(draggedPosition.lat, draggedPosition.lng);
            });
            latitudeInput.value = '';
            longitudeInput.value = '';
            coordinatesOutput.textContent = @json(__('ecommerce::ecommerce.no_location_selected'));
            mapStatus.textContent = @json(__('ecommerce::ecommerce.map_choose_street'));
        };

        var setLocation = function (latitude, longitude) {
            var position = L.latLng(latitude, longitude);
            if (locationMarker) {
                locationMarker.setLatLng(position);
            } else {
                locationMarker = L.marker(position, {draggable: true}).addTo(checkoutMap);
                locationMarker.on('dragend', function (event) {
                    var draggedPosition = event.target.getLatLng();
                    setLocation(draggedPosition.lat, draggedPosition.lng);
                });
            }

            latitudeInput.value = position.lat.toFixed(7);
            longitudeInput.value = position.lng.toFixed(7);
            coordinatesOutput.textContent = position.lat.toFixed(7) + ', ' + position.lng.toFixed(7);
            mapStatus.textContent = @json(__('ecommerce::ecommerce.location_selected')) + ': ' +
                position.lat.toFixed(5) + ', ' + position.lng.toFixed(5);
        };

        var moveMapToSelection = async function (cityId) {
            var requestId = ++geocodeRequestId;

            clearMapSelection();
            if (!countrySelect.value) {
                mapStatus.textContent = @json(__('ecommerce::ecommerce.location_selection_required'));
                checkoutMap.setView([20, 0], 2);
                return;
            }

            mapStatus.textContent = cityId
                ? @json(__('ecommerce::ecommerce.map_finding_city'))
                : @json(__('ecommerce::ecommerce.map_finding_country'));

            var selectedCountry = countrySelect.options[countrySelect.selectedIndex];
            var selectedCity = cityId ? citySelect.options[citySelect.selectedIndex] : null;
            var address = [selectedCity ? selectedCity.text : null, selectedCountry.text]
                .filter(Boolean)
                .join(', ');

            try {
                if (!geocoder) {
                    throw new Error('Google Maps geocoder is unavailable.');
                }

                var result = await geocoder.geocode({
                    address: address,
                    componentRestrictions: {country: selectedCountry.dataset.countryCode}
                });
                if (requestId !== geocodeRequestId) {
                    return;
                }
                if (!result.results.length) {
                    throw new Error('No matching map location was returned.');
                }

                var center = result.results[0].geometry.location;
                showGeocodedCenter(center.lat(), center.lng(), cityId ? 14 : 6);
            } catch (error) {
                if (requestId !== geocodeRequestId) {
                    return;
                }
                console.error('Unable to locate the selected checkout area:', error);
                mapStatus.textContent = @json(__('ecommerce::ecommerce.map_location_unavailable'));
            }
        };

        checkoutMap.on('click', function (event) {
            geocodeRequestId++;
            setLocation(event.latlng.lat, event.latlng.lng);
        });

        if (countrySelect && citySelect) {
            countrySelect.addEventListener('change', function () {
                populateCities('');
                if (districtSelect) {
                    districtSelect.replaceChildren(new Option(@json(__('ecommerce::ecommerce.select_district')), ''));
                }
                moveMapToSelection('');
            });
            citySelect.addEventListener('change', function () {
                if (districtSelect) {
                    districtSelect.replaceChildren(new Option(@json(__('ecommerce::ecommerce.select_district')), ''));
                }
                moveMapToSelection(this.value);
            });
        }

        var savedLatitude = parseFloat(latitudeInput.value);
        var savedLongitude = parseFloat(longitudeInput.value);
        if (Number.isFinite(savedLatitude) && Number.isFinite(savedLongitude)) {
            setLocation(savedLatitude, savedLongitude);
            checkoutMap.setView([savedLatitude, savedLongitude], 15);
        } else if (countrySelect && countrySelect.value) {
            moveMapToSelection(citySelect && citySelect.value ? citySelect.value : '');
        } else {
            mapStatus.textContent = @json(__('ecommerce::ecommerce.location_selection_required'));
        }

        var locateButton = document.getElementById('use-current-location');
        if (locateButton) {
            locateButton.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    mapStatus.textContent = @json(__('ecommerce::ecommerce.location_unavailable'));
                    return;
                }

                locateButton.disabled = true;
                geocodeRequestId++;
                navigator.geolocation.getCurrentPosition(function (position) {
                    setLocation(position.coords.latitude, position.coords.longitude);
                    checkoutMap.setView([position.coords.latitude, position.coords.longitude], 16);
                    locateButton.disabled = false;
                }, function () {
                    mapStatus.textContent = @json(__('ecommerce::ecommerce.location_unavailable'));
                    locateButton.disabled = false;
                }, {enableHighAccuracy: true, timeout: 10000});
            });
        }
    });
</script>
@endsection