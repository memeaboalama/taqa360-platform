@props(['product'])

@php
    $isFavorite = auth()->check() && \App\Domain\MarketPlace\Models\Favorite::where('user_id', auth()->id())
        ->where('item_id', $product->id)
        ->exists();
@endphp

<div class="product-box product-card-fixed">
    <div class="img-wrapper">
        <div class="front">
            <a href="{{ route('product.show', $product->id) }}"><img src="{{ $product->image_path }}" class="img-fluid product-image-fixed" alt="{{ $product->title }}"></a>
            <div>
                <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view" class="add_hover add_search" title="Quick View">
                    <i class="ti-search" aria-hidden="true"></i>
                </a>
                <button type="button" class="add_hover add_wish wishlist-btn{{ $isFavorite ? ' is-favorite' : '' }}"
                        data-product-id="{{ $product->id }}"
                        aria-label="@lang('ecommerce::ecommerce.wishlist')">
                    <i class="{{ $isFavorite ? 'fa fa-heart' : 'ti-heart' }}" aria-hidden="true"></i>
                </button>
                @auth
                <a href="{{ route('cart.add') }}" up-method="post" up-params='{"item_id": "{{ $product->id }}", "qty": 1}' up-target=".cart-header" up-scroll="false" title="@lang('ecommerce::ecommerce.add_to_cart')" class="add_hover" tabindex="0"><i class="ti-shopping-cart" aria-hidden="true"></i></a>
                @else
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginRequiredModal" title="@lang('ecommerce::ecommerce.add_to_cart')" class="add_hover" tabindex="0"><i class="ti-shopping-cart" aria-hidden="true"></i></a>
                @endauth
            </div>
        </div>
    </div>
    <div class="product-detail product-detail-fixed">
        <div class="rating">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </div>
        <a href="{{ route('product.show', $product->id) }}"><h6 class="product-title-fixed">{{ Str::limit($product->title, 35) }}</h6></a>
        <p class="product-description-fixed">
            {{ Str::limit($product->description, 80) }}
        </p>
        <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
        if (btn.dataset.initialized) return;
        btn.dataset.initialized = 'true';
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (btn.dataset.loading === '1') return;
            var productId = btn.getAttribute('data-product-id');
            var token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!productId || !token) return;
            btn.dataset.loading = '1';
            btn.disabled = true;
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
                btn.classList.toggle('is-favorite', isFavorite);
                btn.innerHTML = '<i class="' + (isFavorite ? 'fa fa-heart' : 'ti-heart') + '" aria-hidden="true"></i>';
                if (typeof window.updateWishlistBadge === 'function' && data && typeof data.count !== 'undefined') {
                    window.updateWishlistBadge(data.count);
                }
            } catch (err) {
                if (typeof window.showNotification === 'function') {
                    window.showNotification('{{ __('ecommerce::ecommerce.try_again_error') }}', 'danger');
                }
            } finally {
                delete btn.dataset.loading;
                btn.disabled = false;
            }
        });
    });
});
</script>
