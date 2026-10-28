<div id="product-grid-{{ $placement->id }}" class="product-5 product-m no-arrow">
     @foreach($products as $product)
         @include('ecommerce::components.product-card', ['product' => $product])
     @endforeach
</div>
