<div class="modal fade" id="attachmentModal" tabindex="-1" aria-labelledby="attachmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="attachmentModalLabel">{{ __('admin::home.add_attachment') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="attachmentForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="attachmentId" name="id">

                    <div class="card custom-card shadow-none border mb-0">
                        <div class="card-body">

                            <h6 class="fw-bold mb-3 text-primary"><i class="ri-file-text-line me-1"></i> {{ __('admin::home.attachment_details') }}</h6>
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label for="attachmentTitle" class="form-label text-black">{{ __('admin::home.title') }}</label>
                                    <input type="text" class="form-control" id="attachmentTitle" name="title" placeholder="{{ __('admin::home.image_title') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="attachmentCollection" class="form-label text-black">{{ __('admin::home.collection') }}</label>
                                    <input type="text" class="form-control" id="attachmentCollection" name="collection_name" placeholder="{{ __('admin::home.gallery_docs_placeholder') }}">
                                </div>
                                <div class="col-12">
                                    <label for="attachmentDescription" class="form-label text-black">{{ __('admin::home.description') }}</label>
                                    <textarea class="form-control" id="attachmentDescription" name="description" rows="2" placeholder="{{ __('admin::home.description_placeholder') }}"></textarea>
                                </div>
                            </div>

                            <hr class="my-4 dashed">

                            <h6 class="fw-bold mb-3 text-primary"><i class="ri-upload-cloud-line me-1"></i> {{ __('admin::home.source') }}</h6>

                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="attachment_type" id="typeFile" value="file" checked onclick="toggleAttachmentSource('file')">
                                    <label class="form-check-label" for="typeFile">{{ __('admin::home.upload_file') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="attachment_type" id="typeUrl" value="url" onclick="toggleAttachmentSource('url')">
                                    <label class="form-check-label" for="typeUrl">{{ __('admin::home.external_url') }}</label>
                                </div>
                            </div>

                            <div id="fileInputSection">
                                <label for="attachmentFile" class="form-label text-muted small">{{ __('admin::home.choose_file') }}</label>
                                <input type="file" class="form-control" id="attachmentFile" name="file">
                            </div>

                            <div id="urlInputSection" style="display: none;">
                                <label for="attachmentUrl" class="form-label text-muted small">{{ __('admin::home.enter_direct_url') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-link"></i></span>
                                    <input type="url" class="form-control" id="attachmentUrl" name="url" placeholder="https://example.com/image.png">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ __('admin::home.close') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('admin::home.save_attachment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function toggleAttachmentSource(type) {
        if (type === 'file') {
            document.getElementById('fileInputSection').style.display = 'block';
            document.getElementById('urlInputSection').style.display = 'none';
            document.getElementById('attachmentUrl').value = '';
        } else {
            document.getElementById('fileInputSection').style.display = 'none';
            document.getElementById('urlInputSection').style.display = 'block';
            document.getElementById('attachmentFile').value = '';
        }
    }
</script>
