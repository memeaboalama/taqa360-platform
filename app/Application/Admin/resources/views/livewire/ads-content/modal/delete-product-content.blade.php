<div class="modal fade" id="deleteProductContentModal" tabindex="-1" aria-modal="true" role="dialog" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger-transparent">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="fe fe-trash-2 me-2"></i>{{ __('admin::lang.Delete Product') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled"></button>
            </div>

            <div class="modal-body p-4 text-center">
                <i class="fe fe-alert-triangle fs-50 text-warning mb-3 d-block"></i>
                <h5 class="mb-2">{{ __('admin::lang.Are you sure?') }}</h5>
                <p class="text-muted">
                    {{ __('admin::lang.This action cannot be undone') }}
                </p>
            </div>

            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" wire:loading.attr="disabled">{{ __('admin::lang.Cancel') }}</button>
                <button type="button" class="btn btn-sm btn-danger" wire:click="delete" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="delete">
                        <i class="fe fe-trash me-1"></i>{{ __('admin::lang.Delete') }}
                    </span>
                    <span wire:loading wire:target="delete">
                        <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::lang.Deleting') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
