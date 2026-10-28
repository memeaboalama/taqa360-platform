<div id="tab-content-container">
    <div id="tab-1" class="tab-content active default" style="display: block;">
        <div class="product-5 product-m no-arrow">
            @if(isset($newProducts) && $newProducts->count() > 0)
                @foreach($newProducts as $product)
                <div class="product-box product-card-fixed">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="{{ route('product.show', $product->id) }}"
                               up-target=".main-body"
                               up-preload>
                                <img src="{{ $product->image_path ?? asset('assets/images/ecommerce/Product/1.jpg') }}"
                                     class="img-fluid product-image-fixed"
                                     alt="{{ $product->title }}">
                            </a>
                            <div class="product-actions">
                                <a href="#"
                                   data-quick-view="{{ route('quick-view', $product->id) }}"
                                   class="add_hover add_search"
                                   title="@lang('ecommerce::ecommerce.quick_view')"
                                   up-follow="false">
                                    <i class="ti-search" aria-hidden="true"></i>
                                </a>
                                @include('ecommerce::partials.product.wishlist-btn', ['product' => $product])
                                </a>
                                @auth
                                    <a href="{{ route('cart.add') }}"
                                       title="@lang('ecommerce::ecommerce.add_to_cart')"
                                       class="add_hover"
                                       up-method="post"
                                       up-params='{"item_id": "{{ $product->id }}", "qty": 1}'
                                       up-target=".cart-header"
                                       up-scroll="false">
                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#loginRequiredModal"
                                       title="@lang('ecommerce::ecommerce.add_to_cart')"
                                       class="add_hover"
                                       tabindex="0">
                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="product-detail product-detail-fixed">
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star{{ $i <= ($product->rate ?? 4) ? ' active' : '' }}"></i>
                            @endfor
                        </div>
                        <a href="{{ route('product.show', $product->id) }}"
                           up-target=".main-body"
                           up-preload>
                            <h6 class="product-title-fixed">{{ \Illuminate\Support\Str::limit($product->title, 35) }}</h6>
                        </a>
                        <p class="product-description-fixed">
                            {{ \Illuminate\Support\Str::limit($product->description ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 80) }}
                        </p>
                        <h4 class="product-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                    </div>
                </div>
                @endforeach
            @elseif(isset($featuredProducts) && $featuredProducts->count() > 0)
                @foreach($featuredProducts as $product)
                <div class="product-box product-card-fixed">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="{{ route('product.show', $product->id) }}"
                               up-target=".main-body"
                               up-preload>
                                <img src="{{ $product->image_path ?? asset('assets/images/ecommerce/Product/1.jpg') }}"
                                     class="img-fluid product-image-fixed"
                                     alt="{{ $product->title }}">
                            </a>
                            <div class="product-actions">
                                <a href="#"
                                   data-quick-view="{{ route('quick-view', $product->id) }}"
                                   class="add_hover add_search"
                                   title="@lang('ecommerce::ecommerce.quick_view')"
                                   up-follow="false">
                                    <i class="ti-search" aria-hidden="true"></i>
                                </a>
                                @include('ecommerce::partials.product.wishlist-btn', ['product' => $product])
                                </a>
                                @auth
                                    <a href="{{ route('cart.add') }}"
                                       title="@lang('ecommerce::ecommerce.add_to_cart')"
                                       class="add_hover"
                                       up-method="post"
                                       up-params='{"item_id": "{{ $product->id }}", "qty": 1}'
                                       up-target=".cart-header-container"
                                       up-scroll="false">
                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#loginRequiredModal"
                                       title="@lang('ecommerce::ecommerce.add_to_cart')"
                                       class="add_hover"
                                       tabindex="0">
                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="product-detail product-detail-fixed">
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star{{ $i <= ($product->rate ?? 4) ? ' active' : '' }}"></i>
                            @endfor
                        </div>
                        <a href="{{ route('product.show', $product->id) }}"
                           up-target=".main-body"
                           up-preload>
                            <h6 class="product-title-fixed">{{ \Illuminate\Support\Str::limit($product->title, 35) }}</h6>
                        </a>
                        <p class="product-description-fixed">
                            {{ \Illuminate\Support\Str::limit($product->description ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 80) }}
                        </p>
                        <h4 class="product-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                    </div>
                </div>
                @endforeach
            @elseif(isset($bestSellers) && $bestSellers->count() > 0)
                @foreach($bestSellers as $product)
                <div class="product-box product-card-fixed">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="{{ route('product.show', $product->id) }}"
                               up-target=".main-body"
                               up-preload>
                                <img src="{{ $product->image_path ?? asset('assets/images/ecommerce/Product/1.jpg') }}"
                                     class="img-fluid product-image-fixed"
                                     alt="{{ $product->title }}">
                            </a>
                            <div class="product-actions">
                                <a href="#"
                                   data-quick-view="{{ route('quick-view', $product->id) }}"
                                   class="add_hover add_search"
                                   title="@lang('ecommerce::ecommerce.quick_view')"
                                   up-follow="false">
                                    <i class="ti-search" aria-hidden="true"></i>
                                </a>
                                @include('ecommerce::partials.product.wishlist-btn', ['product' => $product])
                                </a>
                                @auth
                                    <a href="{{ route('cart.add') }}"
                                       title="@lang('ecommerce::ecommerce.add_to_cart')"
                                       class="add_hover"
                                       up-method="post"
                                       up-params='{"item_id": "{{ $product->id }}", "qty": 1}'
                                       up-target=".cart-header-container">
                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#loginRequiredModal"
                                       title="@lang('ecommerce::ecommerce.add_to_cart')"
                                       class="add_hover"
                                       tabindex="0">
                                        <i class="ti-shopping-cart" aria-hidden="true"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="product-detail product-detail-fixed">
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star{{ $i <= ($product->rate ?? 4) ? ' active' : '' }}"></i>
                            @endfor
                        </div>
                        <a href="{{ route('product.show', $product->id) }}"
                           up-target=".main-body"
                           up-preload>
                            <h6 class="product-title-fixed">{{ \Illuminate\Support\Str::limit($product->title, 35) }}</h6>
                        </a>
                        <p class="product-description-fixed">
                            {{ \Illuminate\Support\Str::limit($product->description ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 80) }}
                        </p>
                        <h4 class="product-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <p>No products found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
