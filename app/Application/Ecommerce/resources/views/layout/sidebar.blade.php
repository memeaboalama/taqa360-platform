<style>

</style>
@php
    $sidebarBanner = $sidebarBanner ?? collect();
    $sidebarBanner2 = $sidebarBanner2 ?? collect();
    $newProductsSidebar = $newProductsSidebar ?? collect();
    $recentlyViewed = $recentlyViewed ?? collect();
    $suggestedProducts = $suggestedProducts ?? collect();
    $services = $services ?? collect();
@endphp

<div class="col-lg-3 sidebar ecommerce-sidebar" >

    <div class="theme-card hanger">
        <div class="stiky angle-b-left"></div>
        <div class="stiky angle-b-right"></div>
        <div class="collection-filter-block cat-block">
            {{-- <div class="title">
                <h4>@lang('ecommerce::ecommerce.sidebar.categories')</h4>
            </div> --}}
            <ul>
                @foreach($categories as $category)
                    <li>
                        <a href="{{ route('categories.index', ['main_category' => $category->id]) }}"
                           up-target=".main-content" up-preload up-instant up-scroll="false">
                            <i class="ti-briefcase"></i>{{ $category->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- New Products -->
        <div class="col-md-12 col-sm-6">
            <div class="theme-card">
                <h5 class="title-border">@lang('ecommerce::ecommerce.sidebar.new_products')</h5>
                <div class="offer-slider small_slider collection-filter-block owl-carousel owl-theme">
                    @foreach($newProductsSidebar->chunk(3) as $chunk)
                        <div class="item">
                            @foreach($chunk as $product)
                            <div class="media d-flex">
                                <a href="#"
                                    data-quick-view="{{ route('quick-view', $product->id) }}"
                                    up-follow="false">
                                    <img class="img-fluid"
                                        src="{{ $product->image_path ?? 'assets/images/ecommerce/Product/1.jpg' }}"
                                        alt="{{ $product->title }}">
                                </a>
                                <div class="media-body align-self-center" @if($isRTL) style="margin-right: 10px;" @endif>
                                    <div class="rating">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fa fa-star{{ $i < ($product->rating ?? 5) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <a href="{{ route('product.show', $product->id) }}" up-target=".main-body"
                                        up-preload>
                                        <h6>{{ \Illuminate\Support\Str::limit($product->title, 20) }}</h6>
                                    </a>
                                    <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recently Viewed -->
        <div class="col-md-12 col-sm-6">
            <div class="theme-card">
                <h5 class="title-border">@lang('ecommerce::ecommerce.sidebar.recently_viewed')</h5>
                <div class="offer-slider small_slider collection-filter-block owl-carousel owl-theme">
                    @foreach($recentlyViewed->chunk(3) as $chunk)
                        <div class="item">
                            @foreach($chunk as $product)
                            <div class="media d-flex">
                                <a href="#"
                                    data-quick-view="{{ route('quick-view', $product->id) }}"
                                    up-follow="false">
                                    <img class="img-fluid"
                                        src="{{ $product->image_path ?? 'assets/images/ecommerce/Product/1.jpg' }}"
                                        alt="{{ $product->title }}">
                                </a>
                                <div class="media-body align-self-center" @if($isRTL) style="margin-right: 10px;" @endif>
                                    <div class="rating">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fa fa-star{{ $i < ($product->rating ?? 5) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <a href="{{ route('product.show', $product->id) }}" up-target=".main-body"
                                        up-preload>
                                        <h6>{{ \Illuminate\Support\Str::limit($product->title, 20) }}</h6>
                                    </a>
                                    <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-12 col-6 set-order-md-5 mb-3">
            @if(isset($sidebarBanner) && $sidebarBanner->isNotEmpty())
                @php $banner = $sidebarBanner->first(); @endphp
                <a href="{{ $banner->click_url ?: '#' }}" class="d-block">
                <div class="side-banner">
                    <img src="{{ $banner->desktop_image_url ?: asset('assets/images/ecommerce/Side-banner/1.png') }}" class="bg-img bg-bottom" alt="{{ $banner->localized_title }}">
                    <div class="side-banner-text">
                        <h6>{{ $banner->localized_subtitle ?: __('ecommerce::ecommerce.sidebar.discount_offer') }}</h6>
                        <h4>{{ $banner->localized_title ?: __('ecommerce::ecommerce.sidebar.latest_tools') }}</h4>
                    </div>
                </div>
                </a>
            @else
                <div class="side-banner">
                    <img src="{{ asset('assets/images/ecommerce/Side-banner/1.png') }}" class="bg-img bg-bottom" alt="">
                    <div class="side-banner-text">
                        <h6>@lang('ecommerce::ecommerce.sidebar.discount_offer')</h6>
                        <h4>@lang('ecommerce::ecommerce.sidebar.latest_tools')</h4>
                    </div>
                </div>
            @endif
        </div>



        <!-- You May Interested -->
        <div class="col-md-12 col-sm-12">
            <div class="theme-card">
                <h5 class="title-border">@lang('ecommerce::ecommerce.sidebar.you_may_interested')</h5>
                <div class="offer-slider small_slider collection-filter-block owl-carousel owl-theme">
                    @foreach($suggestedProducts->chunk(3) as $chunk)
                        <div class="item">
                            @foreach($chunk as $product)
                                <div class="media d-flex">
                                    <a href="#"
                                    data-quick-view="{{ route('quick-view', $product->id) }}"
                                    up-follow="false">
                                        <img class="img-fluid"
                                            src="{{ $product->image_path ?? 'assets/images/ecommerce/Product/1.jpg' }}"
                                            alt="{{ $product->title }}">
                                    </a>
                                    <div class="media-body align-self-center" @if($isRTL) style="margin-right: 10px;" @endif>
                                        <div class="rating">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fa fa-star{{ $i < ($product->rating ?? 5) ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <a href="{{ route('product.show', $product->id) }}" up-target=".main-body"
                                            up-preload>
                                            <h6>{{ \Illuminate\Support\Str::limit($product->title, 20) }}</h6>
                                        </a>
                                        <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Banner 2 -->
        <div class="col-md-12 col-6 mb-3">
            @if(isset($sidebarBanner2) && $sidebarBanner2->isNotEmpty())
                @php $banner2 = $sidebarBanner2->first(); @endphp
                <a href="{{ $banner2->click_url ?: '#' }}" class="d-block">
                <div class="side-banner">
                    <img src="{{ $banner2->desktop_image_url ?: asset('assets/images/ecommerce/Side-banner/1.png') }}" class="bg-img bg-bottom" alt="{{ $banner2->localized_title }}">
                    <div class="side-banner-text">
                        <h6>{{ $banner2->localized_subtitle ?: __('ecommerce::ecommerce.sidebar.discount_offer') }}</h6>
                        <h4>{{ $banner2->localized_title ?: __('ecommerce::ecommerce.sidebar.latest_tools') }}</h4>
                    </div>
                </div>
                </a>
            @else
                <div class="side-banner">
                    <img src="{{ asset('assets/images/ecommerce/Side-banner/1.png') }}" class="bg-img bg-bottom" alt="">
                    <div class="side-banner-text">
                        <h6>@lang('ecommerce::ecommerce.sidebar.discount_offer')</h6>
                        <h4>@lang('ecommerce::ecommerce.sidebar.latest_tools')</h4>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Our Service Start -->
        @if(isset($services) && $services->isNotEmpty())
        <div class="ecommerce">
            <div class="title">
                <h2>@lang('ecommerce::ecommerce.sidebar.our_service')</h2>
            </div>
            <div class="collection-filter-block">
                <div class="product-service row">
                    @foreach($services->take(4) as $service)
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="media d-flex {{ $loop->last ? 'border-0 m-0' : '' }}">
                            @if($service->desktop_image_url)
                                <img src="{{ $service->desktop_image_url }}" alt="{{ $service->localized_title }}" style="max-width: 50px;">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -117 679.99892 679" style="max-width: 50px;">
                                    <path d="m12.347656 378.382812h37.390625c4.371094 37.714844 36.316407 66.164063 74.277344 66.164063 37.96875 0 69.90625-28.449219 74.28125-66.164063h241.789063c4.382812 37.714844 36.316406 66.164063 74.277343 66.164063..." fill="#306FAA"></path>
                                </svg>
                            @endif
                            <div class="media-body" @if($isRTL) style="margin-right: 15px;" @endif>
                                <h4>{{ $service->localized_title }}</h4>
                                <p>{{ $service->localized_subtitle }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        <!-- Our Service end -->

    <!-- Testimonial Start -->
    {{-- <div class="theme-card">
        <h5 class="title-border">@lang('ecommerce::ecommerce.sidebar.testimonial')</h5>
        <div class="testimonial home_2_testimonial">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="testimonial_slide owl-carousel owl-theme">
                            @foreach($testimonials as $testimonial)
                                <div class="testimonial_sec">
                                    <div>
                                        <img src="{{ asset($testimonial['image']) }}" alt="{{ $testimonial['name'] }}">
                                        <p>
                                            <span class="quote_left">
                                                <i class="fa fa-quote-left" aria-hidden="true"></i>
                                            </span>
                                            {{ $testimonial['content'] }}
                                            <span class="quote_right">
                                                <i class="fa fa-quote-right" aria-hidden="true"></i>
                                            </span>
                                        </p>
                                        <h3>{{ $testimonial['name'] }}</h3>
                                        <h5>{{ $testimonial['position'] }}</h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Testimonial End -->

</div>
