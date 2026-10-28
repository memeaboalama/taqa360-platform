@extends('ecommerce::layout.store-layout')



@section('title', __('ecommerce::ecommerce.wishlist'))

@section('page-styles')
    <style>
        .wishlist-hero {
            padding: 32px 0 42px;
            color: #fff;
            background: linear-gradient(110deg, #102d49 0%, #173f65 52%, #245c8e 100%)
        }

        .wishlist-hero h1 {
            margin: 0 0 7px;
            color: #fff;
            font-size: 28px
        }

        .wishlist-hero p {
            margin: 0
        }

        .wishlist-main {
            padding: 28px 0 70px
        }

        .wishlist-flash {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600
        }

        .wishlist-flash-error {
            color: #b3261e;
            background: #fdecec;
            border: 1px solid #f5c2c0
        }

        .wishlist-flash-success {
            color: #15803d;
            background: #ecfdf3;
            border: 1px solid #bbf7d0
        }

        .wishlist-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px
        }

        .wishlist-head h2 {
            margin: 0;
            font-size: 21px
        }

        .wishlist-count {
            color: var(--muted)
        }

        .wishlist-grid {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(36, 43, 53, .07);
            overflow: hidden
        }

        .wishlist-table-head,
        .wishlist-card {
            display: grid;
            grid-template-columns: 110px 2fr 1fr 1fr 1.6fr;
            align-items: center;
            gap: 16px;
            padding: 16px 24px;
        }

        .wishlist-table-head {
            background: #23272f;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            letter-spacing: .2px;
            border-bottom: 3px solid var(--yellow, #306FAA);
            padding: 14px 24px;
        }

        .wishlist-table-head span {
            display: block;
            text-align: center;
            white-space: nowrap;
        }

        .wishlist-card {
            min-height: 120px;
            border-top: 1px solid var(--line);
            background: #fff;
        }

        .wishlist-card + .wishlist-card {
            border-top: 1px solid var(--line);
        }

        .wishlist-image-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-image {
            width: 86px;
            height: 86px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--line);
            background: #f8fafc;
        }

        .wishlist-product-info {
            min-width: 0;
            text-align: center;
        }

        .wishlist-card h3 {
            margin: 0 0 4px;
            font-size: 14px;
            line-height: 1.6;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .wishlist-store {
            font-size: 11px;
            color: var(--muted);
        }

        .wishlist-price {
            font-size: 17px;
            font-weight: 700;
            text-align: center;
            white-space: nowrap;
        }

        .stock {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
        }

        .stock .stock-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 999px;
            background: #e7f6ee;
            color: #24965a;
            white-space: nowrap;
        }

        .stock.out .stock-badge {
            background: #fdecea;
            color: #c0392b;
        }

        .card-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid var(--line);
            background: #fff;
            color: #7b8790;
            font-size: 15px;
            transition: all .2s ease;
            text-decoration: none;
        }

        .icon-btn:hover {
            border-color: #c0392b;
            color: #c0392b;
            background: #fef2f2;
        }

        .icon-btn.view:hover {
            border-color: #306FAA;
            color: #306FAA;
            background: #eff6ff;
        }

        .remove-wishlist,
        .details-button {
            color: #7b8790;
            font-size: 18px;
        }

        .remove-wishlist:hover {
            color: #c0392b;
        }

        .cart-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 0;
            border-radius: 8px;
            background: var(--yellow, #306FAA);
            padding: 10px 16px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: filter .2s ease, transform .15s ease;
        }

        .cart-button:hover {
            filter: brightness(.95);
        }

        .empty-wishlist {
            min-height: 360px;
            padding: 42px 24px;
            text-align: center;
            background: linear-gradient(145deg, #fff, #eef4fa)
        }

        .empty-wishlist img {
            width: 170px;
            height: 170px;
            object-fit: contain
        }

        .empty-wishlist h2 {
            margin: 12px 0 7px;
            font-size: 21px
        }

        .empty-wishlist p {
            margin: 0 0 20px;
            color: var(--muted)
        }

        .shop-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            padding: 0 24px;
            border-radius: 5px;
            background: var(--yellow);
            color: #fff;
            font-weight: 700
        }

        .pagination {
            padding: 20px;
            text-align: center
        }

        @media(max-width:900px) {
            .wishlist-table-head {
                display: none;
            }

            .wishlist-card {
                grid-template-columns: 90px 1fr;
                gap: 12px;
                padding: 16px;
            }

            .wishlist-image-wrap {
                grid-column: 1;
                grid-row: 1 / span 4;
            }

            .wishlist-product-info,
            .wishlist-price,
            .stock,
            .card-actions {
                grid-column: 2;
                text-align: start;
                justify-content: flex-start;
            }

            .stock,
            .wishlist-price,
            .wishlist-product-info {
                text-align: start;
            }
        }

        .wishlist-card > .card-actions form {
            margin: 0;
        }
