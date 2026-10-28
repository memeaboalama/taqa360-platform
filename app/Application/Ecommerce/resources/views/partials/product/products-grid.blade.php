<div class="product-wrapper-grid">
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-4 col-6 col-grid-box">
            <div class="product-box">
                <div class="img-wrapper">
                    <div class="front">
                        <a href="{{ route('product.show', $product->id) }}"
                           up-target=".main-content"
                           up-history="true"
                           up-preload>
                            <img src="{{ $product->image_path ?? asset('assets/images/pro/1.jpg') }}"
                                 class="img-fluid"
                                 alt="{{ $product->title }}"
                                 onerror="this.src='{{ asset('assets/images/pro/1.jpg') }}'">
                        </a>
                        <div class="product-actions">
                            <a href="#"
                               data-bs-toggle="modal"
                               data-bs-target="#quick-view"
                               class="add_hover add_search"
                               title="Quick View"
                               up-layer="new"
                               up-size="large"
                               up-target=".quick-view-modal">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </a>
                            <button type="button"
                               title="@lang('ecommerce::ecommerce.add_to_wishlist')"
                               class="add_hover add_wish wishlist-grid-btn"
                               data-product-id="{{ $product->id }}"
                               aria-label="@lang('ecommerce::ecommerce.add_to_wishlist')">
                                <i class="fa fa-heart" aria-hidden="true"></i>
                            </button>
                            <a href="#"
                               title="Add to cart"
                               class="add_hover add-to-cart"
                               up-follow
                               up-method="post"
                               up-target=".cart-count"
                               up-data='{"product_id": "{{ $product->id }}", "quantity": 1}'>
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="product-detail">
                    <div>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star{{ $i <= ($product->rating ?? 5) ? '' : '-o' }}"></i>
                            @endfor
                            <small class="text-muted">({{ $product->reviews_count ?? 0 }})</small>
                        </div>
                        <a href="{{ route('product.show', $product->id) }}"
                           up-target=".main-content"
                           up-history="true">
                            <h6>{{ Str::limit($product->title, 50) }}</h6>
                        </a>
                        <p class="product-description">
                            {{ Str::limit($product->description ?? 'No description available', 120) }}
                        </p>
                        <div class="product-price">
                            @if($product->compare_price && $product->compare_price > $product->price)
                            <span class="compare-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->compare_price) }}</span>
                            @endif
                            <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->price) }}</h4>
                        </div>

                        @if(!$product->isInStock())
                        <div class="out-of-stock-badge">
                            <span class="badge bg-danger">@lang('ecommerce::ecommerce.out_of_stock')</span>
                        </div>
                        @elseif($product->stock !== null && (int) $product->stock < 10)
                        <div class="low-stock-badge">
                            <span class="badge bg-warning">Only {{ (int) $product->stock }} left</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="no-products-found text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="fa fa-search fa-3x text-muted"></i>
                </div>
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                <div class="suggestions">
                    <a href="{{ route('categories.index') }}"
                       class="btn btn-theme me-2"
                       up-target=".main-content"
                       up-history="true">
                       View All Products
                    </a>
                    <button class="btn btn-outline-secondary" onclick="clearFilters()">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

@if($products->hasPages())
<div class="pagination_sec mt-4">
    <div class="content_detail__pagination cdp">
        {{-- Previous Page Link --}}
        @if($products->onFirstPage())
            <span class="cdp_i disabled">
                <i class="fa fa-angle-double-left" aria-hidden="true"></i> @lang('ecommerce::ecommerce.previous')
            </span>
        @else
            <a href="{{ $products->previousPageUrl() }}"
               class="cdp_i"
               up-target=".collection-product-wrapper"
               up-scroll="false"
               up-preload>
                <i class="fa fa-angle-double-left" aria-hidden="true"></i> @lang('ecommerce::ecommerce.previous')
            </a>
        @endif

        {{-- Pagination Elements --}}
        @php
            $start = max(1, $products->currentPage() - 2);
            $end = min($products->lastPage(), $products->currentPage() + 2);
        @endphp

        @if($start > 1)
            <a href="{{ $products->url(1) }}"
               class="cdp_i"
               up-target=".collection-product-wrapper"
               up-scroll="false"
               up-preload>1</a>
            @if($start > 2)
                <span class="cdp_i disabled">...</span>
            @endif
        @endif

        @for($page = $start; $page <= $end; $page++)
            @if($page == $products->currentPage())
                <span class="cdp_i active">{{ $page }}</span>
            @else
                <a href="{{ $products->url($page) }}"
                   class="cdp_i"
                   up-target=".collection-product-wrapper"
                   up-scroll="false"
                   up-preload>{{ $page }}</a>
            @endif
        @endfor

        @if($end < $products->lastPage())
            @if($end < $products->lastPage() - 1)
                <span class="cdp_i disabled">...</span>
            @endif
            <a href="{{ $products->url($products->lastPage()) }}"
               class="cdp_i"
               up-target=".collection-product-wrapper"
               up-scroll="false"
               up-preload>{{ $products->lastPage() }}</a>
        @endif

        {{-- Next Page Link --}}
        @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}"
               class="cdp_i"
               up-target=".collection-product-wrapper"
               up-scroll="false"
               up-preload>
                @lang('ecommerce::ecommerce.next') <i class="fa fa-angle-double-right" aria-hidden="true"></i>
            </a>
        @else
            <span class="cdp_i disabled">
                @lang('ecommerce::ecommerce.next') <i class="fa fa-angle-double-right" aria-hidden="true"></i>
            </span>
        @endif
    </div>

    <div class="pagination-info text-center mt-2">
        <small class="text-muted">
            Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
            • {{ $products->total() }} total products
        </small>
    </div>
</div>
@endif

<style>
.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-box:hover .product-actions {
    opacity: 1;
}

.product-description {
    font-size: 0.875rem;
    color: #666;
    line-height: 1.4;
    margin-bottom: 0.5rem;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 0.5rem;
}

.compare-price {
    text-decoration: line-through;
    color: #999;
    font-size: 0.875rem;
}

.out-of-stock-badge,
.low-stock-badge {
    margin-top: 0.5rem;
}

.no-products-found {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 3rem !important;
}

.empty-state-icon {
    opacity: 0.5;
}

.suggestions {
    margin-top: 1rem;
}

.pagination-info {
    font-size: 0.875rem;
}

.cdp_i {
    transition: all 0.3s ease;
}

.cdp_i:hover:not(.disabled):not(.active) {
    background: #007bff;
    color: white;
}

.pagination_sec .cdp_i i {
    display: inline-block;
}

[dir="rtl"] .pagination_sec .cdp_i i {
    transform: scaleX(-1);
}
</style>
<script>
document.addEventListener('click', async function (event) {
    var btn = event.target.closest('.wishlist-grid-btn');
    if (!btn) return;
    event.preventDefault();
    event.stopPropagation();
    var itemId = btn.dataset.productId;
    var token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!itemId || !token || btn.dataset.loading === '1') return;
    btn.dataset.loading = '1';
    btn.disabled = true;
    try {
        var body = new FormData();
        body.append('_token', token);
        body.append('product_id', itemId);
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
        console.error('Wishlist error', err);
    } finally {
        delete btn.dataset.loading;
        btn.disabled = false;
    }
});
</script>
