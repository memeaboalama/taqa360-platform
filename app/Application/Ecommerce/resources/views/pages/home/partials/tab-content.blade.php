<div class="product-5 product-m no-arrow">
    @foreach($products as $product)
    <div class="product-box">
        <div class="img-wrapper">
            <div class="front">
                <a href="{{ route('product.show', $product->id) }}"
                   up-target=".main-body"
                   up-preload>
                    <img src="{{ $product->image_path ?? asset('assets/images/ecommerce/Product/1.jpg') }}"
                         class="img-fluid"
                         alt="{{ $product->title }}"
                         onerror="this.src='{{ asset('assets/images/ecommerce/Product/1.jpg') }}'">
                </a>
                <div>
                    <a href="#"
                       data-quick-view="{{ route('quick-view', $product->id) }}"
                       class="add_hover add_search"
                       title="@lang('ecommerce::ecommerce.quick_view')"
                       up-follow="false">
                        <i class="ti-search" aria-hidden="true"></i>
                    </a>
                    <a href="#"
                       title="Wish list"
                       class="add_hover add_wish"
                       up-follow
                       up-scroll="false">
                        <i class="ti-heart" aria-hidden="true"></i>
                    </a>
                    <a href="#"
                       title="Add to cart"
                       class="add_hover add-to-cart-btn"
                       data-product-id="{{ $product->id }}"
                       up-follow
                       up-scroll="false">
                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="product-detail">
            <div class="rating">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa fa-star{{ $i <= ($product->rate ?? 4) ? '' : '' }}"></i>
                @endfor
            </div>
            <a href="{{ route('product.show', $product->id) }}"
               up-target=".main-body"
               up-preload>
                <h6>{{ $product->title }}</h6>
            </a>
            <p>
                {{ $product->description ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book' }}
            </p>
            <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
        </div>
    </div>
    @endforeach
</div>
