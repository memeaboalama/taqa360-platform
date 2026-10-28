<div>

    <div class="mt-0">
        <form method="POST" wire:submit.prevent="verify">
            <div class="row gy-3">
                <div class="col-xl-8">
                    <label for="otp" class="form-label text-default">OTP</label>
                    <input type="text"
                           class="form-control form-control-lg @error('otp') is-invalid @enderror"
                           id="otp"
                           wire:model.defer="otp"
                           placeholder="@lang('driver::home.example') 1234">
                    @error('otp') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-xl-4">
                    <label for="otp" class="form-label text-default">@lang('driver::home.Resend ?')</label>
                    <p>@lang("driver::home.Didn't receive the verification code? It could take a bit of time,")<span><a wire:click="$emit('resend_otp')" style="color: #0a58ca; cursor: pointer">@lang('driver::home.request a new code')</a></span></p>
                </div>
            </div>

            <div class="row gy-3 mt-3">
                <div class="col-xl-12 d-grid mt-2">
                    <button type="submit" class="btn btn-lg btn-primary bg-primary">
                    <span class="indicator-label">
                        @lang('driver::home.Send')
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
