<div class="product-top-filter">
    <div class="row">
        <div class="col-xl-12">
            <div class="filter-main-btn">
                <span class="filter-btn btn btn-theme">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="product-filter-content">
                <div class="search-count">
                    <h5>
                        Showing Products
                        {{ $products->firstItem() ? $products->firstItem() : 0 }}-{{ $products->lastItem() ? $products->lastItem() : 0 }}
                        of {{ $products->total() }} Result
                        @if(request('q'))
                        for "{{ request('q') }}"
                        @endif
                    </h5>
                </div>
                <div class="product-page-per-view">
                    <select name="per_page"
                            up-autosubmit
                            up-delay="300"
                            up-target=".collection-product-wrapper"
                            up-scroll="false">
                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 Products Per Page</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 Products Per Page</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 Products Per Page</option>
                        <option value="96" {{ request('per_page') == 96 ? 'selected' : '' }}>96 Products Per Page</option>
                    </select>
                </div>
                <div class="product-page-filter">
                    <select name="sort_by"
                            up-autosubmit
                            up-delay="300"
                            up-target=".collection-product-wrapper"
                            up-scroll="false">
                        <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Name: A to Z</option>
                        <option value="popularity" {{ request('sort_by') == 'popularity' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>

                <!-- Active Filters -->
                @if(request()->anyFilled(['category', 'brands', 'price_ranges', 'colors', 'q']))
                <div class="active-filters">
                    <h6>Active Filters:</h6>
                    <div class="filter-tags">
                        @if(request('category') && request('category') !== 'all')
                            @php
                                $activeCategory = $categories->firstWhere('id', request('category'));
                            @endphp
                            @if($activeCategory)
                            <span class="filter-tag">
                                Category: {{ $activeCategory->title }}
                                <a href="#"
                                   onclick="removeFilter('category')"
                                   up-target=".collection-product-wrapper">
                                   &times;
                                </a>
                            </span>
                            @endif
                        @endif

                        @if(request('brands'))
                            @foreach(request('brands') as $brand)
                            <span class="filter-tag">
                                Brand: {{ $brand }}
                                <a href="#"
                                   onclick="removeArrayFilter('brands', '{{ $brand }}')"
                                   up-target=".collection-product-wrapper">
                                   &times;
                                </a>
                            </span>
                            @endforeach
                        @endif

                        @if(request('q'))
                        <span class="filter-tag">
                            Search: "{{ request('q') }}"
                            <a href="{{ route('categories.index') }}"
                               up-target=".main-content">
                               &times;
                            </a>
                        </span>
                        @endif

                        <a href="{{ route('categories.index') }}"
                           class="clear-all-filters"
                           up-target=".main-content">
                           Clear All
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function removeFilter(paramName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(paramName);
    up.navigate(url.toString(), { target: '.collection-product-wrapper' });
}

function removeArrayFilter(paramName, valueToRemove) {
    const url = new URL(window.location.href);
    const currentValues = url.searchParams.getAll(paramName + '[]');
    const newValues = currentValues.filter(val => val !== valueToRemove);

    url.searchParams.delete(paramName + '[]');
    newValues.forEach(val => url.searchParams.append(paramName + '[]', val));

    up.navigate(url.toString(), { target: '.collection-product-wrapper' });
}
</script>

<style>
.active-filters {
    margin-top: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}

.active-filters h6 {
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: bold;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.filter-tag {
    background: #007bff;
    color: white;
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-tag a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
}

.filter-tag a:hover {
    color: #ffdddd;
}

.clear-all-filters {
    color: #dc3545;
    font-size: 12px;
    text-decoration: none;
    margin-left: 10px;
}

.clear-all-filters:hover {
    text-decoration: underline;
}
</style>
