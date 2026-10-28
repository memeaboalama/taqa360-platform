<form method="POST" wire:submit.prevent="register">
    <div class="row gy-3">

        <div class="col-xl-12">
            <label for="signup-companyname" class="form-label text-default fw-bolder">@lang('marketplace::home.Restaurant Name')</label>
            <input type="text"
                   class="form-control form-control-lg br-10"
                   id="signup-companyname"
                   wire:model.defer="company_name"
                   placeholder="@lang('marketplace::home.Restaurant Name')">
        </div>
        @error('company_name') <span class="d-block text-danger">{{ $message }}</span>@enderror


        <div class= "col-xl-12">
            <label for="signup-fullname" class="form-label text-default fw-bolder">@lang('marketplace::home.Full Name')</label>
            <input type="text"
                   class="form-control form-control-lg br-10"
                   id="signup-fullname"
                   wire:model.defer="user_name"
                   placeholder="@lang('marketplace::home.Full Name')">
        </div>
        @error('user_name') <span class="d-block text-danger">{{ $message }}</span>@enderror


        <div class="col-xl-12">
            <label for="signup-email" class="form-label text-default fw-bolder">@lang('marketplace::home.Email Address')</label>
            <input type="email"
                   class="form-control form-control-lg br-10"
                   id="signup-email"
                   wire:model.defer="email"
                   placeholder="@lang('marketplace::home.Email Address')">
        </div>
        @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror

        <div class="col-xl-12" >
            <label for="signup-phone" class="form-label text-default fw-bolder">@lang('marketplace::home.Phone Number')</label>
            <div class="input-group" dir="ltr">
                <span style="margin: 13px 10px 0px 13px; display: inline-block">(<img style="margin: 0px 2px 4px 4px" src="{{ asset('assets/vendor') }}/images/flags/ar.jpg" width="20"> +966 ) -</span>
                <input type="tel"
                       class="form-control form-control-lg br-10"
                       id="signup-phone"
                       wire:model.defer="phone"
                       placeholder="@lang('marketplace::home.example') 512345678">

            </div>
        </div>
        @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror


        <div class="col-xl-12">
            <label for="signup-password" class="form-label text-default fw-bolder">@lang('marketplace::home.Password')</label>
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
            <label for="signup-confirmpassword" class="form-label text-default fw-bolder">@lang('marketplace::home.Confirm Password')</label>
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
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" wire:model.defer="terms">
                <label class="form-check-label text-muted fw-bolder " for="defaultCheck1">
                    @lang('marketplace::home.By creating a account you agree to our ')<a href="{{ route("marketplace:termsConditions") }}" target="_blank" class="text-success"><u>@lang('marketplace::home.Terms & Conditions')</u></a> @lang('marketplace::home.and') <a href="{{ route("marketplace:privacyPolicy") }}" target="_blank"  class="text-success"><u>@lang('marketplace::home.Privacy Policy')</u></a>
                </label>
                @error('terms') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="col-xl-12 d-grid mt-2">

            <button type="submit" class="btn btn-lg btn-primary">
                                <span class="indicator-label fw-bolder" wire:loading.class="d-none">
                                   @lang('marketplace::home.Create Account')
                                </span>
                <span class="d-none fw-bolder" wire:loading.class.remove="d-none">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
            </button>
        </div>
    </div>
</form>
