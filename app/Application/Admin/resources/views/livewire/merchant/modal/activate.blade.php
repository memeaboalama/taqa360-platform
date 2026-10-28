<div>
    <div class="modal fade" id="activateMerchant"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">{{ __('admin::home.activate_merchant') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="activate"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="activate">
                    <div class="modal-body">
                        <div class="text-center fs-15 fw-medium my-4">{{ __('admin::home.are_you_sure_activate') }}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="activate">{{ __('admin::lang.Close') }}
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="activate">
                                    {{ __('admin::lang.Activate') }}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="activate">
                                    {{ __('admin::lang.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
