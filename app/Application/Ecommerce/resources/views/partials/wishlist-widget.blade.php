{{--
    Shared wishlist widget for the new-design (static) store pages.
    Displays a heart icon link with a live count badge of the logged-in
    user's favorites, mirroring the cart widget badge.
    Use window.updateWishlistBadge(count) to refresh after add/remove.
--}}
@php $wishlistCount = auth()->check() ? \App\Domain\MarketPlace\Models\Favorite::where('user_id', auth()->id())->count() : 0; @endphp

<div class="hululi-wishlist" data-wishlist-widget id="hululi-wishlist-widget">
    <a class="action hululi-wishlist-trigger"
       href="{{ route('wishlist.index') }}"
       aria-label="@lang('ecommerce::ecommerce.wishlist')"
       title="@lang('ecommerce::ecommerce.wishlist')">
        <i class="ti-heart" aria-hidden="true"></i>
        <b class="hululi-wishlist-badge" data-wishlist-count {{ $wishlistCount > 0 ? '' : 'style="display:none"' }}>{{ $wishlistCount }}</b>
    </a>
</div>

<style>
    .hululi-wishlist{position:relative;display:inline-flex;overflow:visible}
    .hululi-wishlist .hululi-wishlist-trigger{position:relative}
    .hululi-wishlist .hululi-wishlist-badge{position:absolute;right:-9px;top:-10px;display:grid;place-items:center;min-width:17px;height:17px;padding:0 3px;border-radius:50%;background:var(--yellow,#306FAA);color:#ffffff;font-size:10px;line-height:1;font-weight:700;box-shadow:0 1px 3px rgba(0,0,0,.25)}
</style>

<script>
if (!window.__hululiWishlistInit) {
    window.__hululiWishlistInit = true;
    window.updateWishlistBadge = function (count) {
        count = parseInt(count, 10) || 0;
        document.querySelectorAll('[data-wishlist-widget]').forEach(function (w) {
            var b = w.querySelector('[data-wishlist-count]');
            if (!b) return;
            b.textContent = count;
            b.style.display = count > 0 ? '' : 'none';
        });
    };
}
</script>