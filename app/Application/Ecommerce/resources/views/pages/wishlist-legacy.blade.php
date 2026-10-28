@extends('ecommerce::layout.layout')

@section('title', __('ecommerce::ecommerce.wishlist'))

@section('content')

<div class="bread_crumb shop_bread_crumb p-0">
    <div class="container">
        <div class="row">
            <div class="col-md-6 sec_low">
                <div class="functionalities">
                    <ul id="breadcrumb" class="breadcrumb">
                        <li><a title="@lang('ecommerce::ecommerce.home')" href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                        <li><strong><i class="fa fa-angle-double-right" aria-hidden="true"></i> @lang('ecommerce::ecommerce.wishlist')</strong></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-title">
                    <h2>@lang('ecommerce::ecommerce.wishlist')</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="wishlist-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @if($favorites->count() > 0)
                <table class="table cart-table table-responsive-xs">
                    <thead>
                    <tr class="table-head">
                        <th scope="col">@lang('ecommerce::ecommerce.image')</th>
                        <th scope="col">@lang('ecommerce::ecommerce.product_name')</th>
                        <th scope="col">@lang('ecommerce::ecommerce.price')</th>
                        {{-- <th scope="col">@lang('ecommerce::ecommerce.availability')</th> --}}
                        <th scope="col">@lang('ecommerce::ecommerce.action')</th>
                    </tr>
                    </thead>
                    @foreach($favorites as $favorite)
                    <tbody>
                    <tr class="wishlist-item-{{ $favorite->id }}">
                        <td>
                            <a href="{{ route('product.show', $favorite->item->id) }}" up-target=".main-body">
                                <img src="{{ $favorite->item->main_image['image_path'] ?? asset('assets/images/pro/1.jpg') }}" alt="{{ $favorite->item->title }}">
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('product.show', $favorite->item->id) }}" up-target=".main-body">{{ $favorite->item->title }}</a>
                            <div class="mobile-cart-content row">
                                <div class="col-xs-3">
                                    <p>{{ $favorite->item->stock > 0 ? __('ecommerce::ecommerce.in_stock') : __('ecommerce::ecommerce.out_of_stock') }}</p>
                                </div>
                                <div class="col-xs-3">
                                    <h2 class="td-color">${{ number_format($favorite->item->default_price, 2) }}</h2>
                                </div>
                                <div class="col-xs-3">
                                    <h2 class="td-color">
                                        <a href="{{ route('wishlist.add') }}"
                                           up-method="post"
                                           up-params='{"product_id": "{{ $favorite->item->id }}", "from_page": "wishlist"}'
                                           up-target=".wishlist-section"
                                           class="icon me-1">
                                            <i class="ti-close"></i>
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </td>
                        <td>
                            <h2>${{ number_format($favorite->item->default_price, 2) }}</h2>
                        </td>
                        {{-- <td>
                            <p>{{ $favorite->item->stock > 0 ? __('ecommerce::ecommerce.in_stock') : __('ecommerce::ecommerce.out_of_stock') }}</p>
                        </td> --}}
                        <td>
                            <a href="{{ route('wishlist.add') }}"
                               up-method="post"
                               up-params='{"product_id": "{{ $favorite->item->id }}", "from_page": "wishlist"}'
                               up-target=".wishlist-section"
                               class="icon me-3"
                               title="@lang('ecommerce::ecommerce.remove')">
                                <i class="ti-close"></i>
                            </a>

                            @if($favorite->item->stock > 0)
                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline-block" up-submit up-target=".cart-header">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $favorite->item->id }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="btn btn-solid btn-sm">
                                    <i class="ti-shopping-cart me-1"></i> @lang('ecommerce::ecommerce.add_to_cart')
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                    @endforeach
                </table>

                <div class="row wishlist-buttons">
                    <div class="col-12">
                        <a href="{{ route('home') }}" class="btn btn-solid">@lang('ecommerce::ecommerce.continue_shopping')</a>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $favorites->links() }}
                </div>

                @else
                <div class="col-sm-12 text-center section-b-space">
                    <img src="{{ asset('assets/ecommerce/images/empty-wishlist.png') }}" class="img-fluid mb-4" alt="Empty Wishlist" style="max-width: 200px;">
                    <h3>@lang('ecommerce::ecommerce.wishlist_empty')</h3>
                    <h4>@lang('ecommerce::ecommerce.explore_products')</h4>
                    <br>
                    <a href="{{ route('home') }}" class="btn btn-solid">@lang('ecommerce::ecommerce.shop_now')</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
