@extends('ecommerce::layout.layout')

@section('title', $merchant->company_name)
@section('content')

<style>
.merchant-page-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 30px 0;
    margin-bottom: 40px;
}

.merchant-basic-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.merchant-logo {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid #007bff;
}

.merchant-main-info h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.merchant-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.merchant-verified {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #e8f5e8;
    color: #2e7d32;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
}

.merchant-rating {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.merchant-rating .rating .fa-star {
    font-size: 0.9rem;
}

.merchant-stats {
    display: flex;
    gap: 25px;
    margin-top: 15px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: #007bff;
    display: block;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.products-filter-bar {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-options {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0;
}

.product-count {
    color: #6c757d;
    font-size: 0.9rem;
}

.category-list a.active {
    color: #007bff;
    font-weight: 600;
    background: #f8f9fa;
    border-radius: 5px;
}

.category-list a {
    display: block;
    padding: 8px 12px;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.category-list a:hover {
    background: #f8f9fa;
    color: #007bff;
}

.loading-indicator {
    display: none;
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.loading-indicator.active {
    display: block;
}

.hidden-radio {
    display: none;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.merchant-product-card {
    height: 380px;
    display: flex;
    flex-direction: column;
    margin-bottom: 25px;
}

.merchant-product-image {
    width: 100%;
    height: 200px;
    object-fit: contain;
    background: #f8f9fa;
    padding: 10px;
}

.merchant-product-detail {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 15px 10px;
}

.merchant-product-title {
    height: 40px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 8px;
    font-size: 14px;
    line-height: 1.3;
    font-weight: 600;
}

.merchant-product-description {
    height: 36px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    font-size: 12px;
    color: #666;
    line-height: 1.4;
    margin-bottom: 10px;
}

.merchant-product-price {
    margin-top: auto;
    font-size: 16px;
    font-weight: bold;
    color: #306FAA;
}

.merchant-product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-box:hover .merchant-product-actions {
    opacity: 1;
}

[dir="rtl"] .merchant-product-actions {
    right: auto;
    left: 10px;
}

@media (max-width: 768px) {
    .merchant-page-header {
        padding: 20px 0;
    }

    .merchant-basic-info {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .merchant-logo {
        width: 70px;
        height: 70px;
    }

    .merchant-main-info h1 {
        font-size: 1.5rem;
    }

    .merchant-meta {
        justify-content: center;
    }

    .merchant-stats {
        justify-content: center;
        gap: 20px;
    }

    .products-filter-bar {
        flex-direction: column;
        align-items: flex-start;
    }

    .filter-options {
        width: 100%;
        justify-content: space-between;
    }

    .merchant-product-card {
        height: 360px;
    }

    .merchant-product-image {
        height: 180px;
    }
}

@media (max-width: 480px) {
    .merchant-stats {
        flex-wrap: wrap;
        gap: 15px;
    }

    .stat-item {
        flex: 1;
        min-width: 80px;
    }

    .filter-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .merchant-product-card {
        height: 340px;
    }

    .merchant-product-image {
        height: 160px;
    }
}
</style>

<div class="merchant-page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="merchant-basic-info">
                    @if($merchant->logo)
                    <img src="{{ $merchant->logo }}"
                         alt="{{ $merchant->company_name }}"
                         class="merchant-logo">
                    @endif
                    <div class="merchant-main-info">
                        <h1>{{ $merchant->company_name }}</h1>
                        <div class="merchant-meta">
                            @if($merchant->is_verified)
                            <span class="merchant-verified">
                                <i class="fa fa-check-circle"></i>
                                @lang('ecommerce::ecommerce.trusted_merchant')
                            </span>
                            @endif
                            <span class="merchant-rating">
                                <div class="rating">
                                    @php $rating = $merchant->rating ?? 4.5; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star{{ $i <= floor($rating) ? ' active' : ($i == ceil($rating) && !is_int($rating) ? '-half active' : '') }}"></i>
                                    @endfor
                                </div>
                                <span>{{ number_format($rating, 1) }} ({{ $merchant->reviews_count ?? 0 }} @lang('ecommerce::ecommerce.reviews'))</span>
                            </span>
                        </div>
                        <div class="merchant-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $categoryCounts['all'] ?? 0 }}</span>
                                <span class="stat-label">@lang('ecommerce::ecommerce.unit_product')</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $merchant->satisfaction_rate ?? 98 }}%</span>
                                <span class="stat-label">@lang('ecommerce::ecommerce.customer_satisfaction')</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $merchant->experience_years ?? 5 }}+</span>
                                <span class="stat-label">@lang('ecommerce::ecommerce.years_experience')</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $merchant->branches_count ?? 1 }}</span>
                                <span class="stat-label">@lang('ecommerce::ecommerce.branches')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-3 col-sm-12">
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
                            <form method="GET" action="{{ route('merchant.filter', $merchant) }}" id="merchant-filter-form">
                                @csrf
                                <ul class="category-list list-unstyled">
                                    <li>
                                        <a href="javascript:void(0)" data-target-radio="category_all" class="{{ request('category', 'all') == 'all' ? 'active' : '' }}">
                                            @lang('admin::lang.all') ({{ $categoryCounts['all'] ?? 0 }})
                                        </a>
                                    </li>
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="javascript:void(0)" data-target-radio="category_{{ $category->id }}" class="{{ request('category') == $category->id ? 'active' : '' }}">
                                                {{ $category->title }} ({{ $categoryCounts[$category->id] ?? 0 }})
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="hidden-radio">
                                    <input type="radio" id="category_all" name="category" value="all" {{ request('category', 'all') == 'all' ? 'checked' : '' }}>
                                    @foreach ($categories as $category)
                                        <input type="radio" id="category_{{ $category->id }}" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }}>
                                    @endforeach
                                </div>
                            </form>
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
        </div>

        <div class="col-lg-9 col-sm-12 products-main">

            <div class="products-filter-bar">
                <div class="filter-options">
                    <span class="filter-label">@lang('ecommerce::ecommerce.sort_by_label')</span>
                    <select class="form-select form-select-sm sort-select" name="sort_by" form="merchant-filter-form">
                        <option value="latest" {{ request('sort_by', 'latest') == 'latest' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.newest')</option>
                        <option value="best_selling" {{ request('sort_by') == 'best_selling' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.best_sellers')</option>
                        <option value="top_rated" {{ request('sort_by') == 'top_rated' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.sort_top_rated')</option>
                        <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_low_high')</option>
                        <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>@lang('ecommerce::ecommerce.price_high_low')</option>
                    </select>
                </div>
                <div class="product-count">
                    @php
                        $totalProducts = $products instanceof Illuminate\Pagination\LengthAwarePaginator ? $products->total() : $products->count();
                        $currentCount = $products->count();
                    @endphp
                    @lang('ecommerce::ecommerce.showing_products_count', ['count' => $currentCount, 'total' => $totalProducts])
                </div>
            </div>

            <div class="collection-product-wrapper">
                <div class="product-wrapper-grid pt-3">
                    @include('ecommerce::pages.partials.product-grid', compact('products'))
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeMerchantFilters();
});

function initializeMerchantFilters() {
    setupCategoryLinks();
    setupSortHandler();
    setupPerPageHandler();
}

function setupCategoryLinks() {
    document.querySelectorAll('.category-list a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetRadioId = this.getAttribute('data-target-radio');
            const targetRadio = document.getElementById(targetRadioId);

            if (targetRadio && !targetRadio.checked) {
                targetRadio.checked = true;

                document.querySelectorAll('.category-list a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');

                submitMerchantFilterForm();
            }
        });
    });
}

function setupSortHandler() {
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            submitMerchantFilterForm();
        });
    }
}

