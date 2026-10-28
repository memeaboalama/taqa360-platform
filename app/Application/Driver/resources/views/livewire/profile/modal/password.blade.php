<div>
    <div class="modal fade" id="updatePasswordModal" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="updatePasswordModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="updatePasswordModalLabel">{{ __('driver::home.Update Password') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:loading.attr="disabled" wire:target="updatePassword"></button>
                </div>
                <form wire:submit.prevent="updatePassword">
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-xl-12">
                                <label for="password_current" class="form-label text-black">{{ __('driver::home.Old Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control @error('old_password') is-invalid @enderror"
                                           id="password_current"
                                           wire:model.defer="old_password"
                                           placeholder="{{ __('driver::home.Old Password') }}">
                                    <button type="button" class="btn btn-outline-primary password-toggle" data-target="password_current" style="border-color: var(--input-border); color: #6c757d; background-color: transparent;">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                @error('old_password')
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-xl-6">
                                <label for="password_new" class="form-label text-black">{{ __('driver::home.New Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control @error('new_password') is-invalid @enderror"
                                           id="password_new"
                                           wire:model.defer="new_password"
                                           placeholder="{{ __('driver::home.New Password') }}">
                                    <button type="button" class="btn btn-outline-primary password-toggle" data-target="password_new" style="border-color: var(--input-border); color: #6c757d; background-color: transparent;">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-xl-6">
                                <label for="password_confirm" class="form-label text-black">{{ __('driver::home.Confirm New Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password"
                                           class="form-control @error('new_password') is-invalid @enderror"
                                           id="password_confirm"
                                           wire:model.defer="new_password_confirmation"
                                           placeholder="{{ __('driver::home.Confirm New Password') }}">
                                    <button type="button" class="btn btn-outline-primary password-toggle" data-target="password_confirm" style="border-color: var(--input-border); color: #6c757d; background-color: transparent;">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="updatePassword"
                                style="border-color: var(--input-border); color: #6c757d; background-color: transparent;">
                            {{ __('driver::home.Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="updatePassword">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="updatePassword">
                                {{ __('driver::home.Save') }}
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="updatePassword">
                                {{ __('driver::home.Wait') }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>