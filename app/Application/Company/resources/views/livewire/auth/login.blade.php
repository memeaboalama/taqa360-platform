<div>
    @if($redirecting)
        <div style="position: fixed; inset: 0; z-index: 99999; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
            <div class="mb-4">
                <span class="avatar avatar-rounded bg-primary-transparent d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="ri-user-smile-line fs-1"></i>
                </span>
            </div>
            <h3 class="fw-bolder mb-1" style="color: #000;">@lang('company::home.welcome_back_msg', ['name' => $welcomeName])</h3>
            <p class="text-muted mb-4">@lang('company::home.redirecting_msg')</p>
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">...</span></div>
        </div>
        <script>
            setTimeout(function () {
                window.location.href = @json($redirectUrl);
            }, 1500);
        </script>
    @else
    <form method="POST" wire:submit.prevent="store">
        <div class="row gy-3">

            <div class="col-xl-12">
                <label for="signup-email" class="form-label text-default">@lang('company::home.Email Address')</label>
                <input type="email"
                       class="form-control form-control-lg"
                       id="signup-email"
                       wire:model.defer="email"
                       placeholder="@lang('company::home.Email Address')">
                @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="col-xl-12">
                <label for="signup-password" class="form-label text-default d-block">@lang('company::home.Password')<a href="{{ route('company:forgetPassword') }}" class="float-end text-danger">@lang('company::home.Forget password ?')</a></label>
                <div class="input-group">
                    <input type="password" class="form-control form-control-lg" id="signup-password"
                           wire:model.defer="password"
                           placeholder="***********">
                    <button class="btn btn-light" onclick="createpassword('signup-password',this)" type="button"
                            id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                </div>
                @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-xl-12 d-grid mt-2">

                <button type="submit" class="btn btn-lg btn-primary bg-primary">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('company::home.Sign In')
                                </span>
                    <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('company::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                </button>
            </div>
        </div>
    </form>
    @endif
</div>
