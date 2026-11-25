<div>
<!-- <form wire:submit.prevent="storeData"> -->
<form method="post" wire:submit.prevent="store" enctype="multipart/form-data">
    <fieldset {{ $editing ? '' : 'disabled' }}>
    <div class="modal-body">
        <div class="row gx-5">
            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                <div class="card custom-card shadow-none border-0 mb-0">
                    <div class="card-body p-0">
                        <div class="row gy-3">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <div class="col-xl-3">
                                <label for="branch-name"
                                    class="form-label text-black">@lang('marketplace::home.Branch Name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="branch-name"
                                wire:model.defer="title.{{$localeCode}}">
                                @error("title." . $localeCode) <span
                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                            </div>
                            @endforeach

                            <div class="col-xl-3">
                                <label for="branch-menu"
                                    class="form-label text-black ">@lang('marketplace::home.Menu Name')</label>
                                    <select class="form-control"
                                        wire:model.defer="merchant_service_id"
                                        id="merchant_service_id"
                                        {{ $menus->isEmpty() ? 'disabled' : '' }}>
                                        @if($menus->isEmpty())
                                            <option value="">@lang('marketplace::home.No Menus Available')</option>
                                        @else
                                            <option value="">@lang('marketplace::home.Select') @lang('marketplace::home.Menu Name')</option>
                                            @foreach($menus as $menu)
                                                <option value="{{ $menu->id }}">{{ \Illuminate\Support\Str::of($menu->title)->limit(30) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">@lang('marketplace::home.Menu Name Help')</small>
                                @error('merchant_service_id') <span
                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-3">
                                <div>
                                    <label for="manager_id"
                                        class="form-label text-black">@lang('marketplace::home.Branch Manager')</label>
                                    <select class="form-control"
                                        wire:model.lazy="manager_id"
                                        id="manager_id">
                                        <option value="">@lang('marketplace::home.Select')</option>
                                        @foreach($employees as $employee)
                                        <option
                                            value="{{ $employee->id }}" {{ $employee->id == $manager_id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="form-text text-muted">@lang('marketplace::home.Branch Manager Help')</small>
                                @error('manager_id') <span
                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-4">
                                <label for="branch-country"
                                    class="form-label text-black ">@lang('marketplace::home.Country') <span class="text-danger">*</span></label>

                                    <div wire:ignore>
                                    <select class="form-control selectCountry"
                                        data-trigger
                                        wire:model.defer="country_id"
                                        id="branch-country-add">
                                        <option value="">@lang('marketplace::home.Select')</option>
                                        @foreach($counties as $country)
                                        <option
                                            value="{{ $country->id  }}" {{ $country->id == $country_id ? 'selected' : '' }}>{{ $country->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country_id') <span
                                    class="d-inline-block text-info">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-4">
                                <div>
                                    <label for="branch-region" class="form-label text-black">@lang('company::home.Region') <span class="text-danger">*</span></label>
                                    <select class="form-control selectRegion"
                                        wire:model="region_id"
                                        id="branch-region">
                                        <option value="">@lang('company::home.select')</option>
                                        @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ $region->id == $region_id ? 'selected' : '' }}>{{ $region->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('region_id') <span
                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-4">
                                <div>
                                    <label for="branch-city"
                                        class="form-label text-black">@lang('marketplace::home.City') <span class="text-danger">*</span></label>
                                    <select class="form-control selectCity"
                                        wire:model="city_id"
                                        id="branch-city">
                                        <option value="">@lang('marketplace::home.Select')</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ $city->id == $city_id ? 'selected' : '' }}>{{ $city->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('city_id') <span
                                    class="d-inline-block text-info">{{ $message }}</span> @enderror
                            </div>

                            {{-- <div class="col-xl-4">
                                    <div>
                                        <label for="branch-distract"
                                               class="form-label text-black">@lang('marketplace::home.District') <span class="text-danger">*</span></label>
                                        <select class="form-control selectDistrict"
                                                wire:model.lazy="distract_id"
                                                id="branch-distract">
                                            <option value="">@lang('marketplace::home.Select')</option>
                                            @foreach($districts as $district)
                                                <option
                                                    value="{{ $district->id }}" {{ $district->id == $distract_id ? 'selected' : '' }}>{{ $district->title }}</option>
                            @endforeach
                            </select>
                        </div>
                        @error('distract_id') <span
                            class="d-inline-block text-info">{{ $message }}</span> @enderror
                    </div> --}}
                </div>
                <div class="row gy-3 mt-2">
                    <div class="col-md-12">
                        <div class="form-group">

                            <table class="table">
                                <thead>
                                    <th>@lang('admin::home.servicer type') </th>
                                    <th>@lang('admin::home.Description')</th>
                                    <th>@lang('admin::home.commission percentage')</th>
                                    <th>@lang('admin::home.discount percentage')</th>
                                    <th>@lang('admin::home.status')</th>

                                </thead>

                                <tbody>
                                    @foreach($package_type_services as $index =>$package_type_service)
                                    <tr>
                                        <input wire:model.defer="package_type_services.{{$index}}.type_service_id" class="form-control" type="hidden" />
                                        <td>
                                            <input value="{{ \Illuminate\Support\Facades\Lang::has('admin::home.'.$package_type_services[$index]['type_service']['name']) ? __('admin::home.'.$package_type_services[$index]['type_service']['name']) : $package_type_services[$index]['type_service']['name'] }}" class="form-control" disabled />
                                        </td>

                                        <td>
                                            <input wire:model.defer="package_type_services.{{$index}}.desc" class="form-control" />
                                        </td>
                                        <td>
                                            <input type="number" class="form-control"
                                                wire:model.defer="package_type_services.{{$index}}.commission"
                                                value="{{$package_type_services[$index]['commission']}}"
                                                disabled />
                                        </td>
                                        <td>
                                            <input wire:model.defer="package_type_services.{{$index}}.discount_rate" value="{{$package_type_services[$index]['discount_rate']}}" disabled class="form-control" />
                                        </td>
                                        <td>
                                            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                <input id="{{'active'.$index + 1000 }}"
                                                    type="checkbox" value="1"
                                                    wire:model.defer="{{ 'package_type_services.' . $index . '.active' }}" {{ $package_type_services[$index]['status'] == "active"  ? 'checked' : '' }}>
                                                <label for="{{'active'.$index + 1000 }}" class="label-success"></label>
                                            </div>
                                        </td>


                                    </tr>

                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 mt-2">
                        <label for="cover_image"
                            class="form-label text-black">@lang('marketplace::home.cover_image') <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="cover_image"
                            placeholder="{{ __("marketplace::home.cover_image") }}"
                            wire:model.defer="cover_image">
                        <label for="cover_image" class="form-label mt-2 text-danger">Max size : 1 MB , 500 px * 200 px</label>
                        @error("cover_image")
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-xl-8">
                        <img src="{{ $cover_image_path }}"
                            class="img-fluid rounded-start mt-3"
                            alt="@lang('marketplace::home.cover_image')" width="150">
                    </div>
                </div>
                <div class="row gy-3 mt-3">
                    @error('longitude')
                    <div class="alert alert-danger" role="alert">
                        {{ __('marketplace::messages.MAP_MESSAGE') }}
                    </div>
                    @enderror
                    @error('address')<span class="text-danger">{{$message}}</span>@enderror
                </div>
                <div class="row gy-3 mt-3">
                <div class="col-xl-12" wire:ignore>
                        <input type="text" id="branch-info-search"
                            class="form-control"
                            placeholder="">
                        <div id="branch-info-map" style="height:300px;" class="my-3 "></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </fieldset>
    @MerchantHasRole(['edit info_branches'])
    <div class="modal-footer">
        <button type="submit" class="btn btn-success" {{ $editing ? '' : 'hidden' }}>
            <span class="indicator-label" wire:loading.class="d-none">
                @lang('marketplace::home.Save')
            </span>
            <span class="d-none" wire:loading.class.remove="d-none">
                @lang('marketplace::home.Wait') <span
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>

    </div>
    @endMerchantHasRole
</form>
</div>
@push('js')
    @include('shared.maps')
<script>
    $(document).on('change', '.selectCountry', function() {
        //     $('.selectCity').empty();
        //     window.livewire.emit('updateCountryId', $(this).val());
        // });
        $('.selectRegion').empty();
        $('.selectCity').empty();
        window.livewire.emit('updateCountry', $(this).val());
    });
    // $(document).on('change', '.selectRegion', function() {
    //     $('.selectCity').empty();
    //     window.livewire.emit('updateRegion', $(this).val());
    // });

    // $(document).on('change', '.selectCity', function() {
    //     $('.selectDistrict').empty();
    //     window.livewire.emit('updateCity', $(this).val());
    // });

    // $(document).on('change', '.selectDistrict', function() {
    //     window.livewire.emit('updateDistrict', $(this).val());
    // });


    Livewire.on('renderRegions', regions => {
        let element = $('.selectRegion');
        element.append(`<option value="">select</option>`);
        for (let i = 0; i < cities.length; i++) {
            element.append(`<option value="${cities[i].id}">${cities[i]?.title?.en}</option>`);
        }
        // Livewire.emit('renderSelect');
    });
    Livewire.on('renderCities', cities => {
        let element = $('.selectCity');
        element.append(`<option value="">select</option>`);
        for (let i = 0; i < cities.length; i++) {
            element.append(`<option value="${cities[i].id}">${cities[i]?.title?.en}</option>`);
        }
        // Livewire.emit('renderSelect');
    });

    Livewire.on('renderDistricts', districts => {
        console.log(districts);

        for (let i = 0; i < districts.length; i++) {
            $('.selectDistrict').append(`<option value="${districts[i].id}">${districts[i]?.title?.en}</option>`);
        }
        Livewire.emit('renderSelect');
    });
</script>



<script>
    (function () {
        const component = @this;
        const latitude = @json($latitude), longitude = @json($longitude);
        const point = latitude !== '' && longitude !== '' && latitude !== null && longitude !== null
            && Number.isFinite(Number(latitude)) && Number.isFinite(Number(longitude))
            ? { lat: Number(latitude), lng: Number(longitude) } : null;
        const picker = TaqaMaps.mountPicker(document.getElementById('branch-info-map'), {
            point,
            editable: @json($editing),
            searchInput: document.getElementById('branch-info-search'),
            onChange: function (latitude, longitude, address) {
                component.set('latitude', latitude, true);
                component.set('longitude', longitude, true);
                component.set('address', address);
            }
        });
        Livewire.on('branchEditingChanged', function (editing, location) {
            picker.setEditable(editing);
            if (!editing) picker.reset(location);
        });
    })();
</script>
@endpush
