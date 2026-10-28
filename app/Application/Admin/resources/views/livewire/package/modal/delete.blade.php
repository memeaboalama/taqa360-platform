<div>
    <div class="modal fade" id="deletePackage"  wire:ignore.self
         data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Deactivate Package')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:loading.attr="disabled"></button>
                </div>
                <form wire:submit.prevent="destroy">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="container">
                                            <div class="alert bg-danger text-white text-center my-4">@lang('admin::lang.Are you sure deactivate this Package ?')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="destroy">@lang('admin::lang.Close')
                        </button>
<button type="submit" class="btn btn-danger" wire:loading.attr="disabled" wire:target="destroy">
                                <span class="indicator-label">
                                    @lang('admin::lang.Deactivate')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="destroy">
                                    <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
