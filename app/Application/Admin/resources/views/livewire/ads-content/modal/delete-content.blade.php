<div class="modal fade" id="deleteContentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin::lang.Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin::lang.Are you sure you want to delete this item?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin::lang.Close') }}</button>
                <button type="button" class="btn btn-danger" wire:click="delete">{{ __('admin::lang.Delete') }}</button>
            </div>
        </div>
    </div>
</div>
