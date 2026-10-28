<style>
    .cart-box.cart-header {
        position: relative;
        overflow: visible;
    }

    .cart-box.cart-header .shopping-cart {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        left: auto;
        z-index: 1050;
        width: min(320px, calc(100vw - 20px));
        max-height: min(70vh, 420px);
        overflow-y: auto;
        overflow-x: hidden;
        background: #fff;
        border: 1px solid #e9edf1;
        border-radius: 12px;
        box-shadow: 0 16px 40px rgba(17, 24, 39, 0.12);
        list-style: none;
        margin: 0;
        padding: 0;
    }

    @media (max-width: 767px) {
        .cart-box.cart-header {
            position: static;
        }

        .cart-box.cart-header .shopping-cart {
            left: 50%;
            right: auto;
            transform: translateX(-50%);
            width: min(300px, calc(100vw - 18px));
        }
    }
</style>

<div class="cart-box cart-header">
    <i class="ti-shopping-cart-full"></i>
    <div class="logo_content">
        <h5 class="title">@lang('ecommerce::ecommerce.cart.shopping_cart')</h5>
        @php $cart = auth()->check() ? auth()->user()->webCart : null; @endphp
        @if($cart && $cart->cartItems->count() > 0)
            <h6>{{ $cart->cartItems->count() }} @lang('ecommerce::ecommerce.cart.items') - {{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->total) }}</h6>
        @else
            <h6>0 @lang('ecommerce::ecommerce.cart.items') - $0.00</h6>
        @endif
    </div>
    <ul class="shopping-cart">
        @if($cart && $cart->cartItems->count() > 0)
            @foreach($cart->cartItems->take(3) as $item)
            <li>
                <div class="media d-flex">
                    <a href="{{ route('product.show', $item->item_id) }}" up-target=".main-body">
                        <img class="me-3" src="{{ $item->item->image_path ?? asset('assets/images/ecommerce/Product/1.jpg') }}"
                            alt="{{ $item->item->title ?? 'Product' }}" style="width: 50px; height: 50px; object-fit: cover;"
                            onerror="this.src='{{ asset('assets/images/ecommerce/Product/1.jpg') }}'">
                    </a>
                    <div class="media-body">
                        <a href="{{ route('product.show', $item->item_id) }}" up-target=".main-body">
                            <h5>{{ \Illuminate\Support\Str::limit($item->item->title ?? 'Product', 20) }}</h5>
                        </a>
                        <h5><span> {{ \App\Domain\AppBase\Models\Setting\Currency::format($item->price) }}x{{ $item->qty }} </span></h5>
                    </div>
                </div>
                <div class="close-circle">
                    <a href="{{ route('cart.remove') }}" class="remove-item" 
                       up-target="body"
                       up-method="POST" 
                       up-params='{"item_id": "{{ $item->item_id }}"}'
                       up-confirm="@lang('ecommerce::ecommerce.cart.confirm_remove')">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </li>
            @endforeach
            <li>
                <div class="total">
                    <h5>@lang('ecommerce::ecommerce.cart.total') : <span>{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->total) }}</span></h5>
                </div>
            </li>
            <li>
                <div class="buttons">
                    <a href="{{ route('cart.index') }}" class="view-cart" up-target=".main-body">
                        @lang('ecommerce::ecommerce.cart.view_cart')
                    </a>
                    <a href="{{ route('checkout.index') }}" class="checkout" up-target=".main-body">
                        @lang('ecommerce::ecommerce.cart.checkout')
                    </a>
                </div>
            </li>
        @else
            <li>
                <div class="empty-cart text-center p-3">
                    <i class="fa fa-shopping-cart fa-2x mb-2 text-muted"></i>
                    <p class="mb-0">@lang('ecommerce::ecommerce.cart.empty_cart')</p>
                </div>
            </li>
        @endif
    </ul>
    @if(!empty($conflictScript))
        {!! $conflictScript !!}
    @endif
</div>

@if(isset($flash))
    <script>showNotification("{{ $flash['message'] }}", "{{ $flash['type'] }}");</script>
@endif
