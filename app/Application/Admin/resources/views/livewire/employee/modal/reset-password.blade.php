<div>
    <div class="modal fade" id="resetEmployeePassword" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="resetEmployeePasswordLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="resetEmployeePasswordLabel">{{ __('admin::home.reset_employee_password') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="store"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label for="reset-employee-password" class="form-label text-black">{{ __('admin::home.Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="{{ $show_password ? 'text' : 'password' }}"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="reset-employee-password"
                                           placeholder="************"
                                           wire:model.defer="password">
                                    <span class="input-group-text @error('password') is-invalid @enderror" style="cursor: pointer"
                                          wire:click="$set('show_password', {{ $show_password ? 'false' : 'true' }})">
                                        <i class="{{ $show_password ? 'ri-eye-off-line' : 'ri-eye-line' }}"></i>
                                    </span>
                                </div>
                                @error('password') <span
                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-12">
                                <label for="reset-employee-password-confirm" class="form-label text-black">{{ __('admin::home.Confirm Password') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="{{ $show_password_confirmation ? 'text' : 'password' }}"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           id="reset-employee-password-confirm"
                                           placeholder="************"
                                           wire:model.defer="password_confirmation">
                                    <span class="input-group-text @error('password_confirmation') is-invalid @enderror" style="cursor: pointer"
                                          wire:click="$set('show_password_confirmation', {{ $show_password_confirmation ? 'false' : 'true' }})">
                                        <i class="{{ $show_password_confirmation ? 'ri-eye-off-line' : 'ri-eye-line' }}"></i>
                                    </span>
                                </div>
                                @error('password_confirmation') <span
                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">{{ __('admin::lang.Close') }}
                        </button>
                        <button type="submit" class="btn btn-success"
                                wire:loading.attr="disabled" wire:target="store">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    {{ __('admin::home.reset_password') }}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    {{ __('admin::lang.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
