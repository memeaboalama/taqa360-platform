@extends('ecommerce::layout.store-layout')

{{-- @section bodies are evaluated BEFORE the parent layout runs, so $isRTL has to be
     resolved here rather than relying on the value store-layout computes. --}}
@php $isRTL = $isRTL ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp

@section('title', $product->title ?? __('ecommerce::ecommerce.product_label'))

@section('page-styles')
<style>
        :root{--bg:#f8f9fb;--ink:#1f2430;--muted:#6b7280;--line:#edf0f3;--yellow:#306FAA;--yellow-deep:#245C8E;--soft:#f6f7f9;--card:#ffffff;--dark:#252b31;--dark-soft:#2e3640}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#f5f5f5;color:var(--ink);font-family:'Cairo',Tahoma,sans-serif}.a{color:inherit;text-decoration:none}button,input{font:inherit}button{border:0;cursor:pointer}.container{width:min(1280px,calc(100% - 36px));margin:0 auto}.topbar{background:#fff;border-bottom:1px solid #f0f1f3}.topbar-inner{height:82px;display:flex;align-items:center;justify-content:space-between;gap:22px}.brand{display:flex;align-items:center}.brand-logo{display:block;width:190px;height:80px;object-fit:contain}.search{flex:1;max-width:560px;height:46px;border:1px solid #eef0f2;border-radius:10px;overflow:hidden;display:flex;direction:{{ $isRTL ? 'rtl' : 'ltr' }};background:#fff}.search input{flex:1;border:0;outline:none;padding:0 16px;background:transparent;color:var(--ink)}.search button{width:54px;border:0;background:var(--yellow);color:#ffffff;font-size:20px}.top-actions{display:flex;align-items:center;gap:14px;direction:ltr}.service{display:inline-flex;align-items:center;justify-content:center;height:35px;padding:0 13px;border-radius:5px;background:var(--yellow);color:#ffffff;font-size:11px;font-weight:700;gap:6px}.action{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;background:#f7f9fb;color:#525b66;text-decoration:none;font-size:18px}.nav{background:#fff;border-bottom:1px solid #f1f2f4}.nav ul{list-style:none;display:flex;justify-content:center;align-items:center;gap:38px;margin:0;padding:12px 0}.nav a{font-size:12px;color:#3a4047;text-decoration:none;font-weight:600}.nav a.active{color:var(--yellow-deep)}.page-shell{padding:24px 0 40px}.product-layout{display:grid;grid-template-columns:1.15fr .95fr;gap:22px}.gallery-card,.details-card,.related-card{background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:0 8px 20px rgba(17,24,39,.04)}.gallery-card{padding:18px}.main-image-wrap{background:#fafafa;border:1px solid #eef1f4;border-radius:14px;padding:12px;display:flex;align-items:center;justify-content:center;min-height:520px}.main-image{max-width:100%;max-height:520px;object-fit:contain}.thumbs{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:14px}.thumb{border:1px solid var(--line);background:#fff;border-radius:10px;padding:8px;cursor:pointer}.thumb.active{border-color:var(--yellow);box-shadow:0 0 0 2px rgba(48,111,170,.18)}.thumb img{display:block;width:100%;height:86px;object-fit:cover;border-radius:8px}.details-card{padding:28px 24px}.details-header{padding-bottom:12px;border-bottom:1px solid var(--line)}.product-name{margin:0;font-size:40px;line-height:1.4;font-weight:800}.badge-row{display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin-top:12px}.badge{display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:#eef4fa;color:#245C8E;font-size:11px;font-weight:700}.price-row{display:flex;align-items:end;gap:14px;flex-wrap:wrap;margin-top:18px}.price{font-size:30px;font-weight:800;color:var(--ink)}.old-price{font-size:18px;color:#9aa3ac;text-decoration:line-through}.saving{padding:6px 10px;border-radius:999px;background:#e8fff3;color:#1e8e5b;font-size:12px;font-weight:700}.rating-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:15px;color:#69717d}.stars{font-size:18px;letter-spacing:2px;color:#EA993E}.meta-list{margin:18px 0 0;padding:0;list-style:none;display:grid;gap:12px}.meta-list li{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;border:1px solid var(--line);border-radius:10px;background:#fafbfd;color:#4a5360;font-size:13px}.meta-list strong{font-size:14px;color:var(--ink)}.action-row{display:flex;flex-wrap:wrap;gap:12px;margin-top:24px}
.wishlist-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:52px;padding:0 20px;border-radius:12px;border:1px solid var(--line);background:#fff;color:#5d6269;cursor:pointer;font-weight:700;font-size:13px}
.wishlist-btn:hover{border-color:var(--yellow);color:var(--gold)}
.wishlist-btn.is-favorite{color:#EA993E;border-color:rgba(234,153,62,.45);background:#eef4fa}
.wishlist-btn[disabled]{opacity:.6;cursor:not-allowed}.primary-btn,.secondary-btn{display:inline-flex;align-items:center;justify-content:center;height:52px;padding:0 22px;border-radius:12px;border:0;cursor:pointer;text-decoration:none;font-weight:800}.primary-btn{background:var(--yellow);color:#ffffff}.secondary-btn{background:#fff;border:1px solid var(--line);color:var(--ink)}.qty-box{display:flex;align-items:center;gap:10px;margin-top:18px}.qty-box label{font-weight:700;color:#4b5563}.qty-controls{display:inline-flex;align-items:center;border:1px solid var(--line);border-radius:10px;overflow:hidden;background:#fff}.qty-controls button{width:42px;height:42px;border:0;background:transparent;color:#45515d;font-size:18px}.qty-controls input{width:54px;height:42px;border:0;text-align:center;outline:none;font-size:16px;font-weight:700}.desc-box{margin-top:26px;padding-top:18px;border-top:1px solid var(--line)}.desc-box h3{margin:0 0 10px;font-size:18px} .desc-box p{margin:0;color:#58616c;line-height:2;font-size:14px}.info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:20px}.info-card{padding:16px;border:1px solid var(--line);border-radius:12px;background:#fafbfd}.info-card h4{margin:0 0 10px;font-size:14px}.info-card p{margin:0;color:#59636d;line-height:1.9;font-size:13px}.section-head{display:flex;justify-content:space-between;align-items:center;margin:30px 0 14px}.section-head h3{margin:0;font-size:22px}.section-head a{color:var(--yellow-deep);text-decoration:none;font-size:12px;font-weight:700}.products-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.product-box{padding:14px;border:1px solid var(--line);border-radius:12px;background:#fff}.product-box .img{display:block;height:188px;overflow:hidden;border-radius:10px;background:#f7f7f8}.product-box img{width:100%;height:100%;object-fit:cover;display:block}.product-box h4{margin:10px 0 8px;font-size:15px;line-height:1.7}.product-box .price-mini{font-weight:800}.related-actions{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-top:10px}.cart-mini{display:inline-flex;align-items:center;justify-content:center;height:36px;padding:0 14px;border-radius:9px;background:var(--yellow);color:#ffffff;font-weight:700;text-decoration:none}.favorites-mini{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:#f7f9fb;color:#4d5965;text-decoration:none}.specs{margin-top:14px} .specs table{width:100%;border-collapse:collapse;background:#fff}.specs th,.specs td{padding:12px 10px;border-bottom:1px solid var(--line);text-align:start;font-size:13px}.specs th{color:#49515d;background:#fafbfd}.specs td{color:#2d3139}.footer{margin-top:40px;background:var(--dark);padding:24px 0 18px;color:#fff}.footer-top{display:flex;justify-content:center}.footer-brand{text-align:center}.footer-logo{display:block;width:170px;margin:0 auto 10px;object-fit:contain}.footer-text{margin:0;color:#dfe6ee;line-height:1.8}.footer-bottom{display:flex;justify-content:space-between;align-items:center;gap:20px;border-top:1px solid rgba(255,255,255,.14);margin-top:18px;padding-top:14px;flex-wrap:wrap}.footer-links,.socials{display:flex;align-items:center;gap:16px;flex-wrap:wrap}.footer-links a,.socials a{color:#dfe6ee;text-decoration:none;font-size:11px}.socials a{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.06)}.copyright{font-size:11px;color:#c9ced6}@media (max-width: 980px){.product-layout{grid-template-columns:1fr}.product-name{font-size:30px}.products-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (max-width: 640px){.topbar-inner{height:auto;padding:12px 0;flex-wrap:wrap}.search{order:3;max-width:none;flex-basis:100%}.nav ul{justify-content:flex-start;gap:20px;overflow:auto;padding:12px 12px}.products-grid{grid-template-columns:1fr}.main-image-wrap{min-height:300px}.action-row{flex-direction:column;align-items:stretch}.primary-btn,.secondary-btn{width:100%}.info-grid{grid-template-columns:1fr}.footer-bottom{justify-content:center;text-align:center}}</style>
@endsection

@section('content')
<div class="page-content container page-shell">
        <div class="product-layout">
            <section class="gallery-card">
                <div class="main-image-wrap">
                    <img class="main-image" src="{{ $product->image_path ?? asset('assets/ecommerce/images/default-product.png') }}" alt="{{ $product->title }}">
                </div>
                <div class="thumbs">
                    @php $images = $product->images ?? []; @endphp
                    @if(!empty($images))
                        @foreach(array_slice($images, 0, 4) as $img)
                            <div class="thumb active"><img src="{{ $img['image_path'] ?? $product->image_path ?? asset('assets/ecommerce/images/default-product.png') }}" alt="{{ $product->title }}"></div>
                        @endforeach
                    @else
                        @for($i=1; $i<=4; $i++)
                            <div class="thumb {{ $i == 1 ? 'active' : '' }}"><img src="{{ asset('assets/ecommerce/images/default-product.png') }}" alt="{{ $product->title }}"></div>
                        @endfor
                    @endif
                </div>
            </section>

            <section class="details-card">
                <div class="details-header">
                    <h1 class="product-name">{{ $product->title }}</h1>
                    <div class="badge-row">
                        <span class="badge"><i class="fa fa-check-circle"></i> @lang('ecommerce::ecommerce.trusted_product')</span>
                        <span class="badge"><i class="fa fa-truck"></i> @lang('ecommerce::ecommerce.fast_shipping')</span>
                    </div>
                </div>

                <div class="price-row">
                    @if(!empty($product->default_price))
                        <div class="price">{{ \App\Domain\AppBase\Models\Setting\Currency::format((float)$product->default_price) }}</div>
                    @endif
                    @if(!empty($product->sale_price) && $product->sale_price < $product->default_price)
                        <div class="old-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format((float)$product->sale_price) }}</div>
                    @endif
                    @if(!empty($product->sale_price) && $product->default_price && $product->sale_price < $product->default_price)
                        @php $discount = round((($product->default_price - $product->sale_price) / $product->default_price) * 100); @endphp
                        <span class="saving">@lang('ecommerce::ecommerce.discount_label') {{ $discount }}%</span>
                    @endif
                </div>

                <div class="rating-row">
                    <div class="stars">★★★★★</div>
                    <span>4.8 @lang('ecommerce::ecommerce.out_of_5')</span>
                    <span>•</span>
                    <span>{{ $product->reviews_count ?? 0 }} @lang('ecommerce::ecommerce.reviews')</span>
                </div>

                <ul class="meta-list">
                    <li><span>@lang('ecommerce::ecommerce.color')</span><strong>{{ $product->color ?? __('ecommerce::ecommerce.color_black') }}</strong></li>
                    <li><span>@lang('ecommerce::ecommerce.brand')</span><strong>{{ $product->merchant->name ?? __('ecommerce::ecommerce.site_name') }}</strong></li>
                    <li><span>@lang('ecommerce::ecommerce.category')</span><strong>{{ $product->mainCategory->title ?? __('ecommerce::ecommerce.generic') }}</strong></li>
                    <li><span>@lang('ecommerce::ecommerce.stock')</span><strong>@if($product->stock === null) @lang('ecommerce::ecommerce.stock_not_tracked') @else {{ (int) $product->stock }} @lang('ecommerce::ecommerce.items') @endif</strong></li>
                </ul>

                <form id="product-add-cart-form" action="{{ route('cart.add') }}" method="POST" data-available="{{ $product->isInStock() ? '1' : '0' }}">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $product->id }}">
                    <div class="qty-box">
                        <label>@lang('ecommerce::ecommerce.quantity')</label>
                        <div class="qty-controls">
                            <button type="button" id="qty-minus" aria-label="@lang('ecommerce::ecommerce.decrease')">−</button>
                            <input type="number" dir="ltr" name="qty" id="qty-input" value="1" min="1" @if($product->stock !== null) max="{{ (int) $product->stock }}" @endif>
                            <button type="button" id="qty-plus" aria-label="@lang('ecommerce::ecommerce.increase')">+</button>
                        </div>
                    </div>

                    <div class="action-row">
<button type="submit" class="primary-btn" id="add-to-cart-btn"><i class="ti-shopping-cart" style="margin-left:8px"></i> @lang('ecommerce::ecommerce.add_to_cart')</button>
<button type="submit" name="redirect_to_checkout" value="1" class="secondary-btn">@lang('ecommerce::ecommerce.buy_now')</button>
<button type="button" class="wishlist-btn{{ $isFavorite ? ' is-favorite' : '' }}" id="product-wishlist-btn" data-product-id="{{ $product->id }}" aria-pressed="{{ $isFavorite ? 'true' : 'false' }}" aria-label="@lang('ecommerce::ecommerce.wishlist')">
<i class="{{ $isFavorite ? 'fa fa-heart' : 'ti-heart' }}" aria-hidden="true"></i>
<span class="wishlist-btn-text">@lang($isFavorite ? 'ecommerce::ecommerce.remove_from_wishlist' : 'ecommerce::ecommerce.add_to_wishlist')</span>
</button>
                    </div>
                </form>

                <div class="desc-box">
                    <h3>@lang('ecommerce::ecommerce.description')</h3>
                    <p>{{ $product->description ?? __('ecommerce::ecommerce.no_detailed_description') }}</p>

                    @if($product->features)
                        <h3 style="margin-top:20px;">@lang('ecommerce::ecommerce.key_features')</h3>
                        <ul style="padding-right:18px; color:#58616c; line-height:2; margin:10px 0 0;">
                            @foreach(explode(',', $product->features) as $feature)
                                <li>{{ trim($feature) }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if($product->benefits)
                        <h3 style="margin-top:20px;">@lang('ecommerce::ecommerce.benefits')</h3>
                        <p>{{ $product->benefits }}</p>
                    @endif
                </div>
            </section>
        </div>

        <section class="specs">
            <div class="section-head">
                <h3>@lang('ecommerce::ecommerce.product_details')</h3>
            </div>
            <table>
                <tbody>
                    <tr><th>@lang('ecommerce::ecommerce.store_name')</th><td>{{ $product->merchant->name ?? __('ecommerce::ecommerce.site_name') }}</td></tr>
                    <tr><th>@lang('ecommerce::ecommerce.category')</th><td>{{ $product->mainCategory->title ?? __('ecommerce::ecommerce.generic') }}</td></tr>
                    <tr><th>@lang('ecommerce::ecommerce.stock')</th><td>@if($product->stock === null) @lang('ecommerce::ecommerce.stock_not_tracked') @else {{ (int) $product->stock }} @lang('ecommerce::ecommerce.items') @endif</td></tr>
                </tbody>
            </table>
        </section>

        <section style="margin-top:32px;">
            <div class="section-head">
                <h3>@lang('ecommerce::ecommerce.available_services')</h3>
            </div>
            <div class="info-grid">
                <div class="info-card">
                    <h4>@lang('ecommerce::ecommerce.solar_panel_installation')</h4>
                    <p>@lang('ecommerce::ecommerce.solar_panel_desc')</p>
                </div>
                <div class="info-card">
                    <h4>@lang('ecommerce::ecommerce.periodic_maintenance')</h4>
                    <p>@lang('ecommerce::ecommerce.periodic_maintenance_desc')</p>
                </div>
                <div class="info-card">
                    <h4>@lang('ecommerce::ecommerce.system_consultation')</h4>
                    <p>@lang('ecommerce::ecommerce.system_consultation_desc')</p>
                </div>
                <div class="info-card">
                    <h4>@lang('ecommerce::ecommerce.extended_warranty')</h4>
                    <p>@lang('ecommerce::ecommerce.extended_warranty_desc')</p>
                </div>
            </div>
        </section>

        @if($relatedProducts && $relatedProducts->count())
            @php
                $relatedFavoriteIds = auth()->check() ? \App\Domain\MarketPlace\Models\Favorite::where('user_id', auth()->id())
                    ->whereIn('item_id', $relatedProducts->pluck('id'))
                    ->pluck('item_id')
                    ->flip()
                    ->all() : [];
            @endphp
            <section>
                <div class="section-head">
                    <h3>@lang('ecommerce::ecommerce.similar_products')</h3>
                    <a href="{{ route('categories.index') }}">@lang('ecommerce::ecommerce.view_all')</a>
                </div>
                <div class="products-grid">
                    @foreach($relatedProducts as $item)
                        @php $isRelatedFavorite = isset($relatedFavoriteIds[$item->id]); @endphp
                        <article class="product-box">
                            <a class="img" href="{{ route('product.static', $item->id) }}"><img src="{{ $item->image_path ?? asset('assets/ecommerce/images/default-product.png') }}" alt="{{ $item->title }}"></a>
                            <h4><a href="{{ route('product.static', $item->id) }}" style="color:inherit;text-decoration:none">{{ $item->title }}</a></h4>
                            <div class="related-actions">
                                <div class="price-mini">{{ \App\Domain\AppBase\Models\Setting\Currency::format((float)($item->default_price ?? 0)) }}</div>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <button type="button" class="favorites-mini wishlist-btn{{ $isRelatedFavorite ? ' is-favorite' : '' }}" data-product-id="{{ $item->id }}" aria-label="@lang('ecommerce::ecommerce.wishlist')"><i class="{{ $isRelatedFavorite ? 'fa fa-heart' : 'ti-heart' }}"></i></button>
                                    <form action="{{ route('cart.add') }}" method="POST" class="add-cart-mini-form" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="cart-mini" style="border:0;cursor:pointer;">@lang('ecommerce::ecommerce.add_short')</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
</div>
@endsection

@section('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            var minusBtn = document.getElementById('qty-minus');
            var plusBtn = document.getElementById('qty-plus');
            var qtyInput = document.getElementById('qty-input');

            if (minusBtn && qtyInput) {
                minusBtn.addEventListener('click', function() {
                    var val = parseInt(qtyInput.value) || 1;
                    if (val > 1) qtyInput.value = val - 1;
                });
            }
            if (plusBtn && qtyInput) {
                plusBtn.addEventListener('click', function() {
                    var val = parseInt(qtyInput.value) || 1;
                    var max = parseInt(qtyInput.getAttribute('max') || '0', 10);
                    if (max > 0 && val + 1 > max) {
                        qtyInput.value = max;
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(@json(__('ecommerce::ecommerce.remaining_stock', ['count' => (int) $product->stock])), 'danger');
                        }
                        return;
                    }
                    qtyInput.value = val + 1;
                });
            }

            var mainForm = document.getElementById('product-add-cart-form');
            if (mainForm) {
                mainForm.addEventListener('submit', async function(e) {
                    var submitter = e.submitter;
                    if (submitter && submitter.name === 'redirect_to_checkout') {
                        // Let the form submit normally to redirect to checkout
                        return;
                    }
                    e.preventDefault();
                    var addBtn = document.getElementById('add-to-cart-btn');
                    var origHtml = addBtn ? addBtn.innerHTML : '';
                    if (addBtn) {
                        addBtn.disabled = true;
                        addBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> ' + @json(__('ecommerce::ecommerce.product.adding'));
                    }
                    try {
                        var res = await fetch(mainForm.action, {
                            method: 'POST',
                            body: new FormData(mainForm),
                            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (res.status === 401) {
                            window.location.href = '{{ route('auth.login') }}';
                            return;
                        }
                        var data = await res.json();
                        if (data.success) {
                            if (addBtn) {
                                addBtn.innerHTML = '✓ ' + @json(__('ecommerce::ecommerce.added_successfully'));
                                setTimeout(function() {
                                    addBtn.disabled = false;
                                    addBtn.innerHTML = origHtml;
                                }, 2500);
                            }
                        } else {
                            var conflictMsg = data.message || @json(__('ecommerce::ecommerce.cart.add_error'));
                            if (window.showStaticConflictModal && typeof window.showStaticConflictModal === 'function') {
                                window.showStaticConflictModal(conflictMsg).catch(function () {});
                            } else {
                                alert(conflictMsg);
                            }
                            if (addBtn) { addBtn.disabled = false; addBtn.innerHTML = origHtml; }
                        }
                    } catch(err) {
                        if (addBtn) { addBtn.disabled = false; addBtn.innerHTML = origHtml; }
                        mainForm.submit();
                    }
                });
            }

            document.querySelectorAll('.add-cart-mini-form').forEach(function(form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    var btn = form.querySelector('button');
                    var origText = btn ? btn.textContent : '';
                    if (btn) btn.textContent = '...';
                    try {
                        var res = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (res.status === 401) {
                            window.location.href = '{{ route('auth.login') }}';
                            return;
                        }
                        var data = await res.json();
                        if (data.success && btn) {
                            btn.textContent = '✓';
                            setTimeout(function() { btn.textContent = origText; }, 2000);
                        } else if (btn) {
                            btn.textContent = origText;
                        }
                    } catch(err) {
                        form.submit();
                    }
                });
});
});
</script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('product-add-cart-form');
        if (!form) return;
        var available = form.getAttribute('data-available') === '1';
        var badge = document.createElement('div');
        badge.className = 'hululi-stock' + (available ? '' : ' out');
        badge.style.margin = '0 0 12px';
        badge.textContent = available ? '@lang('ecommerce::ecommerce.in_stock')' : '@lang('ecommerce::ecommerce.out_of_stock')';
        form.parentNode.insertBefore(badge, form);
        if (!available) {
            var submitBtns = form.querySelectorAll('button[type="submit"]');
            for (var i = 0; i < submitBtns.length; i++) {
                submitBtns[i].disabled = true;
                submitBtns[i].style.background = '#d3d6db';
                submitBtns[i].style.borderColor = '#d3d6db';
                submitBtns[i].style.color = '#8a9198';
                submitBtns[i].style.cursor = 'not-allowed';
            }
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('product-wishlist-btn');
    if (!btn) return;
    var productId = btn.getAttribute('data-product-id');
    var textEl = btn.querySelector('.wishlist-btn-text');
    var addLabel = @json(__('ecommerce::ecommerce.add_to_wishlist'));
    var removeLabel = @json(__('ecommerce::ecommerce.remove_from_wishlist'));
    btn.addEventListener('click', async function () {
        if (btn.dataset.loading === '1') return;
        btn.dataset.loading = '1';
        btn.disabled = true;
        try {
            var body = new FormData();
            body.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            body.append('product_id', productId);
            var response = await fetch(@json(route('wishlist.add')), {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: body
            });
            var data = await response.json().catch(function () { return null; });
            if ((!data && response.url.includes('/login')) || (data && data.login)) {
                window.location.href = data && data.redirect ? data.redirect : response.url;
                return;
            }
            var isFavorite = data && data.status === 'added';
            btn.classList.toggle('is-favorite', isFavorite);
            btn.setAttribute('aria-pressed', isFavorite ? 'true' : 'false');
            btn.innerHTML = '<i class="' + (isFavorite ? 'fa fa-heart' : 'ti-heart') + '" aria-hidden="true"></i>' +
                '<span class="wishlist-btn-text">' + (isFavorite ? removeLabel : addLabel) + '</span>';
            if (typeof window.updateWishlistBadge === 'function' && data && typeof data.count !== 'undefined') {
                window.updateWishlistBadge(data.count);
            }
        } catch (err) {
            if (typeof window.showNotification === 'function') {
                window.showNotification(@json(__('ecommerce::ecommerce.try_again_error')), 'danger');
            }
        } finally {
            delete btn.dataset.loading;
            btn.disabled = false;
        }
    });
});
</script>
<script>
document.addEventListener('click', async function (event) {
    var favorite = event.target.closest('.favorites-mini.wishlist-btn');
    if (!favorite) return;
    event.preventDefault();
    event.stopPropagation();
    var productId = favorite.getAttribute('data-product-id');
    var token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!productId || !token || favorite.dataset.loading === '1') return;
    favorite.dataset.loading = '1';
    favorite.disabled = true;
    try {
        var body = new FormData();
        body.append('_token', token);
        body.append('product_id', productId);
        var response = await fetch('{{ route('wishlist.add') }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: body
        });
        var data = await response.json().catch(function () { return null; });
        if ((!data && response.url.includes('/login')) || (data && data.login)) {
            window.location.href = data && data.redirect ? data.redirect : response.url;
            return;
        }
        var isFavorite = data && data.status === 'added';
        favorite.classList.toggle('is-favorite', isFavorite);
        favorite.innerHTML = '<i class="' + (isFavorite ? 'fa fa-heart' : 'ti-heart') + '" aria-hidden="true"></i>';
        if (typeof window.updateWishlistBadge === 'function' && data && typeof data.count !== 'undefined') {
            window.updateWishlistBadge(data.count);
        }
    } catch (err) {
        if (typeof window.showNotification === 'function') {
            window.showNotification(@json(__('ecommerce::ecommerce.try_again_error')), 'danger');
        }
    } finally {
        delete favorite.dataset.loading;
        favorite.disabled = false;
    }
}, true);
</script>
@endsection
