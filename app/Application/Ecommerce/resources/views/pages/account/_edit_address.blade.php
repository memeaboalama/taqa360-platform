@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    .precise-form-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        padding: 30px;
        margin-bottom: 30px;
    }

    .form-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a202c;
        margin: 0;
    }

    .precise-form-group {
        margin-bottom: 20px;
    }

    .precise-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #5d6778;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .precise-form-group input,
    .precise-form-group select,
    .precise-form-group textarea {
        width: 100%;
        min-height: 42px;
        padding: 9px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        color: #2d3748;
        background: #fff;
        transition: all 0.2s;
    }

    .precise-form-group textarea {
        min-height: 76px;
    }

    .precise-form-group input:focus,
    .precise-form-group select:focus,
    .precise-form-group textarea:focus {
        border-color: #306FAA;
        box-shadow: 0 0 0 3px rgba(48, 111, 170, 0.1);
        outline: none;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-save {
        padding: 9px 18px;
        background: #306FAA;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .address-save-spinner {
        display: none;
    }

    .address-form.is-submitting .address-save-check {
        display: none;
    }

    .address-form.is-submitting .address-save-spinner {
        display: inline-block;
    }

    .address-form.is-submitting .btn-save {
        cursor: wait;
        opacity: 0.85;
    }

    .btn-save:hover {
        background: #245C8E;
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-cancel {
        padding: 9px 18px;
        background: #fff;
        color: #666;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-cancel:hover {
        background: #f8f9fa;
        color: #333;
        border-color: #cbd5e0;
    }

    .default-address-option {
        display: flex;
        align-items: center;
        gap: 12px;
        width: fit-content;
        max-width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        color: #2d3748;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .default-address-option input {
        width: 20px;
        height: 20px;
        margin: 0;
        padding: 0;
        accent-color: #306FAA;
        flex: 0 0 auto;
        cursor: pointer;
    }
</style>

@unless($useNewAccountLayout ?? false)
<div class="account-content-area w-100">
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.addresses')</h2>
    </div>
@endunless

    <div class="precise-form-card">
        @unless($useNewAccountLayout ?? false)
        <div class="form-header">
            <h4 class="form-title">@lang('ecommerce::ecommerce.edit_address')</h4>
        </div>
        @endunless

        <form class="address-form" action="{{ route('account.update_address', $address->id) }}" method="POST"
              @if($useNewAccountLayout ?? false)
              data-async-save="true"
              @else
              up-submit up-target=".dashboard-content"
              @endif>
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.address_label') <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $address->title) }}" placeholder="@lang('ecommerce::ecommerce.home_work_etc')" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.country') <span class="text-danger">*</span></label>
                         <select name="country_id" id="address-country" required>
                             <option value="">@lang('ecommerce::ecommerce.select_country')</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $address->country_id) == $country->id ? 'selected' : '' }}>{{ $country->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.city') <span class="text-danger">*</span></label>
                         <select name="city_id" id="address-city" required disabled>
                             <option value="">@lang('ecommerce::ecommerce.select_city')</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label for="address-district">@lang('ecommerce::ecommerce.district')</label>
                        <input type="text" name="district_name" id="address-district" value="{{ old('district_name', $address->district_name ?: $address->distract?->title) }}" maxlength="255">
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.address_details') <span class="text-danger">*</span></label>
                        <textarea name="address" rows="3" required placeholder="@lang('ecommerce::ecommerce.enter_full_address')">{{ old('address', $address->address) }}</textarea>
                    </div>
                </div>
                
                <div class="col-12">
                    <label class="default-address-option mb-4" for="setDefault">
                        <input type="checkbox" name="is_default" id="setDefault" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                        <span>
                            @lang('ecommerce::ecommerce.set_as_default_address')
                        </span>
                    </label>
                </div>

                <input type="hidden" name="latitude" value="{{ $address->latitude }}">
                <input type="hidden" name="longitude" value="{{ $address->longitude }}">
            </div>

            <div class="section-divider border-top pt-4 mt-0 opacity-25"></div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="ti-check address-save-check"></i>
                    <i class="fa fa-spinner fa-spin address-save-spinner" aria-hidden="true"></i>
                    @lang('ecommerce::ecommerce.save_changes')
                </button>
                <a href="{{ ($useNewAccountLayout ?? false) ? route('account.index', ['section' => 'addresses']) : route('account.addresses') }}" class="btn-cancel" @unless($useNewAccountLayout ?? false) up-target=".dashboard-content" @endunless>
                    @lang('ecommerce::ecommerce.cancel')
                </a>
            </div>
        </form>
    </div>
@unless($useNewAccountLayout ?? false)
</div>
@endunless

<script>
    (function () {
        var countrySelect = document.getElementById('address-country');
        var citySelect = document.getElementById('address-city');
        if (!countrySelect || !citySelect) return;

        var citiesByCountry = @json($citiesByCountry);
        var initialCityId = @json(old('city_id', $address->city_id));
        var placeholder = @json(__('ecommerce::ecommerce.select_city'));

        function populateCities(selectedCityId) {
            var cities = citiesByCountry[countrySelect.value] || [];
            citySelect.replaceChildren(new Option(placeholder, ''));
            cities.forEach(function (city) {
                citySelect.add(new Option(city.title, city.id));
            });
            citySelect.value = selectedCityId || '';
            citySelect.disabled = !countrySelect.value;
        }

        countrySelect.addEventListener('change', function () {
            populateCities('');
        });
        populateCities(initialCityId);
    })();
</script>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
