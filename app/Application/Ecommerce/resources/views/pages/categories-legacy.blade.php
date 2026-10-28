@extends('ecommerce::layout.layout')

@section('title', $currentCategory ? $currentCategory->title : __('ecommerce::ecommerce.ecommerce.categories'))

@section('content')
<style>
    :root {
        --primary: #306FAA;
        --dark: #222;
        --border: #ececec;
    }

    .subcat-section { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid var(--border); }
    .subcat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px; }

    .subcat-card {
        display: flex; flex-direction: column; align-items: center; text-align: center;
        padding: 15px; border: 1px solid var(--border); border-radius: 8px;
        background: #fff; cursor: pointer; transition: 0.3s;
        text-decoration: none !important;
    }
    .subcat-card:hover, .subcat-card.active { border-color: var(--primary); transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .subcat-card.active h6 { color: var(--primary); }
    .subcat-card .img-wrap { width: 50px; height: 50px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; }
    .subcat-card .img-wrap img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .subcat-card h6 { font-size: 13px; margin: 0; font-weight: 600; color: var(--dark); }

    .sidebar-item { display: flex; justify-content: space-between; padding: 8px 10px; cursor: pointer; border-radius: 4px; color: #777; transition: 0.2s; }
    .sidebar-item:hover, .sidebar-item.active { background: #F1F6FB; color: var(--primary); }
    .count-badge { background: #eee; font-size: 10px; padding: 2px 6px; border-radius: 10px; }

    .up-active .collection-content { opacity: 0.6; pointer-events: none; }
</style>

<div class="category-page-container" up-main up-source="{{ request()->fullUrl() }}">
    <section class="section-b-space shop_page">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 collection-filter">
                        <form id="filter-form" method="GET" action="{{ route('categories.index') }}"
                            up-autosubmit up-target=".category-page-container" up-history="true" up-scrolldown="false">

                            <input type="hidden" id="search_input" name="q" value="{{ request('q') }}">
                            <input type="hidden" id="main_input" name="main_category" value="{{ $activeMainId ?? 'all' }}">

                            <div class="collection-filter-block sidebar">
                                <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left arrow-start"></i> @lang('ecommerce::ecommerce.back')</span></div>

                                <div class="collection-collapse-block open sidebar-block">
                                    <h3 class="collapse-block-title">
                                        {{ $activeSubId ? __('ecommerce::ecommerce.categories.sub_categories') : __('ecommerce::ecommerce.categories.categories') }}
                                    </h3>
                                    <div class="collection-collapse-block-content">
                                        <div class="collection-brand-filter">
                                            @if($childCategories->count() > 0)
                                            <ul class="sidebar-list p-0 m-0" style="list-style: none;">
                                                <li>
                                                    <label class="sidebar-item {{ !$activeChildId ? 'active' : '' }}" onclick="updateFilter('child', 'all')">
                                                        <span>@lang('ecommerce::ecommerce.all_categories')</span>
                                                        <input type="radio" name="child_category" value="all" class="d-none" {{ !$activeChildId ? 'checked' : '' }}>
                                                    </label>
                                                </li>
                                                @foreach($childCategories as $child)
                                                <li>
                                                    <label class="sidebar-item {{ $activeChildId == $child->id ? 'active' : '' }}" onclick="updateFilter('child', '{{ $child->id }}')">
                                                        <span>{{ Str::limit($child->title, 20) }}</span>
                                                        <span class="count-badge">{{ $child->items_count }}</span>
                                                        <input type="radio" name="child_category" value="{{ $child->id }}" class="d-none" {{ $activeChildId == $child->id ? 'checked' : '' }}>
                                                    </label>
                                                </li>
                                                @endforeach
                                            </ul>
                                            @else
                                                <p class="text-muted small text-center">No categories available.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="collection-collapse-block open sidebar-block">
                                    <h3 class="collapse-block-title">@lang('ecommerce::ecommerce.categories.price')</h3>
                                    <div class="collection-collapse-block-content">
                                        <div class="collection-brand-filter">
                                            @foreach(['hundred'=>'$10-$100', 'twohundred'=>'$100-$200', 'threehundred'=>'$200-$300', 'fourhundredabove'=>'$400+'] as $key => $label)
                                            <div class="custom-control custom-checkbox collection-filter-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="{{ $key }}" name="price_ranges[]" value="{{ $key }}" {{ in_array($key, (array)request('price_ranges')) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="{{ $key }}">{{ $label }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-none">
                                <input type="radio" name="sub_category" value="all" id="sub_all" {{ !$activeSubId ? 'checked' : '' }}>
                                @foreach($subCategories as $sub)
                                <input type="radio" name="sub_category" value="{{ $sub->id }}" id="sub_{{ $sub->id }}" {{ $activeSubId == $sub->id ? 'checked' : '' }}>
                                @endforeach
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-9 collection-content">
                        <div class="page-main-content">

                            <div class="mb-4">
                                @if($currentCategory)
                                    <h2 class="font-weight-bold">{{ $currentCategory->title }}</h2>
                                    @if($currentCategory->description)<p class="text-muted">{{ $currentCategory->description }}</p>@endif
                                @elseif(request()->filled('q'))
                                    <h2 class="font-weight-bold">@lang('ecommerce::ecommerce.search_results_for'): "{{ request('q') }}"</h2>
                                @else
                                    <h2 class="font-weight-bold">@lang('ecommerce::ecommerce.shop_all')</h2>
                                @endif
                            </div>

                            @if($subCategories->count() > 0)
                            <div class="subcat-section">
                                <div class="subcat-grid">
                                    <a class="subcat-card {{ !$activeSubId ? 'active' : '' }}" onclick="updateFilter('sub', 'all')">
                                        <div class="img-wrap"><i class="fa fa-th text-muted"></i></div>
                                        <h6>@lang('ecommerce::ecommerce.all_categories')</h6>
                                    </a>
                                    @foreach($subCategories as $sub)
                                    <a class="subcat-card {{ $activeSubId == $sub->id ? 'active' : '' }}" onclick="updateFilter('sub', '{{ $sub->id }}')">
                                        <div class="img-wrap">
                                            @if($sub->image) <img src="{{ $sub->image }}"> @else <i class="fa fa-folder-open text-muted"></i> @endif
                                        </div>
                                        <h6>{{ Str::limit($sub->title, 15) }}</h6>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="collection-product-wrapper">
                                {{-- <div class="product-top-filter">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="product-filter-content">
                                                <div class="search-count">
                                                    <h5>Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</h5>
                                                </div>
                                                <div class="product-page-filter">
                                                    <select name="sort_by" form="filter-form">
                                                        <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                                                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low-High</option>
                                                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High-Low</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="product-wrapper-grid pt-3">
                                    @if($products->count() > 0)
                                        @include('ecommerce::pages.partials.product-grid', compact('products'))
                                    @else
                                        <div class="text-center py-5 w-100">
                                            <div class="mb-3"><i class="fa fa-search fa-3x text-muted"></i></div>
                                            <h4>@lang('ecommerce::ecommerce.products.no_results')</h4>
                                            <p class="text-muted">@lang('ecommerce::ecommerce.products.different_keywords')</p>
                                        </div>
                                    @endif
                                </div>
                                {{-- <div class="product-pagination mt-4">
                                    {{ $products->appends(request()->input())->links('pagination::bootstrap-4') }}
                                </div> --}}
                                @if($products->hasPages())
                                    <div class="pagination_sec">
                                        <div class="content_detail__pagination cdp">
                                            {{ $products?->links('vendor.pagination.custom') }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

    if (typeof up !== 'undefined') {
        up.compiler('.pagination a', function(link) {
            link.setAttribute('up-target', '.category-page-container');
            link.setAttribute('up-history', 'true');
            // link.setAttribute('up-scroll', 'false');
        });
    }

    function updateFilter(type, value) {
        const searchInput = document.getElementById('search_input');

        if (type === 'sub') {
            if(searchInput) searchInput.value = '';

            const childAll = document.querySelector('input[name="child_category"][value="all"]');
            if(childAll) childAll.checked = true;
            const targetSub = document.getElementById(value === 'all' ? 'sub_all' : 'sub_' + value);
            if(targetSub) targetSub.checked = true;

        } else if (type === 'child') {
            if(searchInput) searchInput.value = '';

            const targetChild = document.querySelector(`input[name="child_category"][value="${value}"]`);
            if(targetChild) targetChild.checked = true;
        }

        up.submit('#filter-form');
    }
</script>
@endsection
