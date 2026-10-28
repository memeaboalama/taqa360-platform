<div>
    <div class="modal fade" id="deleteEducator"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Delete Educator')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:target="destroy" wire:loading.attr="disabled"></button>
                </div>
                <form wire:submit.prevent="destroy">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="container">
                                            <div class="text-center my-4">
                                                @lang('admin::home.Are you sure delete ?')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                                wire:target="destroy" wire:loading.attr="disabled">
                            @lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-danger" wire:target="destroy" wire:loading.attr="disabled">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('admin::home.Delete')
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('admin::home.Wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
