@props(['product'])

<div class="media d-flex">
    <a href="{{ route('product.show', $product->id) }}"><img class="img-fluid" src="{{ $product->image_path }}" alt="{{ $product->title }}"></a>
    <div class="media-body align-self-center">
        <div class="rating">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </div>
        <a href="{{ route('product.show', $product->id) }}"><h6>{{ $product->title }}</h6></a>
        <h4>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</h4>
    </div>
</div>