</style>
@endsection


@section('content')
    <section class="wishlist-hero">
        <div class="container">
            <h1>@lang('ecommerce::ecommerce.wishlist')</h1>
            <p>@lang('ecommerce::ecommerce.wishlist_message')
                </p>
        </div>
    </section>
    <main class="wishlist-main">
        <div class="container">
            @if(session('error'))
                <div class="wishlist-flash wishlist-flash-error">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="wishlist-flash wishlist-flash-success">{{ session('success') }}</div>
            @endif
            <div class="wishlist-head">
                <h2>@lang('ecommerce::ecommerce.wishlist')</h2><span class="wishlist-count">{{ $favorites->total() }}
                    @lang('ecommerce::ecommerce.items')</span>
            </div>
            @if($favorites->count())
                <div class="wishlist-grid">
                    <div class="wishlist-table-head">
                        <span>@lang('ecommerce::ecommerce.image')</span><span>@lang('ecommerce::ecommerce.wishlist_product')</span><span>@lang('ecommerce::ecommerce.availability')</span><span>@lang('ecommerce::ecommerce.price')</span><span>@lang('ecommerce::ecommerce.actions')</span>
                    </div>
                    @foreach($favorites as $favorite)
                        @php
                            $item = $favorite->item;
                            if (!$item) continue;
                            $title = is_array($item->title ?? null) ? ($item->title[app()->getLocale()] ?? $item->title['en'] ?? reset($item->title)) : ($item->title ?? __('ecommerce::ecommerce.product_label'));
                            $storeName = $item->merchant->name ?? $item->store_name ?? null;
                            $fallbackImage = asset('assets/ecommerce/images/pro/1.jpg');
                            $image = $item->image_path ?: ($item->main_image['image_path'] ?? null) ?: $fallbackImage;
                            if (empty($image)) { $image = $fallbackImage; }
                            $price = (float) ($item->default_price ?? 0);
                            $inStock = $item->isInStock();
                        @endphp
                        <div class="wishlist-card">
                            <a class="wishlist-image-wrap" href="{{ route('product.show', $item->id) }}">
                                <img class="wishlist-image" src="{{ $image }}" alt="{{ $title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                            </a>
                            <div class="wishlist-product-info">
                                <h3>{{ $title }}</h3>
                                @if($storeName)
                                    <div class="wishlist-store">{{ $storeName }}</div>
                                @endif
                            </div>
                            <div class="stock {{ $inStock ? '' : 'out' }}">
                                <span class="stock-badge">{{ $inStock ? __('ecommerce::ecommerce.in_stock') : __('ecommerce::ecommerce.out_of_stock') }}</span>
                            </div>
                            <div class="wishlist-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($price) }}</div>
                            <div class="card-actions">
                                <a class="icon-btn remove-wishlist" href="{{ route('wishlist.add') }}" up-method="post"
                                    up-params='{"product_id":"{{ $item->id }}","from_page":"wishlist"}' up-target=".wishlist-main"
                                    title="@lang('ecommerce::ecommerce.remove_from_wishlist')"><i class="ti-close"></i></a>
                                <a class="icon-btn view details-button"
                                    href="{{ route('product.show', $item->id) }}" aria-label="@lang('ecommerce::ecommerce.details')" title="@lang('ecommerce::ecommerce.details')"><i
                                        class="fa fa-eye"></i></a>
                                @if($inStock)
                                    <form action="{{ route('cart.add') }}" method="POST" class="wishlist-add-cart-form">@csrf<input type="hidden" name="item_id"
                                            value="{{ $item->id }}"><input type="hidden" name="qty" value="1"><button type="submit"
                                            class="cart-button"><i class="ti-shopping-cart"></i>
                                        @lang('ecommerce::ecommerce.add_to_cart')</button></form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pagination">{{ $favorites->links() }}</div>
            @else
                <div class="empty-wishlist"><img src="{{ asset('assets/ecommerce/images/empty-wishlist.png') }}"
                        alt="@lang('ecommerce::ecommerce.wishlist_empty')">
                    <h2>@lang('ecommerce::ecommerce.wishlist_empty')</h2>
                    <p>@lang('ecommerce::ecommerce.explore_products')</p><a class="shop-button"
                        href="{{ route('ecommerce.static') }}">@lang('ecommerce::ecommerce.shop_now')</a>
                </div>
            @endif
        </div>
    </main>
