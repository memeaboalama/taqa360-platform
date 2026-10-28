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
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #2d3748;
        background: #fff;
        transition: all 0.2s;
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
        gap: 15px;
        margin-top: 10px;
    }

    .btn-save {
        padding: 12px 24px;
        background: #306FAA;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-save:hover {
        background: #245C8E;
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-cancel {
        padding: 12px 24px;
        background: #fff;
        color: #666;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
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
</style>

<div class="account-content-area w-100">
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.addresses')</h2>
    </div>

    <div class="precise-form-card">
        <div class="form-header">
            <h4 class="form-title">@lang('ecommerce::ecommerce.edit_address')</h4>
        </div>

        <form action="{{ route('account.update_address', $address->id) }}" 
              method="POST"
              up-submit
              up-target=".dashboard-content">
            @csrf
            
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
                         <select name="country_id" required>
                             <option value="">@lang('ecommerce::ecommerce.select_country')</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $address->country_id == $country->id ? 'selected' : '' }}>{{ $country->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.city') <span class="text-danger">*</span></label>
                         <select name="city_id" required>
                             <option value="">@lang('ecommerce::ecommerce.select_city')</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ $address->city_id == $city->id ? 'selected' : '' }}>{{ $city->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.district') <span class="text-danger">*</span></label>
                         <select name="distract_id" required>
                             <option value="">@lang('ecommerce::ecommerce.select_district')</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ $address->distract_id == $district->id ? 'selected' : '' }}>{{ $district->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.address_details') <span class="text-danger">*</span></label>
                        <textarea name="address" rows="3" required placeholder="@lang('ecommerce::ecommerce.enter_full_address')">{{ old('address', $address->address) }}</textarea>
                    </div>
                </div>
                
                <div class="col-12">
                     <div class="form-check mb-4">
                        <input type="checkbox" name="is_default" class="form-check-input" id="setDefault" value="1" {{ $address->is_default ? 'checked' : '' }} style="width: auto; margin-right: 8px;">
                        <label class="form-check-label d-inline-block" for="setDefault" style="font-size: 14px; font-weight: 500; text-transform: none;">
                            @lang('ecommerce::ecommerce.set_as_default_address')
                        </label>
                    </div>
                </div>

                <input type="hidden" name="latitude" value="{{ $address->latitude }}">
                <input type="hidden" name="longitude" value="{{ $address->longitude }}">
            </div>

            <div class="section-divider border-top pt-4 mt-0 opacity-25"></div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="ti-check"></i> @lang('ecommerce::ecommerce.save_changes')
                </button>
                <a href="{{ route('account.addresses') }}" class="btn-cancel" up-target=".dashboard-content">
                    <i class="ti-arrow-left arrow-start"></i> @lang('ecommerce::ecommerce.cancel')
                </a>
            </div>
        </form>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
