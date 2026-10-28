@php
    $totalProducts = $products instanceof Illuminate\Pagination\LengthAwarePaginator ? $products->total() : $products->count();
    $currentCount = $products->count();
@endphp

<div class="products-filter-bar">
    <div class="filter-options">
        <span class="filter-label">@lang('ecommerce::ecommerce.sort_by_label')</span>
        <select class="form-select form-select-sm sort-select"
                style="width: auto;"
                onchange="updateMerchantSort(this.value)">
            <option value="latest" {{ request('sort_by', 'latest') == 'latest' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.newest')</option>
            <option value="best_selling" {{ request('sort_by') == 'best_selling' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.best_sellers')</option>
            <option value="top_rated" {{ request('sort_by') == 'top_rated' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.sort_top_rated')</option>
            <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_low_high')</option>
            <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_high_low')</option>
        </select>
    </div>
    <div class="product-count">
        @lang('ecommerce::ecommerce.showing_products_count', ['count' => $currentCount, 'total' => $totalProducts])
    </div>
</div>

@if($products->count() > 0)
    <div class="collection-product-wrapper">
        <div class="product-wrapper-grid pt-3">
            @include('ecommerce::pages.partials.product-grid', compact('products'))
        </div>
    </div>

    @if($products instanceof Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                           href="{{ $products->previousPageUrl() }}&sort_by={{ request('sort_by', 'latest') }}&category={{ request('category', 'all') }}"
                           up-target=".products-main"
                           up-scroll="false">
                            @lang('ecommerce::ecommerce.previous')
                        </a>
                    </li>

                    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                            <a class="page-link"
                               href="{{ $url }}&sort_by={{ request('sort_by', 'latest') }}&category={{ request('category', 'all') }}"
                               up-target=".products-main"
                               up-scroll="false">
                                {{ $page }}
                            </a>
                        </li>
                    @endforeach

                    <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link"
                           href="{{ $products->nextPageUrl() }}&sort_by={{ request('sort_by', 'latest') }}&category={{ request('category', 'all') }}"
                           up-target=".products-main"
                           up-scroll="false">
                            @lang('ecommerce::ecommerce.next')
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    @endif
@else
    <div class="col-12 text-center py-5">
        <div class="empty-state">
            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">@lang('ecommerce::ecommerce.no_products_title')</h4>
            <p class="text-muted">@lang('ecommerce::ecommerce.no_products_desc')</p>
        </div>
    </div>
@endif

<script>
function updateMerchantSort(sortBy) {
    console.log('🔧 updateMerchantSort called with:', sortBy);
    const loadingElement = document.getElementById('productsLoading');
    if (loadingElement) loadingElement.style.display = 'block';

    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', sortBy);

    up.navigate(url.toString(), {
        target: '.products-main',
        scroll: false
    });
}
</script>
