<div>
    <form method="POST" wire:submit.prevent="resetPassword">
        <div class="row gy-3">

            <div class="col-xl-12">
                <input
                    type="hidden"
                    class="form-control form-control-lg"
                    wire:model.defer="phone">
            </div>

            <div class="col-xl-12">
                <label for="new-password" class="form-label text-default">@lang('company::home.New Password')</label>
                <div class="input-group">
                    <input
                        type="password"
                        class="form-control form-control-lg"
                        id="new-password"
                        wire:model.defer="password"
                        placeholder="************">
                    <button class="btn btn-light" onclick="createpassword('new-password',this)" type="button" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                </div>
                @error('new_password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="col-xl-12 mb-3">
                <label for="new-confirmpassword" class="form-label text-default">@lang('company::home.Confirm New Password')</label>
                <div class="input-group">
                    <input
                        type="password"
                        class="form-control form-control-lg"
                        wire:model.defer="password_confirmation"
                        id="new-confirmpassword" placeholder="************">
                    <button class="btn btn-light" onclick="createpassword('new-confirmpassword',this)" type="button" id="button-addon21"><i class="ri-eye-off-line align-middle"></i></button>
                </div>
                @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="col-xl-12 d-grid mt-2">
                <button type="submit" class="btn btn-lg btn-primary bg-primary">
                    <span class="indicator-label">
                        @lang('company::home.Save')
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
