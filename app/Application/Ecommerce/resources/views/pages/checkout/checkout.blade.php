@extends('ecommerce::layout.store-layout')

{{-- @section bodies are evaluated BEFORE the parent layout runs, so $isRTL has to be
     resolved here rather than relying on the value store-layout computes. --}}
@php $isRTL = $isRTL ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp

@section('title', __('ecommerce::ecommerce.checkout'))

@section('page-styles')
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
                                        <select class="compact-input" name="new_address_country_id">
                                            <option value="">@lang('ecommerce::ecommerce.select_country')</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('new_address_country_id') == $country->id ? 'selected' : '' }}>{{ $country->title }}</option>
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
                                                <option value="{{ $city->id }}" {{ old('new_address_city_id') == $city->id ? 'selected' : '' }}>{{ $city->title }}</option>
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
                            <label class="payment-option" style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px;border:1px solid #e7ebf0;border-radius:8px;cursor:pointer;">
                                <span><input type="radio" name="payment_method" value="{{ \App\Support\Enum\PaymentMethod::COD->value }}" {{ old('payment_method', \App\Support\Enum\PaymentMethod::COD->value) === \App\Support\Enum\PaymentMethod::COD->value ? 'checked' : '' }}> @lang('ecommerce::ecommerce.cod')</span>
                                <i class="fa fa-truck" aria-hidden="true"></i>
                            </label>
                            @error('payment_method') <span class="error-msg">{{ $message }}</span> @enderror
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var radio = document.getElementById('new-address-radio');
        var form = document.getElementById('new-address-form');
        if (!radio || !form) return;
        var sync = function () {
            form.style.display = radio.checked ? 'block' : 'none';
        };
        radio.addEventListener('change', sync);
        sync();
    });
</script>
@endsection
