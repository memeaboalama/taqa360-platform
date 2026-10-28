<div class="row">
    @forelse($products as $product)
    <div class="col-xl-3 col-lg-4 col-md-6 col-6 col-grid-box">
        <div class="product-box">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{ route('product.show', $product->id) }}" up-target=".main-body" up-preload>
                        <img src="{{ $product->image_path }}" class="img-fluid" alt="{{ $product->title }}">
                    </a>
                    <div>
                        <a href="#" data-quick-view="{{ route('quick-view', $product->id) }}" class="add_hover add_search" title="@lang('ecommerce::ecommerce.quick_view')" up-follow="false" tabindex="0"><i class="ti-search" aria-hidden="true"></i></a>
                        {{-- Wishlist Button --}}
                        @include('ecommerce::partials.product.wishlist-btn', ['product' => $product])
                        @auth
                        <a href="{{ route('cart.add') }}" up-method="post" up-params='{"item_id": "{{ $product->id }}", "qty": 1}' up-target=".cart-header" up-scroll="false" title="@lang('ecommerce::ecommerce.add_to_cart')" class="add_hover" tabindex="0"><i class="ti-shopping-cart" aria-hidden="true"></i></a>
                        @else
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginRequiredModal" title="@lang('ecommerce::ecommerce.add_to_cart')" class="add_hover" tabindex="0"><i class="ti-shopping-cart" aria-hidden="true"></i></a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="product-detail">
                <div>
                    <div class="rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i></div>
                    <a href="{{ route('product.show', $product->id) }}">
                        <h6>{{ Str::limit($product->title, 45) }}</h6>
                    </a>
                    <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <h4>@lang('ecommerce::ecommerce.products.no_products_found')</h4>
        <p class="text-muted">@lang('ecommerce::ecommerce.products.try_different_filters')</p>
    </div>
    @endforelse
</div>

{{-- @if($products->hasPages())
<div class="pagination_sec">
    <div class="content_detail__pagination cdp">
        {{ $products?->links('vendor.pagination.custom') }}
    </div>
</div>
@endif --}}
