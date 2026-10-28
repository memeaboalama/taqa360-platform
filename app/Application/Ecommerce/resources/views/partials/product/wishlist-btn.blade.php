@props(['product', 'displayText' => false, 'style' => 'link'])

@php
    $isFavorite = auth()->check() && \App\Domain\MarketPlace\Models\Favorite::where('user_id', auth()->id())
        ->where('item_id', $product->id)
        ->exists();

    $btnId = 'wishlist-btn-' . $product->id;
@endphp

@auth
    @if($style == 'btn')
        <form class="wishlist-form" method="POST" action="{{ route('wishlist.add') }}"
              up-submit
              up-target="#{{ $btnId }}, #wishlist-count"
              up-scroll="false">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="style" value="{{ $style }}">
            <input type="hidden" name="displayText" value="{{ $displayText }}">
            <button type="submit" class="theme_btn in-favorites" id="{{ $btnId }}">
                <i class="{{ $isFavorite ? 'fa fa-heart' : 'ti-heart' }}"></i>
                <span class="btn-text">
                    {!! $isFavorite ? __('ecommerce::ecommerce.remove_from_wishlist') : __('ecommerce::ecommerce.add_to_wishlist') !!}
                </span>
                <span class="theme_btn_eff"></span>
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('wishlist.add') }}" class="wishlist-form wishlist-link-form" up-submit up-target="#{{ $btnId }}, #wishlist-count" up-scroll="false">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="style" value="{{ $style }}">
            <input type="hidden" name="displayText" value="{{ $displayText }}">
            <button type="submit" id="{{ $btnId }}" title="{{ $isFavorite ? __('ecommerce::ecommerce.remove_from_wishlist') : __('ecommerce::ecommerce.add_to_wishlist') }}" class="add_hover add_wish" tabindex="0">
            @if($isFavorite)
                <i class="fa fa-heart" style="color: #ffffff;" aria-hidden="true"></i>
            @else
                <i class="ti-heart" style="color: #ffffff;" aria-hidden="true"></i>
            @endif
            </button>
        </form>
    @endif
@else
    @if($style == 'btn')
        <button type="button" class="theme_btn in-favorites"
                data-bs-toggle="modal" data-bs-target="#loginRequiredModal">
            <i class="ti-heart" aria-hidden="true"></i>
            <span class="btn-text">@lang('ecommerce::ecommerce.add_to_wishlist')</span>
            <span class="theme_btn_eff"></span>
        </button>
    @else
        <a href="#"
           data-bs-toggle="modal"
           data-bs-target="#loginRequiredModal"
           title="@lang('ecommerce::ecommerce.add_to_wishlist')"
           class="add_hover add_wish"
           tabindex="0">
            <i class="ti-heart" aria-hidden="true"></i>
        </a>
    @endif
@endauth

@if(isset($flash))
    <script>showNotification("{{ $flash['message'] }}", "{{ $flash['type'] }}");</script>
@endif
