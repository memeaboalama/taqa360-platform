<div>
    @if($redirecting)
        <div style="position: fixed; inset: 0; z-index: 99999; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
            <div class="mb-4">
                <span class="avatar avatar-rounded bg-primary-transparent d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="ri-user-smile-line fs-1"></i>
                </span>
            </div>
            <h3 class="fw-bolder mb-1" style="color: #000;">@lang('marketplace::home.welcome_back_msg', ['name' => $welcomeName])</h3>
            <p class="text-muted mb-4">@lang('marketplace::home.redirecting_msg')</p>
            <div class="spinner-border text-primary" role="status"><span class="visually-hidden">...</span></div>
        </div>
        <script>
            setTimeout(function () {
                window.location.href = @json($redirectUrl);
            }, 1500);
        </script>
    @else
    <form method="POST" action="{{ route('marketplace:login.submit') }}" wire:submit.prevent="store">
        @csrf
        <div class="row gy-3">

            <div class="col-xl-12">
                <label for="signup-email" class="form-label text-default fw-bolder">@lang('marketplace::home.Email Address')</label>
                <input type="email"
                       class="form-control form-control-lg"
                       id="signup-email"
                       name="email"
                       wire:model.defer="email"
                       placeholder="@lang('marketplace::home.Email Address')">
                @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="col-xl-12">
                <label for="signup-password" class="form-label text-default d-block fw-bolder">@lang('marketplace::home.Password')<a href="{{ route('marketplace:forgetPassword') }}" class="float-end text-danger">@lang('marketplace::home.Forget password ?')</a></label>
                <div class="input-group">
                    <input type="password" class="form-control form-control-lg" id="signup-password"
                           name="password"
                           wire:model.defer="password"
                           placeholder="***********">
                    <button class="btn btn-light" onclick="createpassword('signup-password',this)" type="button"
                            id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                </div>
                @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-xl-12 d-grid mt-2">

                <button type="submit" class="btn btn-lg btn-primary">
                                <span class="indicator-label fw-bolder" wire:loading.class="d-none">
                                    @lang('marketplace::home.Sign In')
                                </span>
                    <span class="d-none fw-bolder" wire:loading.class.remove="d-none">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                </button>
            </div>
        </div>
    </form>
    @endif
</div>
