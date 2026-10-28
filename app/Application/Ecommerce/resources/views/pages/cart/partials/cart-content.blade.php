<style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}

input[type=number]:focus {
    box-shadow: none;
    outline: none;
}
</style>

<div class="cart-content">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" up-flash>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" up-flash>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($cart && $cart->cartItems->count() > 0)
    <div class="row">
        <div class="col-sm-12">
            <table class="table cart-table table-responsive-sm">
                <thead>
                <tr class="table-head">
                    <th scope="col">@lang('ecommerce::ecommerce.cart.image')</th>
                    <th scope="col">@lang('ecommerce::ecommerce.cart.product_name')</th>
                    <th scope="col">@lang('ecommerce::ecommerce.cart.price')</th>
                    <th scope="col">@lang('ecommerce::ecommerce.cart.quantity')</th>
                    <th scope="col">@lang('ecommerce::ecommerce.cart.action')</th>
                    <th scope="col">@lang('ecommerce::ecommerce.cart.total')</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($cart->cartItems as $item)
                    <tr>
                        <td>
                            <a href="{{ route('product.show', $item->item_id) }}" up-target=".main-body" up-preload>
                                <img src="{{ $item->item->image_path ?? asset('assets/images/pro/c1.jpg') }}"
                                     alt="{{ $item->item->title ?? 'Product' }}"
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/images/pro/c1.jpg') }}'">
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('product.show', $item->item_id) }}" up-target=".main-body" up-preload>
                                {{ $item->item->title ?? 'Product' }}
                            </a>
                        </td>
                        <td>
                            <h2>{{ \App\Domain\AppBase\Models\Setting\Currency::format($item->price) }}</h2>
                        </td>
                        <td>
                            <div class="qty-box">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <form method="POST" action="{{ route('cart.update-qty') }}"
                                              up-submit up-target=".cart-content, .cart-header-container"
                                              up-scroll="false" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                            <input type="hidden" name="qty" value="{{ $item->qty - 1 }}">
                                            <button type="submit"
                                                    class="btn qty-decrease {{ $item->qty <= 1 ? 'disabled' : '' }}"
                                                    {{ $item->qty <= 1 ? 'disabled' : '' }}>
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </span>
                                    <form method="POST" action="{{ route('cart.update-qty') }}"
                                          up-submit up-target=".cart-content, .cart-header-container"
                                          up-scroll="false" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                        <input type="number" dir="ltr"
                                               class="form-control input-number cart-quantity"
                                               name="qty"
                                               value="{{ $item->qty }}"
                                               min="1"
                                               up-autosubmit
                                               up-delay="500"
                                               up-target=".cart-content, .cart-header-container"
                                               up-scroll="false">
                                    </form>
                                    <span class="input-group-prepend">
                                        <form method="POST" action="{{ route('cart.update-qty') }}"
                                              up-submit up-target=".cart-content, .cart-header-container"
                                              up-scroll="false" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                            <input type="hidden" name="qty" value="{{ $item->qty + 1 }}">
                                            <button type="submit" class="btn qty-increase">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button type="button"
                                    class="icon item-remove btn-link p-0 border-0 bg-transparent"
                                    onclick="showConfirmationModal('{{ route('cart.remove') }}', '@lang('ecommerce::ecommerce.cart.confirm_remove')', '@lang('ecommerce::ecommerce.warning')', '@lang('ecommerce::ecommerce.delete')', 'POST', {item_id: '{{ $item->item_id }}'})">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>

                        </td>
                        <td>
                            <h2 class="td-color item-total" data-item-id="{{ $item->item_id }}">
                                {{ \App\Domain\AppBase\Models\Setting\Currency::format($item->subtotal) }}</h2>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table cart-table table-responsive-md">
                <tfoot>
                <tr>
                    <td>@lang('ecommerce::ecommerce.cart.total_price') :</td>
                    <td>
                        <h2 class="cart-total">{{ \App\Domain\AppBase\Models\Setting\Currency::format($cart->total) }}</h2>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row cart-buttons display-flex justify-centered-between">
        <div class="col-sm-6">
            <a class="theme_btn rtl_r" href="{{ route('home') }}">
                @lang('ecommerce::ecommerce.cart.continue_shopping')
                <span class="theme_btn_eff"></span>
            </a>


        </div>
        <div class="col-sm-6">
            <a class="theme_btn rtl_r" href="{{ route('checkout.index') }}" up-target=".main-body" up-preload>
                @lang('ecommerce::ecommerce.cart.checkout')
                <span class="theme_btn_eff"></span>
            </a>
            {{-- <form method="POST" action="{{ route('cart.clear') }}"
                  up-submit up-target=".cart-content, .cart-header-container"
                  up-scroll="false" class="d-inline">
                @csrf
                <button type="submit"
                        class="theme_btn btn-danger ms-2 cart-clear"
                        onclick="return confirm('@lang('ecommerce::ecommerce.cart.confirm_clear')')">
                    @lang('ecommerce::ecommerce.cart.clear_cart')
                </button>
            </form> --}}
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-sm-12 text-center">
            <div class="empty-cart">
                <i class="fa fa-shopping-cart fa-4x mb-4 text-muted"></i>
                <h3>@lang('ecommerce::ecommerce.cart.empty_cart')</h3>
                <p class="text-muted mb-4">@lang('ecommerce::ecommerce.cart.empty_cart_message')</p>
                <a href="{{ route('home') }}" class="theme_btn">
                    @lang('ecommerce::ecommerce.cart.start_shopping')
                    <span class="theme_btn_eff"></span>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('up:request:loaded', function(event) {
    const response = event.request.response;

    if (response && typeof response === 'object') {
        if (response.cart_content) {
            const cartContentElement = document.querySelector('.cart-content');
            if (cartContentElement) {
                cartContentElement.innerHTML = response.cart_content;
            }
        }

        if (response.cart_header) {
            const cartHeaderElement = document.querySelector('.cart-header-container');
            if (cartHeaderElement) {
                cartHeaderElement.innerHTML = response.cart_header;
            }
        }
    }
});

const style = document.createElement('style');
style.textContent = `
    .cart-quantity {
        transition: all 0.3s ease;
    }

    .cart-quantity:focus {
        background-color: #eef4fa;
        border-color: #306FAA;
    }

    .up-loading .cart-content {
        opacity: 0.7;
        pointer-events: none;
    }
`;
document.head.appendChild(style);
</script>
