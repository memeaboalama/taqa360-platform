<div class="theme-card">
    <h2>{{ $placement->getTranslation('name', app()->getLocale()) }}</h2>
    <div class="offer-slider small_slider collection-filter-block owl-carousel owl-theme">
        @foreach($placement->activeBanners->chunk(3) as $chunk)
            <div class="item">
                @foreach($chunk as $banner)
                    @if($product = $banner->product)
                        @include('ecommerce::components.sidebar-product-card', ['product' => $product])
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>
