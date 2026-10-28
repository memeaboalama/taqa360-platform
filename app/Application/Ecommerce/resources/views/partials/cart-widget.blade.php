{{--
    Shared cart widget for the new-design (static) store pages.
    Displays a cart icon with a live count badge plus a mini dropdown
    showing the cart contents, remove buttons and totals.

    Also exposes shared cart helpers used by every add-to-cart form:
      window.submitCartForm(form, { redirectToCheckout }): Promise
      window.refreshCartWidget(): Promise
      window.showStaticConflictModal(message): Promise<boolean>
--}}
@php
    $widgetLocale  = app()->getLocale();
    $widgetIsRtl   = $widgetLocale === 'ar';
    $widgetCart    = auth()->check()
        ? \App\Domain\Ecommerce\Models\WebCart::query()
            ->with('cartItems.item')
            ->where('user_id', auth()->id())
            ->first()
        : null;
    $widgetItems   = $widgetCart?->cartItems ?? collect();
    $widgetCount   = (int) ($widgetItems->sum('qty') ?? 0);
    $widgetTotal   = number_format((float) ($widgetCart?->total ?? 0), 2);

    $widgetTitleOf = static function ($value) use ($widgetLocale) {
        $fallback = __('ecommerce::ecommerce.product_label');
        $value = $value ?? $fallback;
        if (is_array($value)) {
            return $value[$widgetLocale] ?? $value['en'] ?? reset($value) ?? $fallback;
        }
        if (is_object($value)) {
            return $fallback;
        }
        return $value;
    };
    $widgetDefaultImg = asset('assets/ecommerce/images/Product/1.jpg');
@endphp

<div class="hululi-cart{{ $widgetIsRtl ? ' is-rtl' : ' is-ltr' }}" data-cart-widget id="hululi-cart-widget">
    <a class="action cart hululi-cart-trigger"
       href="{{ route('cart.index') }}"
       aria-label="@lang('ecommerce::ecommerce.cart.shopping_cart')"
       title="@lang('ecommerce::ecommerce.cart.shopping_cart')"
       data-cart-toggle>
        <i class="ti-shopping-cart" aria-hidden="true"></i>
        <b class="hululi-cart-badge" data-cart-count {{ $widgetCount > 0 ? '' : 'style="display:none"' }}>{{ $widgetCount }}</b>
    </a>

    <div class="hululi-cart-panel" data-cart-panel aria-hidden="true">
        <div class="hululi-cart-head">
            <span>@lang('ecommerce::ecommerce.cart.shopping_cart')</span>
            <button type="button" class="hululi-cart-head-close" data-cart-close aria-label="@lang('ecommerce::ecommerce.close')">&times;</button>
        </div>
        <div class="hululi-cart-body" data-cart-body>
            @if($widgetItems->isNotEmpty())
                @foreach($widgetItems->take(5) as $widgetItem)
                    <div class="hululi-cart-item" data-cart-row>
                        <a class="hululi-cart-item-img" href="{{ route('product.show', $widgetItem->item_id) }}">
                            <img src="{{ !empty($widgetItem->item?->image_path) ? $widgetItem->item->image_path : $widgetDefaultImg }}"
                                 alt="{{ $widgetTitleOf($widgetItem->item?->title) }}"
                                 loading="lazy"
                                 onerror="this.onerror=null;this.src='{{ $widgetDefaultImg }}';">
                        </a>
                        <div class="hululi-cart-item-info">
                            <a class="hululi-cart-item-title" href="{{ route('product.show', $widgetItem->item_id) }}">
                                {{ \Illuminate\Support\Str::limit($widgetTitleOf($widgetItem->item?->title), 26) }}
                            </a>
                            @if(!$widgetItem->item?->isInStock())<span class="hululi-cart-item-out">@lang('ecommerce::ecommerce.out_of_stock')</span>@endif<span class="hululi-cart-item-meta">{{ \App\Domain\AppBase\Models\Setting\Currency::format((float) ($widgetItem->item?->default_price ?? 0)) }}× {{ $widgetItem->qty }}</span>
                        </div>
                        <button type="button" class="hululi-cart-remove" data-cart-remove="{{ $widgetItem->item_id }}" aria-label="@lang('ecommerce::ecommerce.remove')">&times;</button>
                    </div>
                @endforeach
                <div class="hululi-cart-total-row">
                    <span>@lang('ecommerce::ecommerce.cart.total')</span>
                    <b data-cart-total>{{ \App\Domain\AppBase\Models\Setting\Currency::format($widgetTotal) }}</b>
                </div>
            @else
                <div class="hululi-cart-empty" data-cart-empty>
                    <i class="ti-shopping-cart" aria-hidden="true"></i>
                    <span>@lang('ecommerce::ecommerce.cart.empty_cart')</span>
                </div>
            @endif
        </div>
        <div class="hululi-cart-footer">
            <a class="hululi-cart-btn" href="{{ route('cart.index') }}">@lang('ecommerce::ecommerce.cart.view_cart')</a>
            <a class="hululi-cart-btn hululi-cart-btn-primary" href="{{ route('checkout') }}">@lang('ecommerce::ecommerce.cart.checkout')</a>
        </div>
    </div>
