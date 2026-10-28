<div class="modal fade" id="specLibraryModal" data-bs-backdrop="true" data-bs-keyboard="true" tabindex="-1" aria-labelledby="specLibraryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="specLibraryModalLabel">
                    <i class="ri-book-line me-2"></i>
                    {{ __('admin::home.add_from_library') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="specSearchInput" class="form-label text-default">
                        {{ __('admin::home.search_specification_template') }}
                    </label>
                    <input type="text" class="form-control form-control-lg" autocomplete="on"
                           id="specSearchInput" placeholder="{{ __('admin::home.search_placeholder') }}">
                </div>

                <div id="specSearchResult" class="d-none border rounded p-3 bg-light">
                    <h6 class="text-default">{{ __('admin::home.selected_specification') }}:</h6>
                    <p class="mb-1"><strong id="selectedSpecName" class="text-primary"></strong></p>
                    <small class="text-muted">{{ __('admin::home.specification_copy_note') }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="selectedMasterSpecId">
                <input type="hidden" id="selected_item_id">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    {{ __('admin::home.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="attachSpecBtn" disabled>
                    <i class="ri-links-line me-1"></i>
                    {{ __('admin::home.attach_to_item') }}
                </button>
            </div>
        </div>
    </div>
</div>
