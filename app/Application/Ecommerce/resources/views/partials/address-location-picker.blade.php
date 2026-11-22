<div class="col-12">
    <div class="precise-form-group">
        <label>@lang('ecommerce::ecommerce.location_on_map')</label>
        <p class="text-muted mb-2">@lang('ecommerce::ecommerce.address_map_help')</p>
        <div class="address-location-coordinates" aria-live="polite">
            <span>@lang('ecommerce::ecommerce.selected_coordinates')</span>
            <output data-address-coordinates dir="ltr">@lang('ecommerce::ecommerce.no_location_selected')</output>
        </div>
        <div class="address-location-map" data-address-map role="application"
            aria-label="@lang('ecommerce::ecommerce.location_on_map')"></div>
        <div class="address-location-map-actions">
            <button type="button" class="btn-cancel" data-address-use-location>
                @lang('ecommerce::ecommerce.use_current_location')
            </button>
            <p class="text-muted mb-0" data-address-map-status role="status" aria-live="polite">
                @lang('ecommerce::ecommerce.location_selection_required')
            </p>
        </div>
        <input type="hidden" name="latitude" value="{{ old('latitude', $address->latitude ?? '') }}">
        <input type="hidden" name="longitude" value="{{ old('longitude', $address->longitude ?? '') }}">
    </div>
</div>
