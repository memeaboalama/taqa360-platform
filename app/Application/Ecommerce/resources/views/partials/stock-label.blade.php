{{--
    Stock/availability label used across the static store pages so the
    availability state is identical everywhere (wishlist, product details,
    cart, product cards). Uses the same lang keys as the wishlist page.
    Expects: $item (Model with a `stock` attribute).
--}}
@php $stockAvailable = $item ? $item->isInStock() : false; @endphp
<span class="hululi-stock{{ $stockAvailable ? '' : ' out' }}">
    {{ $stockAvailable ? __('ecommerce::ecommerce.in_stock') : __('ecommerce::ecommerce.out_of_stock') }}
</span>