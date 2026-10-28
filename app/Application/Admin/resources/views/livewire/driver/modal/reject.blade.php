<div>
    <div class="modal fade" id="rejectDriver"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="rejectDriverLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="rejectDriverLabel">@lang('admin::home.reject_driver')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:click="closeRejectModal" wire:target="reject" wire:loading.attr="disabled"></button>
                </div>
                <form wire:submit.prevent="reject">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="container">
                                            <div class="text-center my-3" role="alert">
                                                @lang('admin::home.Are you sure to reject ?')
                                            </div>
                                            <div class="text-start">
                                                <label for="driver-rejection-reason" class="form-label text-black">@lang('admin::home.rejection_reason') <span class="text-danger">*</span></label>
                                                <textarea id="driver-rejection-reason" rows="4"
                                                          class="form-control @error('rejection_reason') is-invalid @enderror"
                                                          placeholder="@lang('admin::home.rejection_reason_placeholder')"
                                                          wire:model.defer="rejection_reason"></textarea>
                                                @error('rejection_reason') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:click="closeRejectModal" wire:target="reject" wire:loading.attr="disabled">@lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-danger" wire:target="reject" wire:loading.attr="disabled">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="reject">
                                     @lang('admin::home.reject')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="reject">
                                    @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
