<form method="POST" wire:submit.prevent="register">
    <div class="row gy-3">

        <div class= "col-xl-8">
            <label for="name" class="form-label text-default fw-bolder">@lang('driver::home.Full Name') <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control form-control-lg br-10"
                   id="name"
                   wire:model.defer="name"
                   placeholder="@lang('driver::home.Full Name')">
                   @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-xl-4">
                                                <label for="dateOfBirth" class="form-label text-default fw-bolder">{{__('admin::home.Date Of Birth')}}</label>
                                                <input type="date" class="form-control" id="dateOfBirth"
                                                       wire:model.defer="dateOfBirth">
                                                @error('dateOfBirth') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

        <div class="col-xl-6">
            <label for="email" class="form-label text-default fw-bolder">@lang('driver::home.Email Address')<span class="text-danger">*</span></label>
            <input type="email"
                   class="form-control form-control-lg br-10"
                   id="email"
                   wire:model.defer="email"
                   placeholder="@lang('driver::home.Email Address')">
                   @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="col-xl-6">
            <label for="phone" class="form-label text-default fw-bolder">@lang('driver::home.Phone Number')<span class="text-danger">*</span></label>
            <div class="input-group" dir="ltr">
                <span style="margin: 13px 10px 0px 13px; display: inline-block">(<img style="margin: 0px 2px 4px 4px" src="{{ asset('assets/vendor') }}/images/flags/ar.jpg" width="20"> +966 ) -</span>
                <input type="tel"
                       class="form-control form-control-lg br-10"
                       id="phone"
                       wire:model.defer="phone"
                       placeholder="@lang('driver::home.example') 512345678">

            </div>
            @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>

                                            <div class="col-xl-12">
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
                                            <div class="row mt-2 gy-3">
                                            <div class="col-xl-4">
                                                <label for="branch-country"
                                                       class="form-label text-black ">@lang('admin::home.Country') <span class="text-danger">*</span></label>

                                                <select class="form-control"
                                                        wire:model="country_id"
                                                        name="country_id"
                                                        id="branch-country-add">
                                                    <option value="">@lang('admin::home.Select')</option>
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
                                                    <label for="branch-region" class="form-label text-black">@lang('admin::home.Region')<span class="text-danger">*</span></label>
                                                    <select class="form-control selectCity"
                                                            wire:model="region_id"
                                                            id="branch-region">
                                                        <option value="">@lang('admin::home.Select')</option>
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
                                        </div>
                                        <div class="row mt-2 gy-3">
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
                                            <div class="col-xl-4">
                                                <label for="carNumber" class="form-label text-black">@lang('admin::home.Vehicle Registration Plate')</label>
                                                <input type="text" class="form-control" wire:model.defer="carNumber"
                                                    id="carNumber"
                                                    placeholder="@lang('admin::home.Vehicle Registration Plate')">
                                                @error('carNumber') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <label for="vehicleSequenceNumber" class="form-label text-black">@lang('admin::home.Vehicle ID') <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" wire:model.defer="vehicleSequenceNumber"
                                                    id="vehicleSequenceNumber"
                                                    placeholder="@lang('admin::home.Vehicle ID')">
                                                @error('vehicleSequenceNumber') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
        <div class="col-xl-6">
            <label for="signup-password" class="form-label text-default fw-bolder">@lang('driver::home.Password')</label>
            <div class="input-group">
                <input
                    type="password"
                    class="form-control form-control-lg br-10"
                    id="signup-password"
                    wire:model.defer="password"
                    placeholder="*************">
                <button class="btn btn-light" onclick="createpassword('signup-password',this)" type="button" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
            </div>
            @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-xl-6 mb-3">
            <label for="signup-confirmpassword" class="form-label text-default fw-bolder">@lang('driver::home.Confirm Password')</label>
            <div class="input-group">
                <input
                    type="password"
                    class="form-control form-control-lg br-10"
                    wire:model.defer="password_confirmation"
                    id="signup-confirmpassword" placeholder="*************">
                <button class="btn btn-light" onclick="createpassword('signup-confirmpassword',this)" type="button" id="button-addon21"><i class="ri-eye-off-line align-middle"></i></button>
            </div>
            @error('password_confirmation') <span class="d-block text-danger">{{ $message }}</span>@enderror
           
        </div>
<div class="col-xl-12">
<div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" wire:model.defer="terms">
                <label class="form-check-label text-muted fw-bolder " for="defaultCheck1">
                    @lang('driver::home.By creating a account you agree to our ')<a href="{{ route('driver:termsConditions') }}" target="_blank" class="text-success"><u>@lang('driver::home.Terms & Conditions')</u></a> @lang('driver::home.and') <a href="{{ route('driver:privacyPolicy') }}" target="_blank" class="text-success"><u>@lang('driver::home.Privacy Policy')</u></a>
                </label>
                @error('terms') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
</div>
        <div class="col-xl-12 d-grid mt-2">

            <button type="submit" class="btn btn-lg btn-primary" wire:loading.attr="disabled" wire:target="register">
                                <span class="indicator-label fw-bolder" wire:loading.class="d-none" wire:target="register">
                                   @lang('driver::home.Create Account')
                                </span>
                <span class="d-none fw-bolder" wire:loading.class.remove="d-none" wire:target="register">
                                    @lang('driver::home.Creating') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
            </button>
        </div>
    </div>
</form>

@push('js')
    <script>
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
