<div class="modal fade" id="deleteSpaceModal" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin::home.Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin::home.Are you sure you want to delete this item?') }}</p>
                <p class="text-danger"><small>{{ __('admin::lang.This will also delete all content in this space') }}</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:loading.attr="disabled">{{ __('admin::home.close') }}</button>
                <button type="button" class="btn btn-danger" wire:click="delete" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="delete">
                        <i class="fe fe-trash me-1"></i>{{ __('admin::home.delete') }}
                    </span>
                    <span wire:loading wire:target="delete">
                        <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::home.delete') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
