@extends('ecommerce::layout.store-layout')

{{-- @section bodies are evaluated BEFORE the parent layout runs, so $isRTL has to be
     resolved here rather than relying on the value store-layout computes. --}}
@php $isRTL = $isRTL ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp

@section('title', $product->title ?? __('ecommerce::ecommerce.product_label'))

@section('page-styles')
<style>
:root{--bg:#f8f9fb;--ink:#1f2430;--muted:#6b7280;--line:#edf0f3;--yellow:{{ $platformBrand['primary_color'] }};--yellow-deep:{{ $platformBrand['primary_hover_color'] }};--soft:#f6f7f9;--card:#ffffff;--dark:#252b31;--dark-soft:#2e3640}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#f5f5f5;color:var(--ink);font-family:'Cairo',Tahoma,sans-serif}.a{color:inherit;text-decoration:none}button,input{font:inherit}button{border:0;cursor:pointer}.container{width:min(1280px,calc(100% - 36px));margin:0 auto}.topbar{background:#fff;border-bottom:1px solid #f0f1f3}.topbar-inner{height:82px;display:flex;align-items:center;justify-content:space-between;gap:22px}.brand{display:flex;align-items:center}.brand-logo{display:block;width:190px;height:80px;object-fit:contain}.search{flex:1;max-width:560px;height:46px;border:1px solid #eef0f2;border-radius:10px;overflow:hidden;display:flex;direction:{{ $isRTL ? 'rtl' : 'ltr' }};background:#fff}.search input{flex:1;border:0;outline:none;padding:0 16px;background:transparent;color:var(--ink)}.search button{width:54px;border:0;background:var(--yellow);color:#ffffff;font-size:20px}.top-actions{display:flex;align-items:center;gap:14px;direction:ltr}.service{display:inline-flex;align-items:center;justify-content:center;height:35px;padding:0 13px;border-radius:5px;background:var(--yellow);color:#ffffff;font-size:11px;font-weight:700;gap:6px}.action{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;background:#f7f9fb;color:#525b66;text-decoration:none;font-size:18px}.nav{background:#fff;border-bottom:1px solid #f1f2f4}.nav ul{list-style:none;display:flex;justify-content:center;align-items:center;gap:38px;margin:0;padding:12px 0}.nav a{font-size:12px;color:#3a4047;text-decoration:none;font-weight:600}.nav a.active{color:var(--yellow-deep)}.page-shell{padding:24px 0 40px}.product-layout{display:grid;grid-template-columns:1.15fr .95fr;gap:22px}.gallery-card,.details-card,.related-card{background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:0 8px 20px rgba(17,24,39,.04)}.gallery-card{padding:18px}.main-image-wrap{background:#fafafa;border:1px solid #eef1f4;border-radius:14px;padding:12px;display:flex;align-items:center;justify-content:center;min-height:520px}.main-image{max-width:100%;max-height:520px;object-fit:contain}.thumbs{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:14px}.thumb{border:1px solid var(--line);background:#fff;border-radius:10px;padding:8px;cursor:pointer}.thumb.active{border-color:var(--yellow);box-shadow:0 0 0 2px rgba(48,111,170,.18)}.thumb img{display:block;width:100%;height:86px;object-fit:cover;border-radius:8px}.details-card{padding:28px 24px}.details-header{padding-bottom:12px;border-bottom:1px solid var(--line)}.product-name{margin:0;font-size:40px;line-height:1.4;font-weight:800}.badge-row{display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin-top:12px}.badge{display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:#eef4fa;color:#245C8E;font-size:11px;font-weight:700}.price-row{display:flex;align-items:end;gap:14px;flex-wrap:wrap;margin-top:18px}.price{font-size:30px;font-weight:800;color:var(--ink)}.old-price{font-size:18px;color:#9aa3ac;text-decoration:line-through}.saving{padding:6px 10px;border-radius:999px;background:#e8fff3;color:#1e8e5b;font-size:12px;font-weight:700}.rating-row{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:15px;color:#69717d}.stars{font-size:18px;letter-spacing:2px;color:#EA993E}.meta-list{margin:18px 0 0;padding:0;list-style:none;display:grid;gap:12px}.meta-list li{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;border:1px solid var(--line);border-radius:10px;background:#fafbfd;color:#4a5360;font-size:13px}.meta-list strong{font-size:14px;color:var(--ink)}.action-row{display:flex;flex-wrap:wrap;gap:12px;margin-top:24px}
.wishlist-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:52px;padding:0 20px;border-radius:12px;border:1px solid var(--line);background:#fff;color:#5d6269;cursor:pointer;font-weight:700;font-size:13px}
.wishlist-btn:hover{border-color:var(--yellow);color:var(--gold)}
.wishlist-btn.is-favorite{color:#EA993E;border-color:rgba(234,153,62,.45);background:#eef4fa}
.wishlist-btn[disabled]{opacity:.6;cursor:not-allowed}.primary-btn,.secondary-btn{display:inline-flex;align-items:center;justify-content:center;height:52px;padding:0 22px;border-radius:12px;border:0;cursor:pointer;text-decoration:none;font-weight:800}.primary-btn{background:var(--yellow);color:#ffffff}.secondary-btn{background:#fff;border:1px solid var(--line);color:var(--ink)}.qty-box{display:flex;align-items:center;gap:10px;margin-top:18px}.qty-box label{font-weight:700;color:#4b5563}.qty-controls{display:inline-flex;align-items:center;border:1px solid var(--line);border-radius:10px;overflow:hidden;background:#fff}.qty-controls button{width:42px;height:42px;border:0;background:transparent;color:#45515d;font-size:18px}.qty-controls input{width:54px;height:42px;border:0;text-align:center;outline:none;font-size:16px;font-weight:700}.desc-box{margin-top:26px;padding-top:18px;border-top:1px solid var(--line)}.desc-box h3{margin:0 0 10px;font-size:18px} .desc-box p{margin:0;color:#58616c;line-height:2;font-size:14px}.info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:20px}.info-card{padding:16px;border:1px solid var(--line);border-radius:12px;background:#fafbfd}.info-card h4{margin:0 0 10px;font-size:14px}.info-card p{margin:0;color:#59636d;line-height:1.9;font-size:13px}.section-head{display:flex;justify-content:space-between;align-items:center;margin:30px 0 14px}.section-head h3{margin:0;font-size:22px}.section-head a{color:var(--yellow-deep);text-decoration:none;font-size:12px;font-weight:700}.products-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.product-box{padding:14px;border:1px solid var(--line);border-radius:12px;background:#fff}.product-box .img{display:block;height:188px;overflow:hidden;border-radius:10px;background:#f7f7f8}.product-box img{width:100%;height:100%;object-fit:cover;display:block}.product-box h4{margin:10px 0 8px;font-size:15px;line-height:1.7}.product-box .price-mini{font-weight:800}.related-actions{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-top:10px}.cart-mini{display:inline-flex;align-items:center;justify-content:center;height:36px;padding:0 14px;border-radius:9px;background:var(--yellow);color:#ffffff;font-weight:700;text-decoration:none}.favorites-mini{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:#f7f9fb;color:#4d5965;text-decoration:none}.specs{margin-top:14px} .specs table{width:100%;border-collapse:collapse;background:#fff}.specs th,.specs td{padding:12px 10px;border-bottom:1px solid var(--line);text-align:start;font-size:13px}.specs th{color:#49515d;background:#fafbfd}.specs td{color:#2d3139}.footer{margin-top:40px;background:var(--dark);padding:24px 0 18px;color:#fff}.footer-top{display:flex;justify-content:center}.footer-brand{text-align:center}.footer-logo{display:block;width:170px;margin:0 auto 10px;object-fit:contain}.footer-text{margin:0;color:#dfe6ee;line-height:1.8}.footer-bottom{display:flex;justify-content:space-between;align-items:center;gap:20px;border-top:1px solid rgba(255,255,255,.14);margin-top:18px;padding-top:14px;flex-wrap:wrap}.footer-links,.socials{display:flex;align-items:center;gap:16px;flex-wrap:wrap}.footer-links a,.socials a{color:#dfe6ee;text-decoration:none;font-size:11px}.socials a{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.06)}.copyright{font-size:11px;color:#c9ced6}@media (max-width: 980px){.product-layout{grid-template-columns:1fr}.product-name{font-size:30px}.products-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (max-width: 640px){.topbar-inner{height:auto;padding:12px 0;flex-wrap:wrap}.search{order:3;max-width:none;flex-basis:100%}.nav ul{justify-content:flex-start;gap:20px;overflow:auto;padding:12px 12px}.products-grid{grid-template-columns:1fr}.main-image-wrap{min-height:300px}.action-row{flex-direction:column;align-items:stretch}.primary-btn,.secondary-btn{width:100%}.info-grid{grid-template-columns:1fr}.footer-bottom{justify-content:center;text-align:center}}</style>
<link rel="stylesheet" href="{{ asset('assets/ecommerce/css/product-page.css') }}">
@endsection

@section('content')
<div class="page-content container page-shell product-page-refresh">
    @include('ecommerce::pages.product.partials.overview')

        <section>
            <div class="section-head">
                <h3>@lang('ecommerce::ecommerce.reviews') ({{ $product->reviews_count }})</h3>
            </div>

            @if(session('product_review_submitted'))
                <p class="alert alert-success" role="status">@lang('ecommerce::ecommerce.product_review_submitted')</p>
            @endif
            @if($errors->has('purchase'))
                <p class="alert alert-danger" role="alert">{{ $errors->first('purchase') }}</p>
            @endif

            @forelse($product->reviews as $review)
                <article class="info-card mb-3">
                    <div class="d-flex justify-content-between gap-3">
                        <strong>{{ $review->user?->name }}</strong>
                        <span>{{ $review->rating }} / 5</span>
                    </div>
                    <h4 class="mt-2">{{ $review->title }}</h4>
                    <p>{{ $review->comment }}</p>
                </article>
            @empty
                <p class="text-muted">@lang('ecommerce::ecommerce.no_reviews_yet')</p>
            @endforelse

            @if($canReview)
                <form method="POST" action="{{ route('products.reviews.store', $product->id) }}" class="info-card mt-4">
                    @csrf
                    <h4>@lang('ecommerce::ecommerce.add_your_review')</h4>
                    @if($errors->has('rating') || $errors->has('title') || $errors->has('comment') || $errors->has('purchase'))
                        <div class="alert alert-danger" role="alert">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <label for="review-rating" class="form-label">@lang('ecommerce::ecommerce.rating')</label>
                    <select id="review-rating" name="rating" class="form-control mb-3" required>
                        <option value="">@lang('ecommerce::ecommerce.select_rating')</option>
                        @for($rating = 5; $rating >= 1; $rating--)
                            <option value="{{ $rating }}" @selected(old('rating') == $rating)>{{ $rating }} / 5</option>
                        @endfor
                    </select>
                    <label for="review-title" class="form-label">@lang('ecommerce::ecommerce.review_title')</label>
                    <input id="review-title" name="title" type="text" class="form-control mb-3" maxlength="120" value="{{ old('title') }}" required>
                    <label for="review-comment" class="form-label">@lang('ecommerce::ecommerce.your_review')</label>
                    <textarea id="review-comment" name="comment" class="form-control mb-3" rows="4" maxlength="2000" required>{{ old('comment') }}</textarea>
                    <button type="submit" class="primary-btn">@lang('ecommerce::ecommerce.submit_review')</button>
                </form>
            @elseif($existingReview)
                <p class="text-muted" role="status">
                    @lang($existingReview->status === 'pending'
                        ? 'ecommerce::ecommerce.product_review_pending'
                        : 'ecommerce::ecommerce.product_review_already_submitted')
                </p>
            @elseif(!auth()->check())
                <p class="text-muted">
                    @lang('ecommerce::ecommerce.product_review_login_required')
                    <a href="{{ route('auth.login') }}">@lang('ecommerce::ecommerce.login')</a>
                </p>
            @elseif(!$hasCompletedPurchase)
                <p class="text-muted">@lang('ecommerce::ecommerce.product_review_purchase_required')</p>
            @endif
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
                            @include('ecommerce::partials.product.rating', ['product' => $item])
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
<script src="{{ asset('assets/ecommerce/js/product-page.js') }}" defer></script>
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

            if (typeof window.submitCartForm !== 'function') {
                if (addBtn) {
                    addBtn.disabled = false;
                    addBtn.innerHTML = origHtml;
                }

                window.alert(@json(__('ecommerce::ecommerce.try_again_error')));
                return;
            }

            window.submitCartForm(mainForm).then(function(data) {
                if (data && data.cancelled) {
                    if (addBtn) {
                        addBtn.disabled = false;
                        addBtn.innerHTML = origHtml;
                    }

                    return;
                }

                if (!data || data.success !== true) {
                    throw new Error((data && data.message) || @json(__('ecommerce::ecommerce.cart.add_error')));
                }

                if (addBtn) {
                    addBtn.innerHTML = '✓ ' + @json(__('ecommerce::ecommerce.added_successfully'));

                    setTimeout(function() {
                        addBtn.disabled = false;
                        addBtn.innerHTML = origHtml;
                    }, 2500);
                }
            }).catch(function(error) {
                if (addBtn) {
                    addBtn.disabled = false;
                    addBtn.innerHTML = origHtml;
                }

                window.alert(error.message || @json(__('ecommerce::ecommerce.try_again_error')));
            });
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
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (res.status === 401) {
                    window.location.href = '{{ route('auth.login') }}';
                    return;
                }

                var data = await res.json();

                if (data.success && btn) {
                    if (typeof window.refreshCartWidget === 'function') {
                        await window.refreshCartWidget();
                    }

                    btn.textContent = '✓';

                    setTimeout(function() {
                        btn.textContent = origText;
                    }, 2000);
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
    badge.style.margin = '12px 0';
    badge.textContent = available
        ? '@lang('ecommerce::ecommerce.in_stock')'
        : '@lang('ecommerce::ecommerce.out_of_stock')';

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
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: body
            });

            var data = await response.json().catch(function () {
                return null;
            });

            if ((!data && response.url.includes('/login')) || (data && data.login)) {
                window.location.href = data && data.redirect ? data.redirect : response.url;
                return;
            }

            var isFavorite = data && data.status === 'added';

            btn.classList.toggle('is-favorite', isFavorite);
            btn.setAttribute('aria-pressed', isFavorite ? 'true' : 'false');

            btn.innerHTML =
                '<i class="' + (isFavorite ? 'fa fa-heart' : 'ti-heart') + '" aria-hidden="true"></i>' +
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
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: body
        });

        var data = await response.json().catch(function () {
            return null;
        });

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
