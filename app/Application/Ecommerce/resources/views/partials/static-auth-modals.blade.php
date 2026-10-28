<style>nav.nav{display:block}.product-img-wrap{position:relative;display:block}.product-stock-badge{position:absolute;left:0;right:0;bottom:0;background:rgba(192,57,43,.92);color:#fff;font-size:11px;font-weight:700;text-align:center;padding:4px 6px;z-index:2}.hululi-stock{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:700;line-height:1.6;color:#15803d;background:#ecfdf3;border:1px solid #bbf7d0;border-radius:20px;padding:3px 10px}.hululi-stock.out{color:#b91c1c;background:#fef2f2;border-color:#fecaca}.hululi-cart-item-out{display:block;color:#c0392b;font-size:10px;font-weight:700;margin-top:2px}</style>
@include('ecommerce::partials.service-request-modal')
@include('ecommerce::partials.login-required-modal')
<script src="{{ asset('assets/ecommerce/js/bootstrap.min.js') }}"></script>
<script>
    window.handleServiceRequestClick = function () {
        function openModalById(id) {
            var el = document.getElementById(id);
            if (!el) return;
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var modal = typeof bootstrap.Modal.getInstance === 'function' ? bootstrap.Modal.getInstance(el) : null;
                if (!modal) modal = new bootstrap.Modal(el);
                modal.show();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(el).modal('show');
            }
        }
        @auth
        openModalById('serviceRequestModal');
        @else
        openModalById('loginRequiredModal');
        @endauth
    };
    if (typeof window.showNotification !== 'function') {
        window.showNotification = function (message, type) {
            var el = document.createElement('div');
            el.style.cssText = 'position:fixed;top:80px;right:20px;z-index:99999;padding:12px 18px;border-radius:8px;color:#fff;background:' + (type === 'danger' ? '#e74c3c' : '#1db863') + ';box-shadow:0 8px 24px rgba(0,0,0,.18);font:600 13px Cairo,sans-serif';
            el.textContent = message;
            document.body.appendChild(el);
            setTimeout(function () { el.remove(); }, 2800);
        };
    }
</script>