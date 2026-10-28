<form method="POST" wire:submit.prevent="register">
    <div class="row gy-3">
        <div class= "col-xl-12 mt-0">
            <label for="signup-fullname" class="form-label text-default">Full Name</label>
            <input type="text"
                   class="form-control form-control-lg"
                   id="signup-fullname"
                   wire:model.defer="user_name"
                   placeholder="Full name">
        </div>
        @error('user_name') <span class="d-block text-danger">{{ $message }}</span>@enderror


        <div class="col-xl-12">
            <label for="signup-email" class="form-label text-default">Email Address</label>
            <input type="email"
                   class="form-control form-control-lg"
                   id="signup-email"
                   wire:model.defer="email"
                   placeholder="Email Address">
        </div>
        @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror

        <div class="col-xl-12">
            <label for="signup-phone" class="form-label text-default">Phone Number</label>
            <input type="text"
                   class="form-control form-control-lg"
                   id="signup-phone"
                   wire:model.defer="phone"
                   placeholder="Phone Number">
        </div>
        @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror


        <div class="col-xl-12">
            <label for="signup-companyname" class="form-label text-default">Company name</label>
            <input type="text"
                   class="form-control form-control-lg"
                   id="signup-companyname"
                   wire:model.defer="company_name"
                   placeholder="Company name">
        </div>
        @error('company_name') <span class="d-block text-danger">{{ $message }}</span>@enderror

        <div class="col-xl-12">
            <label for="signup-phone" class="form-label text-default">Country</label>
            <select
                wire:model.defer="country_id"
                class="form-control form-control-lg">
                <option selected value="">---Select Country---</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->title }}</option>
                @endforeach
            </select>
        </div>
        @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror


        <div class="col-xl-12">
            <label for="signup-password" class="form-label text-default">Password</label>
            <div class="input-group">
                <input
                    type="password"
                    class="form-control form-control-lg"
                    id="signup-password"
                    wire:model.defer="password"
                    placeholder="password">
                <button class="btn btn-light" onclick="createpassword('signup-password',this)" type="button" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
            </div>
            @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-xl-12 mb-3">
            <label for="signup-confirmpassword" class="form-label text-default">Confirm Password</label>
            <div class="input-group">
                <input
                    type="password"
                    class="form-control form-control-lg"
                    wire:model.defer="password_confirmation"
                    id="signup-confirmpassword" placeholder="confirm password">
                <button class="btn btn-light" onclick="createpassword('signup-confirmpassword',this)" type="button" id="button-addon21"><i class="ri-eye-off-line align-middle"></i></button>
            </div>
            @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                    By creating a account you agree to our <a href="#" class="text-success"><u>Terms & Conditions</u></a> and <a class="text-success"><u>Privacy Policy</u></a>
                </label>
            </div>
        </div>
        <div class="col-xl-12 d-grid mt-2">
            <button class="btn btn-lg btn-primary">Create Account</button>
        </div>
    </div>
</form>
