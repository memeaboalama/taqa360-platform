<div>
    <div class="modal fade" id="createBranch"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" wire:ignore.self
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('marketplace::home.Create new branch')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:loading.attr="disabled"
                        aria-label="Close"></button>
                </div>
                <form method="post" wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">

                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                            <div class="col-xl-6">
                                                <label for="branch-name-{{$localeCode}}" class="form-label text-black">@lang('marketplace::home.Branch Name') {{$properties['native']}} <span class="text-danger">*</span>
                                                    <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-branch-name-{{$localeCode}}"
                                                        onclick="translateLocalizedField('branch-name-{{$localeCode}}','branch-name-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                        title="{{ __('admin::main.actions.translate') }}"
                                                        style="cursor: pointer;">
                                                        <i class="ri-refresh-line"></i>
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control" id="branch-name-{{$localeCode}}"
                                                    wire:model.defer="title.{{$localeCode}}"
                                                    onblur="translateLocalizedField('branch-name-{{$localeCode}}','branch-name-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                @error("title." . $localeCode) <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            @endforeach
                                            <!-- <div class="col-xl-12">
                                                    <label for="branch-menu"
                                                           class="form-label text-black ">@lang('marketplace::home.Menu Name')</label>

                                                    <div wire:ignore>
                                                        <select class="form-control"
                                                                wire:model.defer="merchant_service_id"
                                                                name="merchant_service_id">
                                                            <option value="">@lang('marketplace::home.Select') @lang('marketplace::home.Menu Name')</option>
                                                            @foreach($menus as $menu)
                                                                <option
                                                                    value="{{ $menu->id }}">{{ $menu->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('merchant_service_id') <span
                                                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                                </div> -->


                                            <div class="col-xl-4">
                                                <label for="branch-country"
                                                    class="form-label text-black ">@lang('marketplace::home.Country') <span class="text-danger">*</span></label>

                                                <div wire:ignore>
                                                    <select class="form-control selectCountry"
                                                        data-trigger
                                                        name="country_id"
                                                        id="branch-country-add">
                                                        <option value="">@lang('marketplace::home.Select')</option>
                                                        @foreach($counties as $country)
                                                        <option
                                                            value="{{ $country  }}">{{ $country->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('country_id') <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <div>
                                                    <label for="branch-region" class="form-label text-black">@lang('company::home.Region') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectRegion"
                                                        wire:model="region_id"
                                                        id="branch-region">
                                                        <option value="">@lang('company::home.select')</option>
                                                        @foreach($regions as $region)
                                                        <option value="{{ $region->id }}">{{ $region->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('region_id') <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <div>
                                                    <label for="branch-city" class="form-label text-black">@lang('marketplace::home.City') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectCity"
                                                        wire:model="city_id"
                                                        id="branch-city">
                                                        <option value="">@lang('marketplace::home.Select')</option>
                                                        @foreach($cities as $city)
                                                        <option value="{{ $city->id }}">{{ $city->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('city_id') <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            {{-- <div class="col-xl-4">
                                                <div>
                                                    <label for="branch-district"
                                                           class="form-label text-black">@lang('marketplace::home.District') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectDistrict"
                                                            wire:model.lazy="distract_id"
                                                            id="branch-district">
                                                        <option value="">@lang('marketplace::home.Select')</option>
                                                        @foreach($districts as $district)
                                                            <option
                                                                value="{{ $district->id }}">{{ $district->title }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        @error('distract_id') <span
                                            class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                    </div> --}}


                                    <div class="col-xl-4">
                                        <div>
                                            <label for="manager_id"
                                                class="form-label text-black">@lang('marketplace::home.Select Branch Manager')</label>
                                            <select class="form-control"
                                                wire:model.lazy="manager_id"
                                                id="manager_id">
                                                <option value="">@lang('marketplace::home.Select')</option>
                                                @foreach($employees as $employee)
                                                <option
                                                    value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('manager_id') <span
                                            class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <div>
                                            <label for="package_id"
                                                class="form-label text-black">@lang('marketplace::home.Select Package') <span class="text-danger">*</span></label>
                                            <select class="form-control"
                                                wire:model.lazy="package_id"
                                                wire:change="fullPackageTypeService()"
                                                id="package_id">
                                                <option value="">@lang('marketplace::home.Select')</option>
                                                @foreach($packages as $package)
                                                <option
                                                    value="{{ $package->id }}">{{ $package->title."    (".$package->commission.")" }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('package_id') <span
                                            class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                    </div>

                                </div>

                                <div class="row gy-3 mt-2">
                                    <div class="col-xl-4">
                                        <label for="cover_image"
                                            class="form-label text-black">@lang('marketplace::home.cover_image')</label>
                                        <input type="file" class="form-control" id="cover_image"
                                            placeholder="{{ __("marketplace::home.cover_image") }}"
                                            wire:model.defer="cover_image">
                                        <label for="cover_image" class="form-label mt-2 text-danger">Max size : 1 MB , 500 px * 200 px</label>
                                        @error("cover_image")
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row gy-3 mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">

                                            <table class="table">
                                                <thead>
                                                    <th>@lang('admin::home.servicer type') </th>
                                                    <th>@lang('admin::home.commission percentage')</th>
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
                                                            <input wire:model.defer="package_type_services.{{$index}}.commission" disabled class="form-control" />
                                                        <td>
                                                            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                                <input id="{{'active'.$index}}"
                                                                    type="checkbox" {{ $package_type_services[$index]['status'] == "active"  ? 'checked' : '' }}>
                                                                <label for="{{'active'.$index}}" class="label-success"
                                                                    wire:click="updateStatusRow('{{ $index }}')" wire:ignore.self></label>
                                                            </div>
                                                        </td>


                                                    </tr>

                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gy-3 mt-3">
                                    @error('longitude')
                                    <div class="alert alert-danger" role="alert">
                                        {{ __('marketplace::messages.MAP_MESSAGE') }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="row gy-3 mt-3">
                                    <div class="col-xl-12">
                                        <label for="branch-address" class="form-label text-black">@lang('marketplace::home.Branch Address') <span class="text-danger">*</span></label>
                                        <input type="text" id="branch-address" class="form-control"
                                            wire:model.defer="address"
                                            placeholder="">
                                        @error('address')<span class="d-inline-block text-danger">{{$message}}</span>@enderror
                                    </div>
                                </div>
                                <div class="row gy-3 mt-3">
                                    <div class="col-xl-12" wire:ignore>
                                        <small class="d-block mb-1 text-muted">@lang('marketplace::home.map_hint')</small>
                                        <input type="text" id="branch-create-search"
                                            class="form-control"
                                            placeholder="@lang('marketplace::home.search_for_location')">
                                        <div id="branch-create-map" style="height:300px;" class="my-3 "></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger"
                data-bs-dismiss="modal" wire:loading.attr="disabled">@lang('marketplace::home.Close')
            </button>
            <button type="submit" class="btn btn-success">
                <span class="indicator-label" wire:loading.class="d-none">
                    @lang('marketplace::home.Save')
                </span>
                <span class="d-none" wire:loading.class.remove="d-none">
                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>

        </div>
        </form>
    </div>
</div>
</div>
</div>

@push('js')
    @include('shared.maps')
<script>
    function translateLocalizedField(sourceId, targetId, from, to, force) {
        if (typeof window.__translationInProgress === 'undefined') {
            window.__translationInProgress = {};
        }
        var sourceEl = document.getElementById(sourceId);
        var targetEl = document.getElementById(targetId);
        if (!sourceEl || !targetEl) return;

        var text = (sourceEl.value || '').trim();
        if (!text) return;

        if (!force && (targetEl.value || '').trim() !== '') return;

        if (window.__translationInProgress[sourceId]) return;
        window.__translationInProgress[sourceId] = true;

        var btn = document.getElementById('translate-' + sourceId);
        var originalHtml = btn ? btn.innerHTML : '';

        if (btn) {
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        }

        fetch('https://api.mymemory.translated.net/get?q=' + encodeURIComponent(text) + '&langpair=' + from + '|' + to)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                var translated = data && data.responseData && data.responseData.translatedText;
                if (translated) {
                    targetEl.value = translated;
                    targetEl.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    targetEl.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            })
            .catch(function(error) {
                console.error('Translation failed:', error);
            })
            .finally(function() {
                window.__translationInProgress[sourceId] = false;
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('disabled');
                    btn.innerHTML = originalHtml;
                }
            });
    }

    $(document).on('change', '.selectCountry', function() {
        $('.selectCity').empty();
        $('.selectRegion').empty();
        window.livewire.emit('updateCountry', $(this).val());
    });
    $(document).on('change', '.selectRegion', function() {
        $('.selectCity').empty();
        window.livewire.emit('updateRegion', $(this).val());
    });


    // $(document).on('change', '.selectCity', function () {
    //     $('.selectDistrict').empty();
    //     window.livewire.emit('updateCity', $(this).val());
    // });

    $(document).on('change', '.selectDistrict', function() {
        window.livewire.emit('updateDistrict', $(this).val());
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

    $(document).on('click', '#createBranch [data-bs-dismiss="modal"]', function() {
        if ($(this).is(':disabled')) {
            return false;
        }
    });

    var createBranchModal = document.getElementById('createBranch');
    if (createBranchModal) {
        createBranchModal.addEventListener('hide.bs.modal', function(event) {
            var closeBtn = createBranchModal.querySelector('.btn-close');
            var footerClose = createBranchModal.querySelector('.modal-footer [data-bs-dismiss="modal"]');
            if ((closeBtn && closeBtn.disabled) || (footerClose && footerClose.disabled)) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    }
</script>
<script>
    (function () {
        const component = @this;
        const latitude = @json($latitude), longitude = @json($longitude);
        const point = latitude !== '' && longitude !== '' && latitude !== null && longitude !== null
            && Number.isFinite(Number(latitude)) && Number.isFinite(Number(longitude))
            ? { lat: Number(latitude), lng: Number(longitude) } : null;
        const picker = TaqaMaps.mountPicker(document.getElementById('branch-create-map'), {
            point,
            editable: true,
            searchInput: document.getElementById('branch-create-search'),
            onChange: function (latitude, longitude, address) {
                component.set('latitude', latitude, true);
                component.set('longitude', longitude, true);
                component.set('address', address);
            }
        });

    })();
</script>
@endpush
