<div>
    <form method="POST" wire:submit.prevent="forget">
        <div class="row gy-3">

            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        <label for="email" class="form-label text-default">{{ __('admin::home.Email Address') }}</label>
                        <input type="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               id="email"
                               wire:model.defer="email"
                               placeholder="@lang('admin::lang.Enter your Email Address')">
                        @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
                    </div>

                </div>
            </div>
            <div class="col-xl-12 d-grid mt-2">
                <button type="submit" class="btn btn-lg btn-primary bg-primary">
                    <span class="indicator-label" wire:loading.class="d-none">
                        @lang('admin::lang.Send')
                    </span>
                    <span class="d-none" wire:loading.class.remove="d-none">
                        @lang('admin::lang.Please Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