function setupPerPageHandler() {
    const perPageSelect = document.querySelector('select[name="per_page"]');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            submitMerchantFilterForm();
        });
    }
}

let merchantFilterTimeout;
let currentMerchantRequestController = null;

function submitMerchantFilterForm() {
    clearTimeout(merchantFilterTimeout);

    if (currentMerchantRequestController) {
        currentMerchantRequestController.abort();
    }

    showMerchantLoading();

    merchantFilterTimeout = setTimeout(() => {
        const form = document.getElementById('merchant-filter-form');
        if (!form) {
            return;
        }

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        const url = new URL(form.action);
        url.search = params.toString();

        currentMerchantRequestController = new AbortController();

        fetch(url.toString(), {
            method: 'GET',
            signal: currentMerchantRequestController.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            processMerchantFilterResponse(data);
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                showMessage(@json(__('ecommerce::ecommerce.update_products_error')), 'error');
            }
        })
        .finally(() => {
            hideMerchantLoading();
            currentMerchantRequestController = null;
        });
    }, 300);
}

function processMerchantFilterResponse(data) {
    const productGrid = document.querySelector('.product-wrapper-grid');
    if (productGrid && data.product_grid_html) {
        productGrid.innerHTML = data.product_grid_html;
    }

    if (data.new_url) {
        window.history.pushState({}, '', data.new_url);
    }

    initComponents(document.querySelector('.collection-product-wrapper'));
}

