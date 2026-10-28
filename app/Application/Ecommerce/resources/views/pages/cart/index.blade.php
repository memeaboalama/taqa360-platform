@extends('ecommerce::layout.layout')

@section('title', __('ecommerce::ecommerce.cart.shopping_cart'))

@section('content')

<div class="bread_crumb shop_bread_crumb">
    <div class="container">
        <div class="row">
            <div class="col-md-6 sec_low">
                <div class="functionalities">
                    <ul id="breadcrumb" class="breadcrumb">
                        <li>
                            <a title="Home" href="{{ route('home') }}">
                                <i class="fa fa-home" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <strong>
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                @lang('ecommerce::ecommerce.cart.shopping_cart')
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-title">
                    <h2>@lang('ecommerce::ecommerce.cart.shopping_cart')</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="cart-section section-b-space cmn_bg_yellow">
    <ul class="plus_decore">
        <li></li>
        <li></li>
    </ul>

    <div class="container">
        @include('ecommerce::pages.cart.partials.cart-content')
    </div>
</section>

@endsection
