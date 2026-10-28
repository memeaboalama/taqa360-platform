<section class="product_sec pb-0">
    <div class="collection-product-wrapper">
        <div class="row product-wrapper-grid f_product">
            <div class="col-12">
                <div class="title">
                    <h2>{{ $placement->getTranslation('name', app()->getLocale()) }}</h2>
                </div>
            </div>
            <div class="col-12">
                <div class="product-5 product-m no-arrow">
                     @foreach($placement->activeProductContents as $content)
                         @if($product = $content->product)
                             @include('ecommerce::components.product-card', ['product' => $product])
                         @endif
                     @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
