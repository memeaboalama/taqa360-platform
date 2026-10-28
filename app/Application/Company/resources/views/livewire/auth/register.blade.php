<form method="POST" wire:submit.prevent="register">
    <div class="row gy-3">
        <div class="col-xl-12">
            <label for="signup-companyname" class="form-label text-default">@lang('company::home.Company Name')</label>
            <input type="text"
                   class="form-control form-control-lg"
                   id="signup-companyname"
                   wire:model.defer="company_name"
                   placeholder="@lang('company::home.Company Name')">
        </div>
        @error('company_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
        <div class= "col-xl-12 mt-2">
            <label for="signup-fullname" class="form-label text-default">@lang('company::home.Full Name')</label>
            <input type="text"
                   class="form-control form-control-lg"
                   id="signup-fullname"
                   wire:model.defer="name"
                   placeholder="@lang('company::home.Full Name')">
        </div>
        @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror


        <div class="col-xl-12">
            <label for="signup-email" class="form-label text-default">@lang('company::home.Email Address')</label>
            <input type="email"
                   class="form-control form-control-lg"
                   id="signup-email"
                   wire:model.defer="email"
                   placeholder="@lang('company::home.Email Address')">
        </div>
        @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror

        <div class="col-xl-12">
            <label for="signup-phone" class="form-label text-default">@lang('company::home.Phone Number')</label>
            <div class="input-group" dir="ltr">
                <span style="margin: 13px 10px 0px 13px; display: inline-block">(<img style="margin: 0px 2px 4px 4px" src="{{ asset('assets/vendor') }}/images/flags/ar.jpg" width="20"> +966 ) -</span>
                <input type="tel"
                       class="form-control form-control-lg br-10"
                       id="signup-phone"
                       wire:model.defer="phone"
                       placeholder="@lang('company::home.example') 512345678">

            </div>
        </div>
        @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror

        <div class="col-xl-4">
            <label for="branch-country"
                   class="form-label text-black ">@lang('marketplace::home.Country') <span class="text-danger">*</span></label>

            <div wire:ignore>
                <select class="form-control selectCountry"
                        data-trigger
                        name="country_id"
                        id="branch-country-add">
                    <option value="">@lang('marketplace::home.Select')</option>
                    @foreach($countries as $country)
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

        <div class="col-xl-4">
            <div>
                <label for="branch-cityasa"
                       class="form-label text-black">@lang('marketplace::home.District') <span class="text-danger">*</span></label>
                <select class="form-control selectDistrict"
                        wire:model.lazy="district_id"
                        id="branch-cityasa">
                    <option value="">@lang('marketplace::home.Select')</option>
                    @foreach($districts as $district)
                        <option
                            value="{{ $district->id }}">{{ $district->title }}</option>
                    @endforeach
                </select>
            </div>
            @error('district_id') <span
                class="d-inline-block text-danger">{{ $message }}</span> @enderror
        </div>


        <div class="col-xl-12">
            <label for="signup-password" class="form-label text-default">@lang('company::home.Password')</label>
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
        <div class="col-xl-12 mb-3">
            <label for="signup-confirmpassword" class="form-label text-default">@lang('company::home.Confirm Password')</label>
            <div class="input-group">
                <input
                    type="password"
                    class="form-control form-control-lg br-10"
                    wire:model.defer="password_confirmation"
                    id="signup-confirmpassword" placeholder="*************">
                <button class="btn btn-light" onclick="createpassword('signup-confirmpassword',this)" type="button" id="button-addon21"><i class="ri-eye-off-line align-middle"></i></button>
            </div>
            @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            <div class="form-check mt-3">
                <input class="form-check-input" required type="checkbox" value="" id="defaultCheck1">
                <label class="form-check-label text-muted fw-normal " for="defaultCheck1">
                    @lang('company::home.By creating a account you agree to our ')<a href="#" class="text-success"><u>@lang('company::home.Terms & Conditions')</u></a> @lang('company::home.and') <a href="#" class="text-success"><u>@lang('company::home.Privacy Policy')</u></a>
                </label>
            </div>
        </div>

        <div class="col-xl-12 d-grid mt-2">

            <button type="submit" class="btn btn-lg btn-primary">
                                <span class="indicator-label" wire:loading.class="d-none">
                                   @lang('company::home.Create Account')
                                </span>
                <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('company::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
            </button>
        </div>
    </div>
</form>
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
