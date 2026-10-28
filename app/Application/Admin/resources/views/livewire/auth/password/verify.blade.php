<div>
    <form method="POST" wire:submit.prevent="verify">
        <div class="row gy-3">

            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-8">
                        <label for="email" class="form-label text-default">{{ __('admin::home.Email Address') }}</label>
                        <input type="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               id="email"
                               disabled
                               wire:model.defer="email"
                               placeholder="Email Address">
                        @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-xl-4">
                        <label for="otp" class="form-label text-default">Resend ?</label>
                        <p>Didn't receive the verification code? It could take a bit of time,<span><a wire:click="$emit('resend_otp')" style="color: #0a58ca; cursor: pointer">request a new code</a></span></p>
                    </div>

                </div>
            </div>

            <div class="col-xl-12">
                <label for="otp" class="form-label text-default">OTP</label>
                <input type="text"
                       class="form-control form-control-lg @error('otp') is-invalid @enderror"
                       id="otp"
                       wire:model.defer="otp"
                       placeholder="example 1234">
                @error('otp') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="col-xl-12 d-grid mt-2">
                <button type="submit" class="btn btn-lg btn-primary bg-primary">
                    <span class="indicator-label" wire:loading.class="d-none">
                        Send
                    </span>
                    <span class="d-none" wire:loading.class.remove="d-none">
                        Wait <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
