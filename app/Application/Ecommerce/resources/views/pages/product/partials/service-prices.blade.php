        @if($product->activeItemPrices->isNotEmpty())
            <section class="pp-service-prices">
                <div class="section-head">
                    <h3>@lang('ecommerce::product_page.service_prices')</h3>
                </div>
                <div class="info-grid">
                    @foreach($product->activeItemPrices as $itemPrice)
                        @php
                            $typeServiceName = $itemPrice->type_service?->name;
                            $typeServiceLabel = match ($typeServiceName) {
                                'default', 'with_delivery' => __('admin::home.with_delivery'),
                                'without_delivery' => __('admin::home.without_delivery'),
                                null => '-',
                                default => \Illuminate\Support\Facades\Lang::has('admin::home.' . $typeServiceName)
                                    ? __('admin::home.' . $typeServiceName)
                                    : $typeServiceName,
                            };

                            $hasServicePrice = is_numeric($itemPrice->price) && (float) $itemPrice->price > 0;
                            $displayPrice = $hasServicePrice ? (float) $itemPrice->price : null;
                        @endphp
                        <div class="info-card">
                            <h4>{{ $typeServiceLabel }}</h4>
                            <p>
                                @lang('ecommerce::ecommerce.provided_by_store'):
                                @if($product->merchant)
                                    <a href="{{ route('merchant.show', $product->merchant->id) }}">
                                        {{ $product->merchant->getTranslation('company_name', app()->getLocale()) ?: $product->merchant->name }}
                                    </a>
                                @else
                                    {{ __('ecommerce::ecommerce.site_name') }}
                                @endif
                            </p>
                            <p>
                                @lang('ecommerce::ecommerce.product_price_for_option'):
                                @if($displayPrice !== null)
                                    {{ \App\Domain\AppBase\Models\Setting\Currency::format($displayPrice) }}
                                @else
                                    @lang('ecommerce::ecommerce.price_not_specified')
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

