@if($specificationFacets->isNotEmpty())
    <form class="merchant-spec-filters" method="get" action="{{ route('merchant.show', $merchant->id) }}"
        data-placeholder="{{ __('ecommerce::merchant_filters.choose') }}" data-loading="{{ __('ecommerce::merchant_filters.loading') }}"
        data-empty="{{ __('ecommerce::merchant_filters.empty') }}" data-error="{{ __('ecommerce::merchant_filters.error') }}">
        <h3><i class="fa fa-sliders" aria-hidden="true"></i> @lang('ecommerce::merchant_filters.heading')</h3>
        @foreach(['category', 'q', 'sort_by', 'per_page'] as $parameter)
            @if(request()->filled($parameter))<input type="hidden" name="{{ $parameter }}" value="{{ request($parameter) }}">@endif
        @endforeach
        <div class="merchant-spec-fields">
            @foreach($specificationFacets as $facet)
                <fieldset>
                    <legend>{{ $facet['label'] }}</legend>
                    <div class="merchant-spec-dropdown" data-spec-dropdown data-field="{{ $facet['id'] }}"
                        data-url="{{ route('merchant.specification-options', array_merge(['merchant' => $merchant->id], request()->only(['category', 'q']))) }}">
                        <div data-selected-inputs>
                            @foreach($facet['options'] as $option)
                                @if(in_array($option['value'], $selectedSpecifications[$facet['id']] ?? [], true))
                                    <input type="hidden" name="specs[{{ $facet['id'] }}][]" value="{{ $option['value'] }}" data-selected-label="{{ $option['label'] }}">
                                @endif
                            @endforeach
                        </div>
                        <button type="button" class="merchant-spec-trigger" data-spec-trigger aria-haspopup="listbox" aria-expanded="false" aria-controls="spec-options-{{ $facet['id'] }}" aria-label="{{ $facet['label'] }}">
                            <span data-selection-label>@lang('ecommerce::merchant_filters.choose')</span><i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <div class="merchant-spec-menu" data-spec-menu hidden>
                            <div class="merchant-spec-search"><i class="fa fa-search" aria-hidden="true"></i><input type="search" data-spec-search autocomplete="off" maxlength="120" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="spec-options-{{ $facet['id'] }}" aria-label="{{ __('ecommerce::merchant_filters.search', ['field' => $facet['label']]) }}" placeholder="{{ __('ecommerce::merchant_filters.search', ['field' => $facet['label']]) }}"></div>
                            <div class="merchant-spec-options" data-spec-options id="spec-options-{{ $facet['id'] }}" role="listbox" aria-multiselectable="true" aria-label="{{ $facet['label'] }}"></div>
                            <p class="merchant-spec-status" data-spec-status role="status" aria-live="polite"></p>
                            <button type="button" class="merchant-spec-retry" data-spec-retry hidden>@lang('ecommerce::merchant_filters.retry')</button>
                        </div>
                    </div>
                </fieldset>
            @endforeach
        </div>
        <div class="merchant-spec-actions">
            <button type="submit">@lang('ecommerce::merchant_filters.apply')</button>
            @if($selectedSpecifications)
                <a href="{{ route('merchant.show', array_merge(['merchant' => $merchant->id], request()->except(['specs', 'page']))) }}">@lang('ecommerce::merchant_filters.clear')</a>
            @endif
        </div>
    </form>
@endif