</div>

<style>
    .hululi-cart{position:relative;display:inline-flex;overflow:visible}
    .hululi-cart .hululi-cart-trigger{position:relative}
    .hululi-cart .hululi-cart-badge{position:absolute;right:-9px;top:-10px;display:grid;place-items:center;min-width:17px;height:17px;padding:0 3px;border-radius:50%;background:var(--yellow,#306FAA);color:#ffffff;font-size:10px;line-height:1;box-shadow:0 1px 3px rgba(0,0,0,.25)}
    .hululi-cart-panel{position:absolute;top:calc(100% + 8px);right:0;left:auto;z-index:9998;width:min(300px,calc(100vw - 24px)) !important;max-width:340px !important;display:none;background:#fff;border:1px solid #e9edf1;border-radius:10px;box-shadow:0 14px 38px rgba(32,37,45,.16);overflow:hidden;direction:{{ $widgetIsRtl ? 'rtl':'ltr' }};text-align:{{ $widgetIsRtl ? 'right':'left' }}}
    .hululi-cart-panel.is-open{display:block}
    .hululi-cart-head{display:flex;align-items:center;justify-content:space-between;padding:11px 14px;border-bottom:1px solid #f0f1f3;font-weight:700;font-size:12px;background:#fafbfc}
    .hululi-cart-head-close{background:transparent;color:#929aa2;font-size:19px;line-height:1;padding:0 4px}
    .hululi-cart-head-close:hover{color:#c0392b}
    .hululi-cart-body{max-height:250px;overflow-y:auto;padding:4px 12px}
    .hululi-cart-item{display:flex;align-items:center;gap:10px;padding:9px 2px;border-bottom:1px solid #f4f5f7}
    .hululi-cart-item:last-child{border-bottom:0}
    .hululi-cart-item-img{flex:none;width:46px;height:46px;overflow:hidden;border-radius:6px;background:#fafbfc}
    .hululi-cart-item-img img{width:100%;height:100%;object-fit:cover;display:block}
    .hululi-cart-item-info{min-width:0;flex:1}
    .hululi-cart-item-title{display:block;color:#20252d;font-size:11px;font-weight:600;line-height:1.5;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-decoration:none}
    .hululi-cart-item-title:hover{color:#EA993E}
    .hululi-cart-item-meta{display:block;margin-top:2px;color:#747b84;font-size:10px}
.hululi-cart-item-out{display:block;color:#c0392b;font-size:10px;font-weight:700;margin-top:2px}
    .hululi-cart-remove{flex:none;width:22px;height:22px;display:grid;place-items:center;background:#f2f4f6;color:#8b969e;border-radius:50%;font-size:14px;line-height:1}
    .hululi-cart-remove:hover{background:#fdecec;color:#c0392b}
    .hululi-cart-total-row{display:flex;align-items:center;justify-content:space-between;padding:10px 2px;border-top:1px solid #f0f1f3;font-size:12px;font-weight:700}
    .hululi-cart-total-row b{color:#EA993E}
    .hululi-cart-empty{display:flex;flex-direction:column;align-items:center;gap:8px;padding:26px 8px;color:#9299a0;font-size:12px}
    .hululi-cart-empty i{font-size:26px;color:#c9ced4}
    .hululi-cart-footer{display:flex;gap:8px;padding:11px 12px;border-top:1px solid #f0f1f3;background:#fafbfc}
    .hululi-cart-btn{flex:1;height:36px;display:grid;place-items:center;border:1px solid #e2e6e9;border-radius:6px;color:#5b6570;font-size:11px;font-weight:700;text-decoration:none}
    .hululi-cart-btn-primary{background:var(--yellow,#306FAA);border-color:var(--yellow,#306FAA);color:#ffffff}
    .hululi-cart-btn:hover{text-decoration:none;border-color:#EA993E;color:#245C8E}
    .hululi-cart-btn-primary:hover{border-color:#245C8E;color:#ffffff}
    .hululi-cart-toast{position:fixed;top:24px;z-index:99999;padding:11px 18px;border-radius:8px;color:#fff;font:600 13px Cairo,Tahoma,sans-serif;box-shadow:0 8px 24px rgba(0,0,0,.18);opacity:0;transform:translateY(-12px);transition:all .3s ease;pointer-events:none}
    .hululi-cart-toast.show{opacity:1;transform:translateY(0)}
    .hululi-cart-toast.success{background:#1db863}
    .hululi-cart-toast.danger{background:#e74c3c}
</style>

<script>
if (!window.__hululiCartWidgetInit) {
    window.__hululiCartWidgetInit = true;
    (function () {
        var ROUTES = {
            add: @json(route('cart.add')),
            remove: @json(route('cart.remove')),
            sidebar: @json(route('cart.sidebar')),
            switchStore: @json(route('cart.switch-to-store')),
            checkout: @json(route('checkout')),
            login: @json(route('auth.login'))
        };
        var CSRF = '{{ csrf_token() }}';
        var DEFAULT_IMG = @json($widgetDefaultImg);
        var CURRENCY_SYMBOL = @json(\App\Domain\AppBase\Models\Setting\Currency::symbol());
        var I18N = {
            empty: @json(__('ecommerce::ecommerce.cart.empty_cart')),
            total: @json(__('ecommerce::ecommerce.cart.total')),
            outOfStock: @json(__('ecommerce::ecommerce.out_of_stock')),
            close: @json(__('ecommerce::ecommerce.close')),
            remove: @json(__('ecommerce::ecommerce.remove')),
            added: @json(__('ecommerce::ecommerce.cart.item_added_success')),
            storeSwitched: @json(__('ecommerce::ecommerce.cart_switched_success')),
            removed: @json(__('ecommerce::ecommerce.cart.item_removed_success')),
            conflictTitle: @json(__('ecommerce::ecommerce.cart.store_conflict_title')),
            conflictCancel: @json(__('ecommerce::ecommerce.cart.store_conflict_cancel')),
            conflictConfirm: @json(__('ecommerce::ecommerce.cart.empty_and_continue')),
            conflictItem: @json(__('ecommerce::ecommerce.cart_conflict_item')),
            err: @json(__('ecommerce::ecommerce.try_again_error'))
        };
        var widget = document.getElementById('hululi-cart-widget');
        var badgeEl = widget ? widget.querySelector('[data-cart-count]') : null;
        var bodyEl = widget ? widget.querySelector('[data-cart-body]') : null;
        var panelEl = widget ? widget.querySelector('[data-cart-panel]') : null;
        var hoverTimer = null;

        function esc(str) {
            return String(str == null ? '' : str).replace(/[&<>"']/g, function (m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
            });
        }
        function money(v) {
            return esc((parseFloat(v) || 0).toFixed(2));
        }
        function toast(message, type) {
            var el = document.getElementById('hululi-cart-toast');
            if (!el) {
                el = document.createElement('div');
                el.id = 'hululi-cart-toast';
                el.className = 'hululi-cart-toast';
                document.body.appendChild(el);
                el.style[document.body.dir === 'rtl' || document.documentElement.dir === 'rtl' ? 'right' : 'left'] = '24px';
            }
            el.textContent = message;
            el.className = 'hululi-cart-toast ' + (type === 'danger' ? 'danger' : 'success');
            void el.offsetWidth;
            el.classList.add('show');
            clearTimeout(el._t);
            el._t = setTimeout(function () { el.classList.remove('show'); }, 2600);
        }
        function clampToViewport() {
            if (!panelEl) return;
            var pad = 8;
            var doc = document.documentElement;
            panelEl.style.transform = '';
            var rect = panelEl.getBoundingClientRect();
            var shift = 0;
            if (rect.left < pad) shift = pad - rect.left;
            else if (rect.right > doc.clientWidth - pad) shift = doc.clientWidth - pad - rect.right;
            if (shift) panelEl.style.transform = 'translateX(' + shift + 'px)';
        }
        function open() {
            if (!panelEl) return;
            panelEl.classList.add('is-open');
            panelEl.setAttribute('aria-hidden', 'false');
            clampToViewport();
        }
        function close() {
            if (!panelEl) return;
            panelEl.classList.remove('is-open');
            panelEl.setAttribute('aria-hidden', 'true');
        }
        function toggle() {
            if (panelEl.classList.contains('is-open')) close(); else open();
        }

        function rowHtml(it) {
            return '<div class="hululi-cart-item" data-cart-row>' +
                '<a class="hululi-cart-item-img" href="' + esc(it.url || '#') + '">' +
                    '<img src="' + esc(it.image) + '" alt="' + esc(it.title) + '" loading="lazy" onerror="this.onerror=null;this.src=\'' + DEFAULT_IMG + '\';">' +
                '</a>' +
                '<div class="hululi-cart-item-info">' +
                    '<a class="hululi-cart-item-title" href="' + esc(it.url || '#') + '">' + esc(it.title) + '</a>' +
                    '<span class="hululi-cart-item-meta">' + CURRENCY_SYMBOL + money(it.price) + ' &times; ' + esc(it.qty) + '</span>' +
                    (it.available === false ? '<span class="hululi-cart-item-out">' + esc(I18N.outOfStock) + '</span>' : '') +
                '</div>' +
                '<button type="button" class="hululi-cart-remove" data-cart-remove="' + esc(it.item_id) + '" aria-label="' + esc(I18N.remove) + '">&times;</button>' +
            '</div>';
        }
        function applyCart(data) {
            if (!bodyEl || !badgeEl) return;
            var count = data && data.cart_count != null ? parseInt(data.cart_count, 10) || 0 : 0;
            badgeEl.textContent = count;
            badgeEl.style.display = count > 0 ? '' : 'none';
            var items = (data && data.items) || [];
            var total = data && data.cart_total != null ? data.cart_total : '0.00';
            if (!items.length) {
                bodyEl.innerHTML = '<div class="hululi-cart-empty" data-cart-empty><i class="ti-shopping-cart" aria-hidden="true"></i><span>' + esc(I18N.empty) + '</span></div>';
            } else {
                bodyEl.innerHTML = items.slice(0, 5).map(rowHtml).join('') +
                    '<div class="hululi-cart-total-row"><span>' + esc(I18N.total) + '</span><b data-cart-total>' + CURRENCY_SYMBOL + money(total) + '</b></div>';
            }
        }
        function refreshCartWidget() {
            return fetch(ROUTES.sidebar, {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) { applyCart(data); return data; })
            .catch(function () { return null; });
        }

        function showConflictModal(message, options) {
            return new Promise(function (resolve) {
            options = options || {};
                if (!document.getElementById('hululi-conflict-preset')) {
                    var st = document.createElement('style');
                    st.id = 'hululi-conflict-preset';
                    st.textContent = '.hululi-conflict-overlay{position:fixed;inset:0;z-index:99999;display:grid;place-items:center;padding:20px;background:rgba(32,37,45,.45)}' +
                        '.hululi-conflict-modal{position:relative;width:min(420px,100%);padding:25px 24px 20px;border:1px solid #e9edf1;border-radius:10px;background:#fff;box-shadow:0 16px 45px rgba(0,0,0,.2);direction:inherit;text-align:start}' +
                        '.hululi-conflict-modal h3{margin:0 0 10px;color:#20252d;font:700 18px Cairo,Tahoma,sans-serif}' +
                        '.hululi-conflict-modal p{margin:0;color:#66717b;font:13px/1.9 Cairo,Tahoma,sans-serif}' +
                        '.hululi-conflict-close{position:absolute;top:10px;inset-inline-start:12px;background:transparent;color:#929aa2;font-size:23px}' +
                        '.hululi-conflict-actions{display:flex;justify-content:flex-start;gap:8px;margin-top:20px}' +
                        '.hululi-conflict-actions button{height:36px;padding:0 15px;border-radius:4px;font:600 11px Cairo,Tahoma,sans-serif}' +
                        '.hululi-conflict-cancel{border:1px solid #e0e4e8;background:#fff;color:#6d767e}' +
                        '.hululi-conflict-cancel:hover{border-color:#EA993E}' +
                        '.hululi-conflict-confirm{background:#306FAA;color:#ffffff;font-weight:700}' +
                        '.hululi-conflict-confirm:hover{background:#245C8E}';
                    document.head.appendChild(st);
                }
                var overlay = document.createElement('div');
                overlay.className = 'hululi-conflict-overlay';
                overlay.innerHTML = '<div class="hululi-conflict-modal" role="dialog" aria-modal="true">' +
                    '<button class="hululi-conflict-close" type="button" aria-label="' + esc(I18N.close) + '">&times;</button>' +
                    '<h3>' + esc(I18N.conflictTitle) + '</h3>' +
                    '<p></p>' +
                    '<div class="hululi-conflict-actions">' +
                        '<button class="hululi-conflict-cancel" type="button">' + esc(I18N.conflictCancel) + '</button>' +
                        '<button class="hululi-conflict-confirm" type="button">' + esc(I18N.conflictConfirm) + '</button>' +
                    '</div>' +
                '</div>';
                document.body.appendChild(overlay);
                overlay.querySelector('.hululi-conflict-modal p').textContent = message || I18N.err;
                if (options.title) overlay.querySelector('.hululi-conflict-modal h3').textContent = options.title;
                if (options.cancelText) overlay.querySelector('.hululi-conflict-cancel').textContent = options.cancelText;
                if (options.confirmText) overlay.querySelector('.hululi-conflict-confirm').textContent = options.confirmText;
                function done(val) { overlay.remove(); resolve(val); }
                overlay.querySelector('.hululi-conflict-close').addEventListener('click', function () { done(false); });
                overlay.addEventListener('click', function (e) { if (e.target === overlay) done(false); });
                overlay.querySelector('.hululi-conflict-cancel').addEventListener('click', function () { done(false); });
                overlay.querySelector('.hululi-conflict-confirm').addEventListener('click', function () { done(true); });
            });
        }

        function submitCartForm(form, opts) {
            opts = opts || {};
            var fd = new FormData(form);
            var addBody = new FormData();
            var addValue = (form.getAttribute('data-add-field') || 'item_id');
            addBody.append('item_id', fd.get(addValue) || form.getAttribute('data-add-id') || '');
            addBody.append('qty', fd.get('qty') || 1);
            addBody.append('store_id', form.getAttribute('data-store-id') || '');

            return fetch(ROUTES.add, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
                body: addBody
            })
            .then(function (res) {
                var ct = res.headers.get('content-type') || '';
                if (ct.indexOf('application/json') === -1 && ct.indexOf('text/json') === -1) {
                    if (res.status === 401) { redirectLogin(); return { login: true }; }
                    if (res.status === 409) { return handleConflict(addBody, I18N.conflictItem); }
                    throw new Error(I18N.err);
                }
                return res.json().then(function (data) {
                    if (res.status === 401) { redirectLogin(); return { login: true }; }
                    if (data && data.conflict) return handleConflict(addBody, data.message);
                    if (!res.ok || (data && data.success === false)) throw new Error((data && data.message) || I18N.err);
                    return data;
                });
            })
            .then(function (data) {
                if (data && data.login) return data;
                return refreshCartWidget().then(function () { return data; });
            });
        }
        function redirectLogin() {
            var url = new URL(ROUTES.login, window.location.origin);
            url.searchParams.set('redirect', window.location.href);
            window.location.href = url.toString();
        }
        function handleConflict(addBody, message) {
            return showConflictModal(message || I18N.err).then(function (ok) {
                if (!ok) return { conflict: true, cancelled: true };
                var fd2 = new FormData();
                fd2.append('item_id', addBody.get('item_id'));
                fd2.append('qty', addBody.get('qty') || 1);
                return fetch(ROUTES.switchStore, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
                    body: fd2
                }).then(function (res) {
                    return res.json().then(function (data) {
                        if (!res.ok || (data && data.success === false)) throw new Error((data && data.message) || I18N.err);
                        return data;
                    });
                });
            });
        }

        if (widget) {
            widget.addEventListener('click', function (e) {
                var t = e.target;
                var toggleEl = t.closest ? t.closest('[data-cart-toggle]') : null;
                var closeEl = t.closest ? t.closest('[data-cart-close]') : null;
                var rmEl = t.closest ? t.closest('[data-cart-remove]') : null;
                if (toggleEl) { e.preventDefault(); e.stopPropagation(); toggle(); return; }
                if (closeEl) { close(); return; }
                if (rmEl) {
                    e.preventDefault(); e.stopPropagation();
                    var id = rmEl.getAttribute('data-cart-remove');
                    var fd = new FormData();
                    fd.append('item_id', id);
                    fetch(ROUTES.remove, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
                        body: fd
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data && data.success) { toast(I18N.removed); refreshCartWidget(); }
                    })
                    .catch(function () { toast(I18N.err, 'danger'); });
                    return;
                }
            });
            if (window.matchMedia && window.matchMedia('(hover:hover)').matches) {
                widget.addEventListener('mouseenter', function () {
                    clearTimeout(hoverTimer);
                    open();
                });
                widget.addEventListener('mouseleave', function () {
                    clearTimeout(hoverTimer);
                    hoverTimer = setTimeout(close, 250);
                });
            }
        }
        document.addEventListener('click', function (e) {
            if (widget && panelEl && panelEl.classList.contains('is-open') && !widget.contains(e.target)) close();
        });
        window.addEventListener('resize', function () {
            if (panelEl && panelEl.classList.contains('is-open')) clampToViewport();
        });

        window.refreshCartWidget = refreshCartWidget;
        window.submitCartForm = submitCartForm;
        window.showStaticConflictModal = showConflictModal;
    })();
}
</script>
