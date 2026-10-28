<div>
    <div class="modal fade" id="updatePasswordEmployee" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('marketplace::home.Update Password')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:loading.attr="disabled" wire:target="store"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-12">
                                                <label for="update-employee-password"
                                                       class="form-label text-black">{{ __('marketplace::home.Password') }} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="update-employee-password"
                                                           placeholder="************"
                                                           wire:model.defer="password"
                                                    >
                                                    <button class="btn btn-light" onclick="createpassword('update-employee-password',this)" type="button"><i class="ri-eye-off-line align-middle"></i></button>
                                                </div>
                                                @error('password') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-12">
                                                <label for="update-employee-password-confirm" class="form-label text-black">{{ __('marketplace::home.Confirm Password') }} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="update-employee-password-confirm"
                                                           placeholder="************"
                                                           wire:model.defer="password_confirmation">
                                                    <button class="btn btn-light" onclick="createpassword('update-employee-password-confirm',this)" type="button"><i class="ri-eye-off-line align-middle"></i></button>
                                                </div>
                                                @error('password_confirmation') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">@lang('marketplace::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    @lang('marketplace::home.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
