<style>
    /* Standard Grid Layout */
    .merchant-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        padding-bottom: 20px;
    }
    
    @media (max-width: 1400px) { .merchant-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 992px) { .merchant-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { 
        .merchant-grid { 
            grid-template-columns: repeat(2, 1fr); 
            gap: 10px; 
        } 
    }

    /* Ultra Compact Card */
    .merchant-profile-card {
        background: #fff;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.2s ease;
        overflow: hidden;
    }
    
    .merchant-profile-card:hover {
        border-color: var(--theme-color-1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    /* Compact Banner */
    .mp-banner {
        height: 55px;
        background-color: #f8f9fa;
        background-image: url("data:image/svg+xml,%3Csvg width='6' height='6' viewBox='0 0 6 6' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23e1e1e1' fill-opacity='0.4' fill-rule='evenodd'%3E%3Cpath d='M5 0h1L0 6V5zM6 5v1H5z'/%3E%3C/g%3E%3C/svg%3E");
        position: relative;
        border-bottom: 1px solid #eee;
    }

    /* Rating Badge */
    .mp-rating-box {
        position: absolute;
        top: 6px;
        right: 6px;
        background: #fff;
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        color: #ffa200;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        z-index: 2;
        border: 1px solid #eee;
    }
    [dir="rtl"] .mp-rating-box { right: auto; left: 6px; }

    /* Compact Logo */
    .mp-logo-wrapper {
        width: 50px;
        height: 50px;
        background: #fff;
        border-radius: 50%;
        padding: 3px;
        margin: -25px auto 0;
        position: relative;
        z-index: 3;
        border: 1px solid #eaeaea;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .mp-logo {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: contain;
        background: #fff;
    }

    /* Tighter Content */
    .mp-content {
        padding: 6px 10px 4px; /* Reduced bottom padding */
        text-align: center;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .mp-name-row {
        margin-top: 4px;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
    
    .mp-name {
        font-size: 14px;
        font-weight: 700;
        color: #222;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    
    .mp-verified-icon {
        color: #00baf2;
        font-size: 13px;
    }

    .mp-desc {
        font-size: 11px;
        color: #888;
        margin-bottom: 8px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 15px; 
    }

    /* Compact Stats Grid */
    .mp-info-grid {
        display: flex;
        border: 1px solid #f0f0f0;
        border-radius: 6px;
        background: #fdfdfd;
        margin-bottom: 8px;
        padding: 4px 0;
    }
    .mp-info-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-right: 1px solid #f0f0f0;
        line-height: 1.1;
    }
    .mp-info-col:last-child { border-right: none; }
    
    .mp-info-val { font-size: 12px; font-weight: 700; color: #333; }
    .mp-info-label { font-size: 9px; color: #999; margin-top: 2px; }

    /* Contacts - Small Text */
    .mp-contacts {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 10px;
        font-size: 11px;
        color: #666;
    }
    .mp-contact-row {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f9f9f9;
        padding: 4px 8px;
        border-radius: 4px;
        width: 100%;
        overflow: hidden;
    }
    .mp-contact-row i { color: #aaa; width: 12px; font-size: 10px; }
    .mp-contact-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: sans-serif;
    }

    /* Footer - Fixed Layout */
    .mp-footer {
        padding: 8px 10px;
        background: #fff;
        border-top: 1px solid #f5f5f5;
        margin-top: auto; /* Push to bottom */
    }

    .mp-btn-visit {
        width: 100%;
        height: 30px; /* Reduced specific height */
        background-color: #306FAA;
        color: #ffffff !important;
        border-radius: 4px; /* Standard radius */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700; /* Bold text */
        text-decoration: none;
        transition: all 0.2s;
        gap: 6px;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .mp-btn-visit:hover {
        background-color: #245C8E;
        color: #ffffff !important;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    [dir="rtl"] .mp-btn-visit i { transform: rotate(180deg); }


    .overlay-link { position: absolute; inset: 0; z-index: 1; }
    .mp-logo-wrapper, .mp-footer, .mp-contacts { position: relative; z-index: 2; }
    
    /* Mobile Adjustments */
    @media (max-width: 576px) {
        .mp-name { font-size: 13px; }
        .mp-banner { height: 50px; }
        .mp-logo-wrapper { width: 42px; height: 42px; margin-top: -21px; }
        .mp-content { padding: 6px 8px 4px; }
        .mp-footer { padding: 6px 8px; }
        .merchant-profile-card { border-radius: 6px; }
    }
</style>

@if($merchants->count() > 0)
    <div class="merchant-grid">
        @foreach($merchants as $merchant)
            @php
                $merchantName = $merchant->getTranslation('company_name', app()->getLocale())
                    ?: $merchant->getTranslation('company_name', 'en')
                    ?: $merchant->name;
            @endphp
            <div class="merchant-profile-card">
                <a href="{{ route('merchant.show', $merchant->id) }}" class="overlay-link" up-target=".main-body" up-preload></a>

                <div class="mp-banner">
                    @php $rating = $merchant->rate ?? 0; @endphp
                    @if($rating > 0)
                        <div class="mp-rating-box">
                            {{ $rating }} <i class="fa fa-star"></i>
                        </div>
                    @endif
                </div>

                <div class="mp-logo-wrapper">
                     <img src="{{ $merchant->logo ?? '' }}"
                         alt="{{ $merchantName }}"
                         class="mp-logo"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNmZmYiLz48dGV4dCB4PSI1MCIgeT0iNTAiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjQwIiBmaWxsPSIjZWRlZGVkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIj5NPC90ZXh0Pjwvc3ZnPg==';">
                </div>

                <div class="mp-content">
                    <div class="mp-name-row">
                        <span class="mp-name" title="{{ $merchantName }}">{{ $merchantName }}</span>
                        @if($merchant->is_verified)
                            <i class="fa fa-check-circle mp-verified-icon" title="@lang('ecommerce::ecommerce.verified')"></i>
                        @endif
                    </div>

                    <p class="mp-desc">
                         @php
                            $description = $merchant->description;
                            if (is_array($description)) {
                                $description = $merchant->getTranslation('description', app()->getLocale());
                            }
                        @endphp
                        {{ \Illuminate\Support\Str::limit($description ?? __('ecommerce::ecommerce.no_description'), 50) }}
                    </p>

                    <div class="mp-info-grid">
                        <div class="mp-info-col">
                            <span class="mp-info-val">{{ $merchant->items_count ?? $merchant->items()->count() }}</span>
                            <span class="mp-info-label">@lang('ecommerce::ecommerce.sales')</span>
                        </div>
                        <div class="mp-info-col">
                            <span class="mp-info-val">{{ $merchant->sales_count ?? 0 }}</span>
                            <span class="mp-info-label">@lang('ecommerce::ecommerce.sales')</span>
                        </div>
                    </div>

                    <div class="mp-contacts">
                        @if($merchant->phone)
                            <div class="mp-contact-row">
                                <i class="fa fa-phone"></i>
                                <span class="mp-contact-text num">{{ $merchant->phone }}</span>
                            </div>
                        @endif
                        @if($merchant->email)
                            <div class="mp-contact-row">
                                <i class="fa fa-envelope"></i>
                                <span class="mp-contact-text ltr">{{ $merchant->email }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mp-footer">
                    <a href="{{ route('merchant.show', $merchant->id) }}" class="mp-btn-visit" up-target=".main-body" up-preload>
                        @lang('ecommerce::ecommerce.visit_store') <i class="fa fa-arrow-right arrow-end"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    @if($merchants->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        <div class="pagination-wrapper">
             {{ $merchants->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
@else
    <div class="no-results-box">
        <div class="mb-3"><i class="fa fa-search fa-2x text-muted opacity-25"></i></div>
        <h6 class="fw-bold text-dark">@lang('ecommerce::ecommerce.products.no_results')</h6>
    </div>
@endif
