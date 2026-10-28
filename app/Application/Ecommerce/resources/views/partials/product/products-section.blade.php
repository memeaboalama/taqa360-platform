<div class="row">
    <div class="col-sm-12">
        @if(request()->routeIs('categories.search') && request('q'))
        <div class="top-banner-wrapper">
            <div class="top-banner-content small-section">
                <h4>Search Results</h4>
                <h5>Found {{ $products->total() }} results for "{{ request('q') }}"</h5>
                <a href="{{ route('categories.index') }}"
                   class="btn btn-theme btn-sm"
                   up-target=".main-content"
                   up-history="true">
                   View All Products
                </a>
            </div>
        </div>
        @elseif(isset($currentCategory) && $currentCategory)
        <div class="top-banner-wrapper">
            <div class="top-banner-content small-section">
                <h4>{{ $currentCategory->title }}</h4>
                <p>{{ $currentCategory->description ?? 'Explore our collection of ' . $currentCategory->title }}</p>
            </div>
        </div>
        @else
        <div class="top-banner-wrapper">
            <a href="#"><img src="{{ asset('assets/images/side-banner.jpg') }}" class="img-fluid" alt=""></a>
            <div class="top-banner-content small-section">
                <h4>All Products</h4>
                <h5>Browse our complete collection</h5>
                <p>Discover our wide range of products across all categories.</p>
            </div>
        </div>
        @endif

        <div class="collection-product-wrapper" up-scroll="false">
            @include('ecommerce::partials.product.products-toolbar')
            @include('ecommerce::partials.product.products-grid')
        </div>
    </div>
</div>
