@php
    $presentation = \App\Application\Ecommerce\Support\ProductPagePresentation::make($product);
    $onSale = $product->isOnActiveSale();
    $currentPrice = $product->effective_price;
@endphp
<nav class="pp-breadcrumb" aria-label="{{ __('ecommerce::ecommerce.category') }}">
    <a href="{{ route('home') }}">@lang('ecommerce::ecommerce.home')</a>
    @foreach([$product->mainCategory, $product->subCategory, $product->childCategory] as $category)
        @if($category)<span aria-hidden="true">/</span><span>{{ $category->title }}</span>@endif
    @endforeach
</nav>

<div class="pp-hero">
    <section class="pp-visual" aria-label="{{ __('ecommerce::ecommerce.product_label') }}">
        <div class="pp-photo"><img id="product-main-image" src="{{ $presentation['images']->first() }}" alt="{{ $product->title }}" fetchpriority="high"></div>
        <div class="pp-thumbnails">
            @foreach($presentation['images'] as $imageUrl)
                <button type="button" class="pp-thumbnail {{ $loop->first ? 'active' : '' }}" data-product-image="{{ $imageUrl }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" aria-label="{{ __('ecommerce::product_page.product_image', ['number' => $loop->iteration]) }}">
                    <img src="{{ $imageUrl }}" alt="" loading="lazy">
                </button>
            @endforeach
        </div>
    </section>

    <section class="pp-info">
        @if($product->merchant?->brand)<div class="pp-brand">{{ $product->merchant->brand->getTranslation('name', app()->getLocale()) }}</div>@endif
        <h1>{{ $product->title }}</h1>
        <div class="pp-meta">
            <span class="pp-stock {{ $product->isInStock() ? '' : 'pp-stock-unavailable' }}">{{ __($product->isInStock() ? 'ecommerce::product_page.stock_available' : 'ecommerce::ecommerce.out_of_stock') }}</span>
            <span>{{ __('ecommerce::product_page.product_reference') }}: <bdi>#{{ $product->id }}</bdi></span>
        </div>
        @include('ecommerce::partials.product.rating', ['product' => $product, 'containerClass' => 'rating-row'])
        <p class="pp-description">{{ $product->description ?: __('ecommerce::ecommerce.no_detailed_description') }}</p>

        @if($presentation['highlights']->isNotEmpty())
            <div class="pp-highlights">
                @foreach($presentation['highlights'] as $field)
                    <div class="pp-highlight"><small>{{ $field->display_name }}</small><strong><bdi>{{ $field->display_value }}</bdi></strong></div>
                @endforeach
            </div>
        @endif

        <div class="pp-price-row">
            <strong>{{ \App\Domain\AppBase\Models\Setting\Currency::format($currentPrice) }}</strong>
            @if($onSale)
                <del>{{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price) }}</del>
                @if((float) $product->default_price > 0)<span class="pp-saving">-{{ round((1 - $currentPrice / $product->default_price) * 100) }}%</span>@endif
            @endif
        </div>

        <form id="product-add-cart-form" action="{{ route('cart.add') }}" method="POST" data-available="{{ $product->isInStock() ? '1' : '0' }}">
            @csrf
            <input type="hidden" name="item_id" value="{{ $product->id }}">
            <div class="pp-purchase-row">
                <div class="pp-quantity">
                    <button type="button" id="qty-minus" aria-label="@lang('ecommerce::ecommerce.decrease')">−</button>
                    <input type="number" dir="ltr" name="qty" id="qty-input" value="1" min="1" @if($product->stock !== null) max="{{ (int) $product->stock }}" @endif aria-label="{{ __('ecommerce::ecommerce.quantity') }}">
                    <button type="button" id="qty-plus" aria-label="@lang('ecommerce::ecommerce.increase')">+</button>
                </div>
                <button type="submit" class="pp-add-cart" id="add-to-cart-btn" @disabled(!$product->isInStock())><i class="ti-shopping-cart" aria-hidden="true"></i> @lang('ecommerce::ecommerce.add_to_cart')</button>
                <button type="button" class="wishlist-btn pp-favorite{{ $isFavorite ? ' is-favorite' : '' }}" id="product-wishlist-btn" data-product-id="{{ $product->id }}" aria-pressed="{{ $isFavorite ? 'true' : 'false' }}" aria-label="@lang('ecommerce::ecommerce.wishlist')">
                    <i class="{{ $isFavorite ? 'fa fa-heart' : 'ti-heart' }}" aria-hidden="true"></i>
                    <span class="wishlist-btn-text">@lang($isFavorite ? 'ecommerce::ecommerce.remove_from_wishlist' : 'ecommerce::ecommerce.add_to_wishlist')</span>
                </button>
            </div>
            <button type="submit" name="redirect_to_checkout" value="1" class="pp-buy-now" @disabled(!$product->isInStock())>@lang('ecommerce::ecommerce.buy_now')</button>
        </form>
        <div class="pp-delivery-note"><i class="fa fa-info-circle" aria-hidden="true"></i><span>@lang('ecommerce::product_page.delivery_note')</span></div>
        <div class="pp-seller">
            <span>@lang('ecommerce::product_page.sold_by')
                @if($product->merchant)<a href="{{ route('merchant.show', $product->merchant->id) }}">{{ $product->merchant->getTranslation('company_name', app()->getLocale()) ?: $product->merchant->name }}</a>
                @else<strong>{{ __('ecommerce::ecommerce.site_name') }}</strong>@endif
            </span><i class="fa fa-shield" aria-hidden="true"></i>
        </div>
    </section>
