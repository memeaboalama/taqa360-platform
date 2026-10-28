<div class="modal fade" id="deleteCategoryContentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger-transparent">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="fe fe-trash-2 me-2"></i>{{ __('admin::lang.Delete Content') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="text-danger mb-3">
                    <i class="fe fe-alert-triangle fs-1"></i>
                </div>
                <h5>{{ __('admin::lang.Are you sure?') }}</h5>
                <p class="text-muted mb-0">{{ __('admin::lang.This action cannot be undone') }}</p>
            </div>
            <div class="modal-footer bg-light py-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('admin::home.Close') }}</button>
                <button type="button" class="btn btn-sm btn-danger" wire:click="destroy">
                    <span wire:loading.remove wire:target="destroy">{{ __('admin::lang.Confirm Delete') }}</span>
                    <span wire:loading wire:target="destroy">
                        <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::home.Wait') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
