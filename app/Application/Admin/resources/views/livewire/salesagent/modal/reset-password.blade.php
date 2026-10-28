<div>
    <div class="modal fade" id="resetSalesAgentPassword" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="resetPasswordLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="resetPasswordLabel">{{__('admin::home.Reset Password')}}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="resetPassword">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-12">
                                                <label for="reset-new-password"
                                                       class="form-label text-black">{{__('admin::home.New Password')}} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="reset-new-password"
                                                           placeholder="************"
                                                           wire:model.defer="password">
                                                    <button class="btn btn-light" onclick="createpassword('reset-new-password',this)" type="button"
                                                            id="button-addon2"><i class="ri-eye-line align-middle"></i></button>
                                                </div>
                                                @error('password') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-12">
                                                <label for="reset-password-confirm" class="form-label text-black">{{__('admin::home.Confirm Password')}} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="reset-password-confirm"
                                                           placeholder="************"
                                                           wire:model.defer="password_confirmation">
                                                    <button class="btn btn-light" onclick="createpassword('reset-password-confirm',this)" type="button"
                                                            id="button-addon21"><i class="ri-eye-line align-middle"></i></button>
                                                </div>
                                                @error('password') <span
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
                                data-bs-dismiss="modal">{{__('admin::home.Close')}}
                        </button>
                        <button type="submit" class="btn btn-danger">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="resetPassword">
                                    {{__('admin::home.Reset Password')}}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="resetPassword">
                                    {{__('admin::home.Wait')}} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('js')
    <script src="{{ asset('assets/vendor/js/show-password.js') }}"></script>
    <script>
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form) return;
            let isLivewireForm = false;
            for (let i = 0; i < form.attributes.length; i++) {
                if (form.attributes[i].name.indexOf('wire:submit') === 0) {
                    isLivewireForm = true;
                    break;
                }
            }
            if (!isLivewireForm) return;
            const modal = form.closest('#resetSalesAgentPassword');
            if (modal) {
                modal.querySelectorAll('button').forEach(function (btn) {
                    if (!btn.disabled) {
                        btn.setAttribute('data-op-disabled', '1');
                        btn.disabled = true;
                    }
                });
            }
        });
        window.addEventListener('livewire:update', function () {
            document.querySelectorAll('#resetSalesAgentPassword button[data-op-disabled]').forEach(function (btn) {
                const modal = btn.closest('#resetSalesAgentPassword');
                const spinner = modal ? modal.querySelector('.spinner-border') : null;
                const spinnerVisible = spinner && spinner.offsetParent !== null;
                if (!spinnerVisible) {
                    btn.disabled = false;
                    btn.removeAttribute('data-op-disabled');
                }
            });
        });
    </script>
@endpush