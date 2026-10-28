@extends('ecommerce::layout.store-layout')

{{-- @section bodies are evaluated BEFORE the parent layout runs, so $isRTL has to be
     resolved here rather than relying on the value store-layout computes. --}}
@php $isRTL = $isRTL ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp

@section('title', __('ecommerce::ecommerce.cart.shopping_cart'))

@section('page-styles')
<style>
        :root{--ink:#20252d;--muted:#747b84;--line:#e9edf1;--yellow:#306FAA;--gold:#EA993E;--soft:#f6f7f9;--shadow:0 8px 24px rgba(36,43,53,.07)}
        *{box-sizing:border-box}body{margin:0;color:var(--ink);background:var(--soft);font:13px 'Cairo',Tahoma,sans-serif}
        a{text-decoration:none;color:inherit}button,input{font:inherit}button{border:0;cursor:pointer}
        .container{width:min(1260px,calc(100% - 40px));margin:auto}
        .topbar{height:74px;background:#fff;border-bottom:1px solid #f0f1f3;display:flex;align-items:center}
        .topbar-inner{display:flex;align-items:center;justify-content:space-between;gap:25px}
        .brand{min-width:172px}.brand img{display:block;width:175px;height:76px;object-fit:contain}
        .search{height:42px;max-width:535px;flex:1;display:flex;direction:ltr;border:1px solid var(--line);border-radius:9px;overflow:hidden}
        .search input{flex:1;border:0;outline:0;direction:{{ $isRTL ? 'rtl' : 'ltr' }};text-align:{{ $isRTL ? 'right' : 'left' }};padding:0 18px}.search button{width:48px;background:var(--yellow);font-size:18px;color:#fff}
        .top-actions{display:flex;align-items:center;gap:18px;color:#5d6269;direction:ltr}.action{font-size:19px}
        .service{display:inline-flex;align-items:center;justify-content:center;padding:8px 13px;border-radius:5px;background:var(--yellow);color:#fff;font-size:11px;font-weight:700;gap:6px}
        .nav{height:51px;background:#fff;border-bottom:1px solid #f3f3f3;box-shadow:0 3px 12px #00000008}
        .nav ul{height:100%;display:flex;align-items:center;justify-content:center;gap:40px;list-style:none;margin:0;padding:0}
        .nav a{font-size:12px}.nav a.active,.nav a:hover{color:var(--yellow);font-weight:700}
        .cart-hero{padding:32px 0 42px;color:#fff;background:linear-gradient(110deg,#102d49 0%,#173f65 52%,#245c8e 100%)}
        .cart-hero h1{margin:0 0 7px;color:#fff;font-size:28px}.cart-hero p{margin:0;font-size:13px}
        .cart-main{padding:28px 0 70px}
        .cart-layout{display:grid;grid-template-columns:minmax(0,1fr) 290px;gap:22px;direction:{{ $isRTL ? 'rtl' : 'ltr' }}}
        .cart-table-wrap,.summary{background:#fff;border:1px solid var(--line);border-radius:8px;box-shadow:var(--shadow)}
        .cart-table-wrap{overflow:hidden}
        .cart-table{width:100%;border-collapse:collapse}
        .cart-table th{height:58px;background:#fafbfc;color:#66717b;font-size:11px;font-weight:700;text-align:center;vertical-align:middle}
        .cart-table td{height:112px;padding:10px 14px;text-align:center;vertical-align:middle;border-top:1px solid var(--line);font-size:12px}
        .cart-table th:first-child,.cart-table td:first-child{text-align:start;padding-inline-start:22px}
        .cart-product{display:flex;align-items:center;gap:14px;text-align:start;min-width:0}
        .cart-product img{width:78px;height:78px;flex-shrink:0;object-fit:cover;border-radius:6px;background:#fafbfc}
        .cart-product span{min-width:0}
        .cart-product strong{display:block;color:#4f5962;font-size:13px;overflow-wrap:anywhere}
        .cart-product small{display:block;margin-top:5px;color:var(--muted)}
        .cart-price,.cart-total{direction:ltr;font-size:16px}
        .qty-control{display:inline-flex;align-items:center;border:1px solid #e2e6e9;border-radius:5px;overflow:hidden}
        .qty-control button{width:30px;height:30px;background:#fff;color:#7b858d}
        .qty-control button:hover{background:#eef4fa;color:var(--yellow)}
        .qty-control button:disabled{opacity:.4;cursor:not-allowed}
        .qty-input-display{width:38px;height:30px;border:0;border-right:1px solid #e2e6e9;border-left:1px solid #e2e6e9;text-align:center;outline:0;font:inherit}
        .remove-item{color:#8b969e;background:transparent;font-size:19px}.remove-item:hover{color:#c0392b}
        .summary{height:max-content;padding:22px}
        .summary h2{margin:0 0 20px;font-size:19px;border-bottom:1px solid var(--line);padding-bottom:15px}
        .summary-row{display:flex;justify-content:space-between;gap:14px;margin:13px 0;color:var(--muted)}
        .summary-row strong{color:var(--ink);direction:ltr}
        .summary-total{display:flex;justify-content:space-between;gap:14px;border-top:1px solid var(--line);padding-top:17px;margin-top:17px;font-size:17px;font-weight:800}
        .summary-total span:last-child{color:#306FAA;direction:ltr}
        .checkout-button,.continue-button{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;height:42px;margin-top:18px;border-radius:5px;font-size:12px;font-weight:700}
        .checkout-button{background:var(--yellow);color:#fff}.checkout-button:hover{background:#245C8E}
        .continue-button{border:1px solid #e2e6e9;color:#69737b}.continue-button:hover{border-color:var(--yellow);color:var(--yellow)}
        .clear-button{width:100%;margin-top:12px;padding:8px;background:transparent;color:#b85b5b;font-size:11px}
        .empty-cart{padding:75px 25px;text-align:center;background:linear-gradient(145deg,#fff,#eef4fa)}
        .empty-cart-icon{width:88px;height:88px;display:grid;place-items:center;margin:0 auto 18px;border-radius:50%;background:#eef4fa;color:#306FAA;font-size:35px}
        .empty-cart h2{margin:0 0 7px;font-size:21px}.empty-cart p{margin:0 0 20px;color:var(--muted)}
        .cart-toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(80px);z-index:9999;padding:12px 22px;border-radius:8px;font-size:13px;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.18);transition:transform .3s ease,opacity .3s ease;opacity:0;pointer-events:none}
        .cart-toast.show{transform:translateX(-50%) translateY(0);opacity:1}
        .cart-toast.success{background:#1db863;color:#fff}.cart-toast.error{background:#e74c3c;color:#fff}
        .row-loading{opacity:.5;pointer-events:none;transition:opacity .2s}
        footer{background:#252b31;color:#fff;padding:24px 0 18px;margin-top:0}
        .footer-grid{display:flex;justify-content:center}.footer-brand{text-align:center}
        .footer-logo{display:block;width:160px;max-width:160px;height:auto;max-height:80px;object-fit:contain;margin:0 auto 8px}
        .footer-brand p{margin:0;font-size:14px;line-height:1.8}
        .copyright{display:flex;align-items:center;justify-content:space-between;gap:20px;border-top:1px solid #3d4348;color:#afb4b8;font-size:11px;margin-top:15px;padding-top:10px}
        .footer-links{display:flex;gap:18px}.footer-links a,.socials a{color:#d2d4d6;font-size:10px;text-decoration:none}.socials{display:flex;gap:14px}
        .summary{text-align:start}
        .summary-row span,.summary-total span:first-child{text-align:start}
        .summary-row strong,.summary-total span:last-child{text-align:end}
        .summary-total{align-items:center}
        @media(max-width:900px){.cart-layout{grid-template-columns:1fr}.summary{order:-1}}
        @media(max-width:700px){.container{width:calc(100% - 24px)}.topbar{height:auto;padding:10px 0}.topbar-inner{flex-wrap:wrap;gap:10px}.brand{min-width:130px}.brand img{width:125px;height:58px}.top-actions{margin-inline-start:auto;gap:12px}.search{order:3;flex-basis:100%;max-width:none}.nav{overflow:auto}.nav ul{justify-content:flex-start;gap:27px;width:max-content;padding:0 10px}.cart-table-wrap{overflow-x:auto}.cart-table{min-width:760px}.summary{padding:18px}.cart-product img{width:62px;height:62px}}
        @media(max-width:800px){.copyright{flex-wrap:wrap;justify-content:center;text-align:center}.footer-links{gap:10px}}
    </style>
@endsection

@section('content')
<section class="cart-hero">
    <div class="container">
        <h1>@lang('ecommerce::ecommerce.cart.shopping_cart')</h1>
        <p>@lang('ecommerce::ecommerce.review_cart_message')</p>
    </div>
</section>

<div class="page-content cart-main">
    <div class="container">
        @if($cart && optional($cart->cartItems)->count())
        <div class="cart-layout">
            <section class="cart-table-wrap">
                <table class="cart-table" id="cart-table">
                    <thead>
                        <tr>
                            <th>@lang('ecommerce::ecommerce.product_label')</th>
                            <th>@lang('ecommerce::ecommerce.price')</th>
                            <th>@lang('ecommerce::ecommerce.quantity')</th>
                            <th>@lang('ecommerce::ecommerce.actions')</th>
                            <th>@lang('ecommerce::ecommerce.total')</th>
                        </tr>
                    </thead>
                    <tbody id="cart-tbody">
                        @foreach($cart->cartItems as $item)
                        <tr class="cart-row"
                            data-item-id="{{ $item->item_id }}"
                            data-price="{{ (float)($item->price ?? 0) }}"
                            data-qty="{{ $item->qty }}">
                            <td>
                                <a class="cart-product" href="{{ route('product.show', $item->item_id) }}">
                                    <img src="{{ !empty($item->item?->image_path) ? $item->item->image_path : asset('assets/images/pro/c1.jpg') }}"
                                         alt="{{ $item->item?->title ?? 'Product' }}">
                                    <span>
                                        <strong>{{ $item->item?->title ?? 'Product' }}</strong>
                                        <small>@lang('ecommerce::ecommerce.selected_product')</small>
                                        @include('ecommerce::partials.stock-label', ['item' => $item->item])
                                    </span>
                                </a>
                            </td>
                            <td class="cart-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format((float)($item->price ?? 0)) }}</td>
                            <td>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn qty-minus"
                                            data-item-id="{{ $item->item_id }}"
                                            {{ $item->qty <= 1 ? 'disabled' : '' }}>−</button>
                                    <input class="qty-input-display" type="text"
                                           value="{{ $item->qty }}"
                                           readonly
                                           data-item-id="{{ $item->item_id }}">
                                    <button type="button" class="qty-btn qty-plus"
                                            data-item-id="{{ $item->item_id }}">+</button>
                                </div>
                            </td>
                            <td>
                                <button class="remove-item" type="button"
                                        data-item-id="{{ $item->item_id }}"
                                        aria-label="@lang('ecommerce::ecommerce.remove')">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </td>
                            <td class="cart-total row-subtotal">{{ \App\Domain\AppBase\Models\Setting\Currency::format((float)($item->subtotal ?? 0)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <aside class="summary" id="cart-summary">
                <h2>@lang('ecommerce::ecommerce.order_summary')</h2>
                <div class="summary-row">
                    <span>@lang('ecommerce::ecommerce.items_count')</span>
                    <strong id="summary-count">{{ optional($cart->cartItems)->sum('qty') ?? 0 }}</strong>
                </div>
                <div class="summary-total">
                    <span>@lang('ecommerce::ecommerce.total')</span>
                    <span id="summary-total">{{ \App\Domain\AppBase\Models\Setting\Currency::format((float)($cart->total ?? 0)) }}</span>
                </div>
                <a class="checkout-button" href="{{ route('checkout') }}">
                    @lang('ecommerce::ecommerce.cart.checkout')
                        <i class="fa fa-arrow-right arrow-end"></i>
                </a>
                <a class="continue-button" href="{{ route('ecommerce.static') }}">
                    @lang('ecommerce::ecommerce.cart.continue_shopping')
                </a>
                <button class="clear-button" id="clear-cart-btn" type="button">@lang('ecommerce::ecommerce.clear_cart')</button>
            </aside>
        </div>
        @else
        <div class="empty-cart" id="empty-cart-state">
            <div class="empty-cart-icon"><i class="ti-shopping-cart"></i></div>
            <h2>@lang('ecommerce::ecommerce.cart.empty_cart')</h2>
            <p>@lang('ecommerce::ecommerce.cart.empty_cart_message')</p>
            <a class="checkout-button"
               style="width:max-content;margin:18px auto 0;padding:0 22px"
               href="{{ route('ecommerce.static') }}">
                @lang('ecommerce::ecommerce.cart.start_shopping')
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<div id="cart-toast" class="cart-toast"></div>

@php
    $cartPageI18n = [
        'serverError' => __('ecommerce::ecommerce.server_error'),
        'confirmDelete' => __('ecommerce::ecommerce.confirm_delete_item'),
        'confirmClear' => __('ecommerce::ecommerce.confirm_clear_cart'),
        'cancel' => __('ecommerce::ecommerce.cancel'),
        'delete' => __('ecommerce::ecommerce.delete'),
        'clearing' => __('ecommerce::ecommerce.clearing_cart'),
        'updated' => __('ecommerce::ecommerce.cart_updated'),
        'deleted' => __('ecommerce::ecommerce.item_removed'),
        'cleared' => __('ecommerce::ecommerce.cart_cleared'),
        'error' => __('ecommerce::ecommerce.error'),
    ];
@endphp
<script>
(function() {
    var I18N = @json($cartPageI18n);
    var CSRF_TOKEN = '{{ csrf_token() }}';
    var REMOVE_URL = '{{ route('cart.remove') }}';
    var CURRENCY_SYMBOL = @json(\App\Domain\AppBase\Models\Setting\Currency::symbol());
        var UPDATE_URL = '{{ route('cart.update-qty') }}';
    var CLEAR_URL  = '{{ route('cart.clear') }}';
    var LOGIN_URL  = '{{ route('auth.login') }}';

    function showToast(msg, type) {
        var toast = document.getElementById('cart-toast');
        if (!toast) return;
        toast.textContent = msg;
        toast.className = 'cart-toast ' + (type || 'success');
        toast.classList.add('show');
        setTimeout(function() { toast.classList.remove('show'); }, 3000);
    }

    function setRowLoading(row, loading) {
        if (!row) return;
        if (loading) row.classList.add('row-loading');
        else row.classList.remove('row-loading');
    }

    function updateSummary(data) {
        if (data.cart_count !== undefined) {
            var el = document.getElementById('summary-count');
            if (el) el.textContent = data.cart_count;
        }
        if (data.cart_total !== undefined) {
            var el2 = document.getElementById('summary-total');
            if (el2) el2.textContent = CURRENCY_SYMBOL + data.cart_total;
        }
    }

    function updateRowSubtotal(row, qty, price) {
        var subtotal = row.querySelector('.row-subtotal');
        if (subtotal) subtotal.textContent = CURRENCY_SYMBOL + (qty * price).toFixed(2);
    }

    async function cartRequest(url, body) {
        try {
            var formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            Object.keys(body).forEach(function(k) { formData.append(k, body[k]); });
            var res = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (res.status === 401) { window.location.href = LOGIN_URL; return null; }
            return await res.json();
        } catch(err) {
            showToast(I18N.serverError, 'error');
            return null;
        }
    }

    function confirmCartAction(message) {
        if (typeof window.showStaticConflictModal === 'function') {
            return window.showStaticConflictModal(message, {
                title: I18N.confirmDelete,
                cancelText: I18N.cancel,
                confirmText: I18N.delete
            });
        }
        return Promise.resolve(false);
    }

    // --- Qty minus/plus buttons ---
    document.addEventListener('click', async function(e) {
        var btn = e.target.closest('.qty-btn');
        if (!btn) return;
        var itemId = btn.dataset.itemId;
        var row = document.querySelector('.cart-row[data-item-id="' + itemId + '"]');
        if (!row) return;
        var currentQty = parseInt(row.dataset.qty) || 1;
        var price = parseFloat(row.dataset.price) || 0;
        var isPlus = btn.classList.contains('qty-plus');
        var newQty = isPlus ? currentQty + 1 : Math.max(1, currentQty - 1);
        if (newQty === currentQty) return;

        setRowLoading(row, true);
        var data = await cartRequest(UPDATE_URL, { item_id: itemId, qty: newQty });
        setRowLoading(row, false);
        if (!data) return;

        if (data.success) {
            row.dataset.qty = newQty;
            var input = row.querySelector('.qty-input-display');
            if (input) input.value = newQty;
            var minusBtn = row.querySelector('.qty-minus');
            if (minusBtn) minusBtn.disabled = (newQty <= 1);
            updateRowSubtotal(row, newQty, price);
            updateSummary(data);
            showToast(data.message || I18N.updated, 'success');
            if (data.cart_empty) setTimeout(function() { window.location.reload(); }, 700);
        } else {
            showToast(data.message || I18N.error, 'error');
        }
    });

    // --- Remove item ---
    document.addEventListener('click', async function(e) {
        var btn = e.target.closest('.remove-item');
        if (!btn) return;
        if (!await confirmCartAction(I18N.confirmDelete)) return;
        var itemId = btn.dataset.itemId;
        var row = document.querySelector('.cart-row[data-item-id="' + itemId + '"]');

        setRowLoading(row, true);
        var data = await cartRequest(REMOVE_URL, { item_id: itemId });
        if (!data) { setRowLoading(row, false); return; }

        if (data.success) {
            if (row) {
                row.style.transition = 'opacity .3s';
                row.style.opacity = '0';
                setTimeout(function() { row.remove(); }, 300);
            }
            updateSummary(data);
            showToast(data.message || I18N.deleted, 'success');
            if (data.cart_empty) {
                setTimeout(function() { window.location.reload(); }, 900);
            }
        } else {
            showToast(data.message || I18N.error, 'error');
            setRowLoading(row, false);
        }
    });

    // --- Clear cart ---
    var clearBtn = document.getElementById('clear-cart-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', async function() {
            if (!await confirmCartAction(I18N.confirmClear)) return;
            clearBtn.disabled = true;
            var origText = clearBtn.textContent;
            clearBtn.textContent = I18N.clearing;
            var data = await cartRequest(CLEAR_URL, {});
            if (!data) {
                clearBtn.disabled = false;
                clearBtn.textContent = origText;
                return;
            }
            if (data.success) {
                showToast(data.message || I18N.cleared, 'success');
                setTimeout(function() { window.location.reload(); }, 700);
            } else {
                showToast(data.message || I18N.error, 'error');
                clearBtn.disabled = false;
                clearBtn.textContent = origText;
            }
        });
    }

})();
</script>
@endsection
