<div class="mt-0">
    <form method="POST" wire:submit.prevent="updatePassword">
        <div class="row gy-3">
            <div class="col-xl-8">
                <label for="old_password" class="form-label text-default">{{ __('marketplace::home.Old Password') }}</label>
                <input
                    type="password"
                    class="form-control form-control-lg @error('old_password') is-invalid @enderror"
                    id="old_password"
                    wire:model.defer="old_password"
                    placeholder="{{ __('marketplace::home.Old Password') }}">
                @error('old_password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="row gy-3 mt-3">
            <div class="col-xl-8">
                <label for="new_password" class="form-label text-default">{{ __('marketplace::home.New Password') }}</label>
                <input
                    type="password"
                    class="form-control form-control-lg @error('new_password') is-invalid @enderror"
                    id="new_password"
                    wire:model.defer="new_password"
                    placeholder="{{ __('marketplace::home.New Password') }}">
                @error('new_password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="row gy-3 mt-3">
            <div class="col-xl-8 mb-3">
                <label for="confirmpassword" class="form-label text-default">{{ __('marketplace::home.Confirm New Password') }}</label>
                <input
                    type="password"
                    class="form-control form-control-lg @error('new_password') is-invalid @enderror"
                    wire:model.defer="new_password_confirmation"
                    id="confirmpassword" placeholder="{{ __('marketplace::home.Confirm New Password') }}">
                @error('new_password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="row gy-3 mt-3">
            <div class="col-xl-4 d-grid mt-4">
                <button class="btn btn-lg btn-primary">{{ __('marketplace::home.Save') }}</button>
            </div>
        </div>
    </form>
</div>
