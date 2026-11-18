@extends('ecommerce::layout.store-layout')

@section('title', $sectionTitle)
@section('active-nav', 'home')

@section('page-styles')
    <style>
        .collection-page {
            padding: 28px 0 56px
        }

        .collection-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px
        }

        .collection-heading h1 {
            margin: 0;
            padding-inline-start: 12px;
            border-inline-start: 3px solid var(--yellow);
            font-size: 22px
        }

        .collection-back {
            color: var(--yellow-deep);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none
        }

        .collection-products {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px
        }

        .collection-product-card {
            position: relative;
            min-width: 0;
            padding: 12px 13px 10px;
            border: 1px solid #e5e9ed;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 3px 12px rgba(29, 37, 48, .07)
        }

        .collection-product-image-link {
            display: flex;
            height: 160px;
            align-items: center;
            justify-content: center
        }

        .collection-product-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain
        }

        .collection-product-card h2 {
            margin: 6px 0 4px;
            font-size: 14px;
            line-height: 1.5
        }

        .collection-product-card h2 a {
            color: inherit;
            text-decoration: none
        }

        .collection-product-card .rating {
            color: #EA993E;
            font-size: 12px;
            direction: ltr;
            unicode-bidi: isolate
        }

        .collection-product-card .price {
            margin-top: 5px;
            font-size: 14px;
            font-weight: 700;
            direction: ltr;
            unicode-bidi: isolate
        }

        [dir="rtl"] .collection-product-card .rating,
        [dir="rtl"] .collection-product-card .price {
            text-align: right
        }

        .collection-favorite {
            position: absolute;
            z-index: 2;
            top: 8px;
            left: 8px
        }

        .collection-favorite .wishlist-form {
            margin: 0
        }

        .collection-favorite .add_wish {
            display: inline-flex;
            position: static;
            width: 25px;
            height: 25px;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: #fff;
            color: #89929b;
            font-size: 15px
        }

        .collection-cart-form {
            margin-top: 8px
        }

        .collection-cart-form button {
            width: 100%;
            min-height: 36px;
            border-radius: 18px;
            background: var(--yellow);
            color: #fff;
            font-size: 12px;
            font-weight: 700
        }

        .collection-pagination {
            margin-top: 24px
        }

        .collection-empty {
            padding: 35px 16px;
            color: var(--muted);
            text-align: center
        }

        @media (max-width: 800px) {
            .collection-products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px
            }

            .collection-product-card {
                padding: 9px
            }

            .collection-product-image-link {
                height: 130px
            }
        }
    </style>
@endsection

@section('content')
    <div class="container collection-page">
        <div class="collection-heading">
            <h1>{{ $sectionTitle }}</h1>
            <a class="collection-back" href="{{ route('ecommerce.static') }}">@lang('ecommerce::ecommerce.home')</a>
        </div>

        @if($products->isNotEmpty())
            <div class="collection-products">
                @foreach($products as $product)
                    @php
                        $productTitle = $product->title;
                        if (is_array($productTitle)) {
                            $productTitle = $productTitle[$currentLocale] ?? ($productTitle['en'] ?? reset($productTitle));
                        }
                        $productTitle = $productTitle ?: __('ecommerce::ecommerce.product_label');
                    @endphp
                    <article class="collection-product-card">
                        <div class="collection-favorite">
                            @include('ecommerce::partials.product.wishlist-btn', ['product' => $product])
                        </div>
                        <a class="collection-product-image-link" href="{{ route('product.static', $product->id) }}">
                            <img class="collection-product-image"
                                 src="{{ $product->image_path ?: ($product->image ?: asset('assets/ecommerce/images/slider/4.webp')) }}"
                                 alt="{{ $productTitle }}" loading="lazy">
                        </a>
                        @if(!$product->isInStock())
                            <span class="product-stock-badge">@lang('ecommerce::ecommerce.out_of_stock')</span>
                        @endif
                        <h2>
                            <a href="{{ route('product.static', $product->id) }}">{{ $productTitle }}</a>
                        </h2>
                        @include('ecommerce::partials.product.rating', ['product' => $product])
                        <div class="price">
                            {{ \App\Domain\AppBase\Models\Setting\Currency::format($product->default_price ?? $product->price ?? 0) }}
                        </div>
                        <form class="collection-cart-form" action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" @disabled(!$product->isInStock())>
                                @if($product->isInStock())
                                    @lang('ecommerce::ecommerce.add_to_cart')
                                @else
                                    @lang('ecommerce::ecommerce.out_of_stock')
                                @endif
                            </button>
                        </form>
                    </article>
                @endforeach
            </div>
            <div class="collection-pagination">{{ $products->links() }}</div>
        @else
            <div class="collection-empty">@lang('ecommerce::ecommerce.products.no_products_found')</div>
        @endif
    </div>
@endsection