function showMerchantLoading() {
    const loadingElement = document.getElementById('productsLoading');
    if (loadingElement) {
        loadingElement.style.display = 'block';
    }

    document.querySelector('.product-wrapper-grid')?.classList.add('loading');
}

function hideMerchantLoading() {
    const loadingElement = document.getElementById('productsLoading');
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }

    document.querySelector('.product-wrapper-grid')?.classList.remove('loading');
}

function showMessage(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    document.querySelectorAll('.global-message').forEach(msg => msg.remove());

    const html = `
        <div class="alert ${alertClass} alert-dismissible fade show global-message"
             style="position: fixed; top: 20px; z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', html);

    setTimeout(() => {
        const msg = document.querySelector('.global-message');
        if (msg) msg.remove();
    }, 5000);
}

function initComponents(container = document) {
    initCart(container);
    initImages(container);
    initCarousels(container);
}

function initCart(container = document) {
    container.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.onsubmit = (e) => {
            e.preventDefault();
            addToCart(form);
        };
    });
}

function initImages(container = document) {
    container.querySelectorAll('img:not([data-processed])').forEach(img => {
        img.setAttribute('data-processed', 'true');
        const wrapper = img.closest('.img-wrapper');
        if (wrapper) wrapper.classList.add('loading');

        img.onload = function() {
            this.classList.add('loaded');
            if (wrapper) wrapper.classList.remove('loading');
        };
        img.onerror = function() {
            if (wrapper) {
                wrapper.classList.remove('loading');
                wrapper.classList.add('error');
            }
        };
        if (img.complete) {
            img.onload();
        }
    });
}

function initCarousels(container = document) {
    if (window.jQuery && jQuery.fn.owlCarousel) {
        jQuery(container).find('.owl-carousel:not(.owl-loaded)').each(function() {
            jQuery(this).owlCarousel({ });
        });
    }
}

function addToCart(form) {
    const btn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);

    if (btn) {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>...';
        btn.disabled = true;
    }

    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': getToken(), 'Accept': 'application/json' },
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
    })
    .then(data => {
        if (btn) {
            btn.innerHTML = @json(__('ecommerce::ecommerce.added'));
            setTimeout(() => {
                if (btn) {
                    btn.innerHTML = original;
                    btn.disabled = false;
                }
            }, 1000);
        }
        if (data.login_required) {
            showLoginMessage(data.message);
            return;
        }
        if (data.success) {
            if (data.cart_header) {
                updateHeader(data.cart_header);
            }
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        if (btn) {
            btn.innerHTML = original;
            btn.disabled = false;
        }
        showMessage(@json(__('ecommerce::ecommerce.add_error_short')), 'error');
    });
}

function updateHeader(html) {
    const header = document.querySelector('.cart-header-container');
    if (header && html) {
        header.innerHTML = html;
        setTimeout(() => initCart(header), 50);
    }
}

function getToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

function showLoginMessage(message) {
    if (confirm(message + '\n\n' + @json(__('ecommerce::ecommerce.go_to_login')))) {
        window.location.href = '/login';
    }
}

if (window.up) {
    up.compiler('.products-main', function(element) {
        initializeMerchantFilters();
    });

    up.compiler('.merchant-page-header', function(element) {
    });
}
</script>

@endsection
