<div class="row quick-view-rows">
    <div class="col-lg-6 col-xs-12">
        <div class="quick-view-img">
            @php
                $images = $product->images ?? null;
                $hasImages = is_object($images) && method_exists($images, 'count')
                    ? $images->count() > 0
                    : (is_array($images) ? count($images) > 0 : false);
                $firstImage = null;
                if ($hasImages) {
                    $first = is_array($images) ? reset($images) : $images->first();
                    $firstImage = is_array($first) ? ($first['image_path'] ?? null) : ($first->image_path ?? null);
                }
            @endphp
            @if($hasImages)
                <img src="{{ $firstImage ?? asset('assets/images/pro/9.jpg') }}"
                     class="img-fluid" alt="{{ $product->title }}">
            @else
                <img src="{{ $product->image_path ?? asset('assets/images/pro/9.jpg') }}"
                     class="img-fluid" alt="{{ $product->title }}">
            @endif
        </div>
    </div>
    <div class="col-lg-6 rtl-text">
        <div class="product-right">
            <h2>{{ $product->title }}</h2>

            @if($product->sale_price && $product->sale_price < $product->default_price)
                @php
                    $discountPercentage = round((($product->default_price - $product->sale_price) / $product->default_price) * 100);
                @endphp
                <h4>
                    <del>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</del>
                    <span>{{ $discountPercentage }}% @lang('ecommerce::ecommerce.off')</span>
                </h4>
                <h3>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->sale_price) }}</h3>
            @else
                <h3>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h3>
            @endif

            <div class="rating mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star{{ $i <= ($product->rate ?? 4) ? ' active' : '' }}"></i>
                @endfor
                <span class="ms-2">({{ $product->reviews_count ?? 0 }} @lang('ecommerce::ecommerce.reviews'))</span>
            </div>

            <div class="border-product">
                <h6 class="product-title">@lang('ecommerce::ecommerce.quantity')</h6>
                <div class="qty-box">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <button type="button" class="btn quantity-left-minus" data-type="minus">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                        </span>
                        <input type="text" name="quantity" class="form-control input-number" value="1" min="1">
                        <span class="input-group-prepend">
                            <button type="button" class="btn quantity-right-plus" data-type="plus">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="border-product">
                <h6 class="product-title">@lang('ecommerce::ecommerce.product_details')</h6>
                <p>{{ $product->description ?? __('ecommerce::ecommerce.no_description') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="quick-view-footer">
    @auth
        <form class="add-to-cart-form" method="POST" action="{{ route('cart.add') }}"
            up-submit up-target=".cart-header" up-scroll="false">
            @csrf
            <input type="hidden" name="item_id" value="{{ $product->id }}">
            <input type="hidden" name="qty" value="1">
            <button type="submit" class="theme_btn add-to-cart-btn">
                <span class="btn-text">@lang('ecommerce::ecommerce.add_to_cart')</span>
                <span class="btn-loading" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
                <span class="theme_btn_eff"></span>
            </button>
        </form>
    @else
        <button type="button" class="theme_btn add-to-cart-btn"
                data-bs-toggle="modal" data-bs-target="#loginRequiredModal">
            <span class="btn-text">@lang('ecommerce::ecommerce.add_to_cart')</span>
            <span class="theme_btn_eff"></span>
        </button>
    @endauth

    <a href="{{ route('product.show', $product->id) }}" class="theme_btn">
        @lang('ecommerce::ecommerce.view_details')
    </a>

    @include('ecommerce::partials.product.wishlist-btn', ['product' => $product, 'displayText' => true, 'style' => 'btn'])
</div>