<form method="post" wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">

                                          
                                        
                                                


                                            <div class="col-xl-4">
                                                <label for="branch-country"
                                                       class="form-label text-black ">@lang('marketplace::home.Country') <span class="text-danger">*</span></label>

                                                <select class="form-control selectCountry"
                                                        wire:model="country_id"
                                                        name="country_id"
                                                        id="branch-country-add">
                                                    <option value="">@lang('marketplace::home.Select')</option>
                                                    @foreach($counties as $country)
                                                        <option
                                                            value="{{ $country->id }}">{{ $country->title }}</option>
                                                    @endforeach
                                                </select>
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
                                                    <label for="branch-cityasa"
                                                           class="form-label text-black">@lang('marketplace::home.District') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectDistrict"
                                                            wire:model.lazy="distract_id"
                                                            id="branch-cityasa">
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


                                            


                                        </div>

                                        <div class="row gy-3 mt-2">
                                            <div class="col-md-12">
                                            <div class="form-group">

<table class="table">
    <thead>
        <th>@lang('admin::home.Package') </th>
    <th>@lang('admin::home.servicer type') </th>
    <th>@lang('admin::home.Description')</th>
    <th>@lang('admin::home.status')</th>

    </thead>

    <tbody>
    @foreach($package_type_services as $index =>$package_type_service)
        <tr>
            <input wire:model.defer="package_type_services.{{$index}}.type_service_id" class="form-control"  type="hidden" />
            <td>
            <input  wire:model.defer="package_type_services.{{$index}}.package.title.{{LaravelLocalization::getCurrentLocale()}}"  class="form-control" disabled />
                            </td>
            <td>
            <input type="text" value=" @lang('admin::home.' . $package_type_services[$index]['type_service']['name']) " class="form-control" disabled />
                            </td>

                        
            <td>
            <input wire:model.defer="package_type_services.{{$index}}.desc"  class="form-control" />
                            </td>
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
                                      
                                            
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                       
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    @lang('marketplace::home.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>

                @push('js')
    <script>
        $(document).on('change', '.selectDistrict', function () {
            window.livewire.emit('updateDistrict', $(this).val());
        });

        Livewire.on('renderRegions', regions => {
            let element = $('.selectRegion');
            element.empty();
            element.append(`<option value="">@lang('company::home.select')</option>`);
            for (let i = 0; i < regions.length; i++) {
                element.append(`<option value="${regions[i].id}">${regions[i].name}</option>`);
            }
        });

        Livewire.on('renderCities', cities => {
            let element = $('.selectCity');
            element.empty();
            element.append(`<option value="">@lang('marketplace::home.Select')</option>`);
            for (let i = 0; i < cities.length; i++) {
                element.append(`<option value="${cities[i].id}">${cities[i].name}</option>`);
            }
        });
    </script>

@endpush
