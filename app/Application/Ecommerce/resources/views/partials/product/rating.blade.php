@php
    $productReviewCount = max(0, (int) data_get($product, 'reviews_count', 0));
    $productRatingValue = data_get($product, 'rate') ?? data_get($product, 'rating');
    $productRatingValue = $productReviewCount > 0 && is_numeric($productRatingValue)
        ? min(5, max(0, (float) $productRatingValue))
        : null;
    $productRatingClass = $containerClass ?? 'rating';
@endphp

<div class="{{ $productRatingClass }}">
    @if($productRatingValue !== null)
        @for($i = 1; $i <= 5; $i++)
            <i class="fa fa-star{{ $i <= $productRatingValue ? ' active' : '' }}" aria-hidden="true"></i>
        @endfor
        <span class="ms-2">
            {{ number_format($productRatingValue, 1) }}
            ({{ $productReviewCount }} @lang('ecommerce::ecommerce.reviews'))
        </span>
    @else
        <span class="text-muted small" role="status">@lang('ecommerce::ecommerce.no_reviews_yet')</span>
    @endif
</div>
