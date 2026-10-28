<div>
    <div class="modal fade" id="createDriver" wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> Create new driver</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-6">
                                                <label for="name" class="form-label text-black">{{__('admin::home.Driver Name')}}</label>
                                                <input type="text" class="form-control" id="name"
                                                       placeholder="Name" wire:model.defer="name">
                                                @error('name') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="email"
                                                       class="form-label text-black">{{__('admin::home.Email')}}</label>
                                                <input type="email" class="form-control" id="email"
                                                       wire:model.defer="email"
                                                       placeholder="email">
                                                @error('email') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6" >
                                                <label for="taken_commission"
                                                       class="form-label text-black">{{__('admin::home.Commission')}}</label>
                                                <input type="number" min="0" max="100" class="form-control" id="taken_commission"
                                                       wire:model.defer="taken_commission"
                                                       placeholder="0.00" disabled>
                                                @error('taken_commission') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="phone" class="form-label text-black">@lang('admin::home.Phone Number')</label>
                                                <input type="text" class="form-control" wire:model.defer="phone"
                                                       id="phone"
                                                       placeholder="phone">
                                                @error('phone') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-2 gy-3">
                                            <div class="col-xl-6">
                                                <label for="dateOfBirth" class="form-label text-black">{{__('admin::home.Date Of Birth')}}</label>
                                                <input type="date" class="form-control" id="dateOfBirth"
                                                       wire:model.defer="dateOfBirth">
                                                @error('dateOfBirth') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="Id_expire_date" class="form-label text-black">{{ __('admin::home.Id_expire_date') }}</label>
                                                <input type="date" class="form-control" id="Id_expire_date"
                                                       wire:model.defer="Id_expire_date">
                                                @error('Id_expire_date') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="Id_number" class="form-label text-black">{{ __('admin::home.Id_number') }}</label>
                                                <input type="text" class="form-control" wire:model.defer="Id_number"
                                                       id="Id_number"
                                                       placeholder="{{ __('admin::home.Id_number') }}">
                                                @error('Id_number') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <div>
                                                    <label for="IdentityTypeId" class="form-label text-black">@lang('admin::home.Identity Type')<span class="text-danger">*</span></label>
                                                    <select class="form-control selectCity"
                                                            wire:model="IdentityTypeId"
                                                            id="IdentityTypeId">
                                                        <option value="">@lang('admin::home.Select')</option>
                                                        <option value="NV25GlPuOnQ=">مواطن</option>
                                                        <option value="oIcaYzeDfQQ=">مقيم</option>
                                                    </select>
                                                </div>
                                                @error('IdentityTypeId') <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-12">
                                                <label for="iban" class="form-label text-black">{{ __('admin::home.iban') }}</label>
                                                <input type="text" class="form-control" wire:model.defer="iban"
                                                       id="iban"
                                                       placeholder="{{ __('admin::home.iban') }}">
                                                @error('iban') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-2 gy-3">
                                            <div class="col-xl-4">
                                                <label for="branch-country"
                                                       class="form-label text-black ">@lang('admin::home.Country') <span class="text-danger">*</span></label>

                                                <div wire:ignore>
                                                    <select class="form-control selectCountry"
                                                            data-trigger
                                                            name="country_id"
                                                            id="branch-country-add">
                                                        <option value="">@lang('admin::home.Select')</option>
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
                                                    <label for="branch-city" class="form-label text-black">@lang('admin::home.Region')<span class="text-danger">*</span></label>
                                                    <select class="form-control selectCity"
                                                            wire:model="region_id"
                                                            id="branch-city">
                                                        <option value="">@lang('admin::home.Select')</option>
                                                        @foreach($regions as $region)
                                                            <option value="{{ $region->id }}">{{ $region->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('city_id') <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <div>
                                                    <label for="branch-city" class="form-label text-black">@lang('admin::home.City') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectCity"
                                                            wire:model="city_id"
                                                            id="branch-city">
                                                        <option value="">@lang('admin::home.Select')</option>
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
                                                           class="form-label text-black">@lang('admin::home.District') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectDistrict"
                                                            wire:model.lazy="district_id"
                                                            id="branch-cityasa">
                                                        <option value="">@lang('admin::home.Select')</option>
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

                                        <div class="row mt-2 gy-3">
                                            <div class="col-xl-6">
                                                <label for="carNumber" class="form-label text-black">@lang('admin::home.Vehicle Registration Plate')</label>
                                                <input type="text" class="form-control" wire:model.defer="carNumber"
                                                    id="carNumber"
                                                    placeholder="@lang('admin::home.Vehicle Registration Plate')">
                                                @error('carNumber') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="vehicleSequenceNumber" class="form-label text-black">@lang('admin::home.Vehicle ID')</label>
                                                <input type="text" class="form-control" wire:model.defer="vehicleSequenceNumber"
                                                    id="vehicleSequenceNumber"
                                                    placeholder="@lang('admin::home.Vehicle ID')">
                                                @error('vehicleSequenceNumber') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-4">
                                                <div>
                                                    <label for="branch-city" class="form-label text-black">@lang('admin::home.Vehicle Type') <span class="text-danger">*</span></label>
                                                    <select class="form-control selectCity"
                                                            wire:model="car_type_id"
                                                            id="branch-city">
                                                        <option value="">@lang('admin::home.Select')</option>
                                                        @foreach($cars_types as $type)
                                                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('car_type_id') <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-2 gy-3">
                                            <div class="col-xl-5">
                                                <label for="password"
                                                       class="form-label text-black">@lang('admin::home.Password')</label>
                                                <input type="password" class="form-control" id="password"
                                                       placeholder="************"
                                                       wire:model.defer="password"
                                                >
                                                @error('password') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-5">
                                                <label for="password-confirm" class="form-label text-black">
                                                    @lang('admin::home.Confirm Password')</label>
                                                <input type="password" class="form-control" id="password-confirm"
                                                       placeholder="************"
                                                       wire:model.defer="password_confirmation">
                                            </div>


                                            <div class="col-xl-2">
                                                <label for="category-active"
                                                       class="form-label text-black">@lang('admin::home.status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="category-active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}
                                                    >
                                                    <label for="category-active" class="label-success"
                                                           wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                                @error('status') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            {{--                                            <div class="col-xl-12" wire:ignore>--}}
                                            {{--                                                <div id="map" style="height:300px;" class="my-3"></div>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"data-bs-dismiss="modal">
                            @lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('admin::home.Save')
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none">
                                        @lang('admin::home.Wait') 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
{{--@push('js')--}}
{{--        <script>--}}
{{--            let map;--}}
{{--            function initMap() {--}}
{{--                map = new google.maps.Map(document.getElementById("map"), {--}}
{{--                    center: {--}}
{{--                        lat: 24.774265,--}}
{{--                        lng: 46.738586,--}}
{{--                    },--}}
{{--                    zoom: 8,--}}
{{--                    scrollwheel: true,--}}
{{--                });--}}

{{--                const uluru = {--}}
{{--                    lat: 24.774265,--}}
{{--                    lng: 46.738586,--}}
{{--                };--}}

{{--                const icon = {--}}
{{--                    url: "{{ asset('assets/vendor/images/marker.png') }}",--}}
{{--                    scaledSize: new google.maps.Size(50, 50),--}}
{{--                    origin: new google.maps.Point(0,0),--}}
{{--                    anchor: new google.maps.Point(0, 0)--}}
{{--                };--}}

{{--                let marker = new google.maps.Marker({--}}
{{--                    position: uluru,--}}
{{--                    map: map,--}}
{{--                    icon: icon,--}}
{{--                    draggable: true--}}
{{--                });--}}

{{--                google.maps.event.addListener(marker,'position_changed',--}}
{{--                    function (){--}}
{{--                        let lat = marker.position.lat()--}}
{{--                        let lng = marker.position.lng()--}}
{{--                        window.livewire.emit('set:latitude-longitude', lat, lng)--}}
{{--                    })--}}

{{--                google.maps.event.addListener(map,'click',--}}
{{--                    function (event){--}}
{{--                        pos = event.latLng--}}
{{--                        marker.setPosition(pos)--}}
{{--                    })--}}
{{--            }--}}
{{--        </script>--}}
{{--    @endpush--}}
</div>

@push('js')
    <script>
        $(document).on('change', '.selectCountry', function () {
            $('.selectCity').empty();
            window.livewire.emit('updateCountry', $(this).val());
        });


        // $(document).on('change', '.selectCity', function () {
        //     $('.selectDistrict').empty();
        //     window.livewire.emit('updateCity', $(this).val());
        // });

        $(document).on('change', '.selectDistrict', function () {
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
    </script>
@endpush
