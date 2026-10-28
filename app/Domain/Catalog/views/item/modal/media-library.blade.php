<div class="modal fade" id="mediaLibraryModal" tabindex="-1" aria-labelledby="mediaLibraryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="mediaLibraryModalLabel">{{ __('admin::home.media_library') }}</h6>
                <input type="text" id="media-library-search" class="form-control w-25 ms-auto" placeholder="{{ __('admin::home.search_placeholder') }}">
                <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div id="media-library-grid" class="row g-2"></div>
                <div id="media-library-pagination" class="mt-3 d-flex justify-content-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('admin::home.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="attachSelectedBtn" disabled>{{ __('admin::home.attach_selected') }}</button>
            </div>
        </div>
    </div>
</div>
