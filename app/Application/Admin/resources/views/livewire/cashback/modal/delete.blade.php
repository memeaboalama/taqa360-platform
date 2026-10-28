<div>
    <div class="modal fade" id="deleteCashBack"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Deactivate CashBack')</h6>
                    <button type="button" class="btn-close" id="deleteCashBackCloseHeaderBtn" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="destroy"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="destroy">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="container">
                                            <div class="text-center text-danger my-4">{{__('admin::home.Are you sure delete ?')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="deleteCashBackCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="destroy">{{__('admin::home.Close')}}
                        </button>
                        <button type="submit" class="btn btn-danger" id="deleteCashBackSubmitBtn"
                                wire:loading.attr="disabled" wire:target="destroy">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="destroy">
                                    {{__('admin::home.Delete')}}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="destroy">
                                    {{__('admin::home.Wait')}} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
