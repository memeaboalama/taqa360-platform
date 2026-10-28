<div class="collection-filter-block">
    <div class="collection-mobile-back">
        <span class="filter-back">
            <i class="fa fa-angle-left arrow-start" aria-hidden="true"></i>
            @lang('ecommerce::ecommerce.back')
        </span>
    </div>
    <div class="collection-collapse-block border-0 open mb-4">
        <h3 class="collapse-block-title">@lang('ecommerce::ecommerce.store_categories')</h3>
        <div class="collection-collapse-block-content">
            <div class="collection-brand-filter">
                <ul class="category-list list-unstyled">
                    <li>
                        <a href="javascript:void(0);"
                           class="category-filter {{ request('category', 'all') == 'all' ? 'active' : '' }}"
                           data-category="all">
                            @lang('admin::table.all') ({{ $categoryCounts['all'] ?? 0 }})
                        </a>
                    </li>
                    @foreach ($categories as $category)
                        <li>
                            <a href="javascript:void(0);"
                               class="category-filter {{ request('category') == $category->id ? 'active' : '' }}"
                               data-category="{{ $category->id }}">
                                {{ $category->title }} ({{ $categoryCounts[$category->id] ?? 0 }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="collection-filter-block">
    <h3 class="collapse-block-title">@lang('ecommerce::ecommerce.our_commitment')</h3>
    <div class="product-service mb-0">
        <div class="media d-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <div class="media-body">
                <h4>@lang('ecommerce::ecommerce.fast_shipping')</h4>
                <p>@lang('ecommerce::ecommerce.fast_shipping_desc')</p>
            </div>
        </div>
        <div class="media d-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            <div class="media-body">
                <h4>@lang('ecommerce::ecommerce.quality_products')</h4>
                <p>@lang('ecommerce::ecommerce.quality_products_desc')</p>
            </div>
        </div>
        <div class="media d-flex border-0 m-0 pb-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <div class="media-body">
                <h4>@lang('ecommerce::ecommerce.expert_support')</h4>
                <p>@lang('ecommerce::ecommerce.expert_support_desc')</p>
            </div>
        </div>
    </div>
</div>