</div>

<nav class="pp-tabs" role="tablist" aria-label="{{ __('ecommerce::ecommerce.product_details') }}">
    @foreach(['about', 'specifications', 'service_prices', 'documents'] as $tab)
        @continue($tab === 'service_prices' && $product->activeItemPrices->isEmpty())
        <button type="button" role="tab" id="pp-tab-{{ $tab }}" data-product-tab="{{ $tab }}" aria-controls="pp-panel-{{ $tab }}" aria-selected="{{ $tab === 'specifications' ? 'true' : 'false' }}" tabindex="{{ $tab === 'specifications' ? '0' : '-1' }}" class="{{ $tab === 'specifications' ? 'active' : '' }}">{{ __('ecommerce::product_page.' . $tab) }}</button>
    @endforeach
</nav>
<div class="pp-spec-layout">
    <div class="pp-panels">
        <section id="pp-panel-about" role="tabpanel" aria-labelledby="pp-tab-about" hidden>
            <h2>@lang('ecommerce::product_page.about')</h2>
            <p class="pp-description">{{ $product->description ?: __('ecommerce::ecommerce.no_detailed_description') }}</p>
            @if($product->features)
                <h3>@lang('ecommerce::ecommerce.key_features')</h3>
                <ul class="pp-features">@foreach(explode(',', $product->features) as $feature)<li>{{ trim($feature) }}</li>@endforeach</ul>
            @endif
            @if($product->benefits)<h3>@lang('ecommerce::ecommerce.benefits')</h3><p class="pp-description">{{ $product->benefits }}</p>@endif
        </section>
        <section id="pp-panel-specifications" role="tabpanel" aria-labelledby="pp-tab-specifications">
            <div class="pp-spec-heading">
                <h2>@lang('ecommerce::product_page.specifications')</h2>
                <label class="pp-spec-search"><i class="fa fa-search" aria-hidden="true"></i><input type="search" data-spec-search placeholder="{{ __('ecommerce::product_page.search_specifications') }}" aria-label="{{ __('ecommerce::product_page.search_specifications') }}"></label>
            </div>
            @forelse($presentation['groups'] as $group => $fields)
                <section class="pp-spec-group" data-spec-group>
                    <h3>{{ $group ?: __('ecommerce::product_page.general_specifications') }}</h3>
                    <table><tbody>
                        @foreach($fields as $field)
                            <tr data-spec-row data-spec-search-text="{{ $field->display_name . ' ' . ($field->meta['name_ar'] ?? '') . ' ' . ($field->meta['name_en'] ?? '') . ' ' . $field->display_value }}"><th scope="row">{{ $field->display_name }}</th><td><bdi>{{ $field->display_value }}</bdi></td></tr>
                        @endforeach
                    </tbody></table>
                </section>
            @empty
                <p class="pp-empty">@lang('ecommerce::product_page.no_specifications')</p>
            @endforelse
            <p class="pp-empty" data-spec-no-matches role="status" hidden>@lang('ecommerce::product_page.no_matches')</p>
        </section>
        @if($product->activeItemPrices->isNotEmpty())
            <section id="pp-panel-service_prices" role="tabpanel" aria-labelledby="pp-tab-service_prices" hidden>
                @include('ecommerce::pages.product.partials.service-prices')
            </section>
        @endif
        <section id="pp-panel-documents" role="tabpanel" aria-labelledby="pp-tab-documents" hidden>
            <h2>@lang('ecommerce::product_page.documents')</h2>
            @include('ecommerce::pages.product.partials.documents')
        </section>
    </div>
    <aside class="pp-documents-aside" aria-label="{{ __('ecommerce::product_page.documents') }}">@include('ecommerce::pages.product.partials.documents')</aside>
</div>
