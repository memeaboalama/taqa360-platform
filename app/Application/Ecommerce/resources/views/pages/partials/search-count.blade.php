<h5>
    @lang('ecommerce::ecommerce.products.showing_products')
    {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}

    @lang('ecommerce::ecommerce.products.of')
    {{ $products->total() }} @lang('ecommerce::ecommerce.products.results')
</h5>
