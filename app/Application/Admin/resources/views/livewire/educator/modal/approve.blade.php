<div>
    <div class="modal fade" id="approveEducator" wire:ignore.self
         data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="approveEducatorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="approveEducatorLabel">@lang('admin::home.approve')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:loading.attr="disabled" wire:target="accept"></button>
                </div>
                <form wire:submit.prevent="accept">
                    <div class="modal-body">
                        <p class="text-center fs-5 mb-0">@lang('admin::home.Are you sure to approve ?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="accept">@lang('admin::home.Close')</button>
                        <button type="submit" class="btn btn-success"
                                wire:loading.attr="disabled" wire:target="accept">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="accept">
                                @lang('admin::home.approve')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="accept">
                                @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>