@endsection

@section('scripts')
    <script>document.addEventListener('DOMContentLoaded', function () { document.querySelectorAll('a.remove-wishlist').forEach(function (link) { link.addEventListener('click', function (event) { event.preventDefault(); var params = JSON.parse(link.getAttribute('up-params') || '{}'), form = document.createElement('form'); form.method = 'POST'; form.action = link.href;[['product_id', params.product_id], ['from_page', 'wishlist_static']].forEach(function (pair) { var input = document.createElement('input'); input.type = 'hidden'; input.name = pair[0]; input.value = pair[1] || ''; form.appendChild(input) }); var token = document.createElement('input'); token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}'; form.appendChild(token); document.body.appendChild(form); form.submit(); }); }); });</script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var I18N = {
                added: @json(__('ecommerce::ecommerce.cart.item_added_success')),
                addError: @json(__('ecommerce::ecommerce.cart.add_error')),
                serviceUnavailable: @json(__('ecommerce::ecommerce.cart_service_unavailable')),
                adding: @json(__('ecommerce::ecommerce.product.adding'))
            };

            function showMessage(message) {
                if (window.showNotification && typeof window.showNotification === 'function') {
                    window.showNotification(message, 'danger');
                    return;
                }
                window.alert(message);
            }

            function showSuccess(message) {
                if (window.showNotification && typeof window.showNotification === 'function') {
                    window.showNotification(message, 'success');
                    return;
                }
                window.alert(message);
            }

            document.querySelectorAll('form.wishlist-add-cart-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    var button = form.querySelector('button[type="submit"]');
                    var originalHtml = button ? button.innerHTML : '';
                    if (button) { button.disabled = true; button.textContent = I18N.adding; }

                    var restore = function () {
                        if (button) { button.disabled = false; button.innerHTML = originalHtml; }
                    };

                    if (window.submitCartForm && typeof window.submitCartForm === 'function') {
                        window.submitCartForm(form, {})
                            .then(function (result) {
                                restore();
                                if (result && (result.login || result.cancelled || result.error || result.conflict)) return;
                                showSuccess((result && result.message) || I18N.added);
                            })
                            .catch(function (error) {
                                restore();
                                showMessage((error && error.message) || I18N.addError);
                            });
                        return;
                    }

                    fetch(form.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': (form.querySelector('input[name="_token"]') || {}).value || ''
                        },
                        body: new FormData(form)
                    })
                        .then(function (res) {
                            return res.json().catch(function () { return null; }).then(function (data) {
                                if (res.status === 401) { window.location.href = @json(route('auth.login')) + '?redirect=' + encodeURIComponent(window.location.href); return { login: true }; }
                                restore();
                                if (data && data.conflict) {
                                    if (window.showStaticConflictModal && typeof window.showStaticConflictModal === 'function') {
                                        window.showStaticConflictModal(data.message).catch(function () { });
                                    } else {
                                        showMessage(data.message);
                                    }
                                    return { conflict: true };
                                }
                                if (!data || !res.ok || (data && data.success === false)) { showMessage((data && data.message) || I18N.addError); return { error: true }; }
                                if (window.refreshCartWidget) { window.refreshCartWidget(); }
                                showSuccess(data.message || I18N.added);
                                return data;
                            });
                        })
                        .catch(function () { restore(); showMessage(I18N.serviceUnavailable); });
                });
            });
        });
    </script>
@endsection
