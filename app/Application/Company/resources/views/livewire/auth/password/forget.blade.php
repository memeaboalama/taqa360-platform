<div>
    <form method="POST" wire:submit.prevent="forget">
        <div class="row gy-3">

            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12" >
                        <label for="signup-phone" class="form-label text-default">@lang('company::home.Phone Number')</label>
                        <div class="input-group" dir="ltr">
                            <span style="margin: 13px 10px 0px 13px; display: inline-block">(<img style="margin: 0px 2px 4px 4px" src="{{ asset('assets/vendor') }}/images/flags/ar.jpg" width="20"> +966 ) -</span>
                            <input type="tel"
                                   class="form-control form-control-lg br-10 @error('phone') is-invalid @enderror"
                                   id="signup-phone"
                                   wire:model.defer="phone"
                                   placeholder="@lang('company::home.example') 512345678">

                        </div>
                    </div>
                    @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror

                </div>
            </div>
            <div class="col-xl-12 d-grid mt-2">
                <button type="submit" class="btn btn-lg btn-primary bg-primary">
                    <span class="indicator-label" wire:loading.class="d-none">
                        @lang('company::home.Send')
                    </span>
                    <span class="d-none" wire:loading.class.remove="d-none">
                        @lang('company::home.wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
