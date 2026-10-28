





/**
 Item Attachment

 */
window.initItemAttachmentsInfo = function (options = {}) {
    const option = options;
    const {
        id: itemId = 0,
        name: itemName = '',
        infoModal = null,
        url,
        attachable = 'items',
        userType = null,
        path = null,
        entity='item',
        //    url = `${uri}/investor/investment-orders`,
        // headers = {
        //     'Accept': 'application/json; charset=utf-8',
        //     'Authorization': auth
        // },
        // getUrl
    } = options;
    // const itemId = $('#addAttachmentBtn').data('item-id');
    const attachmentModal = new bootstrap.Modal(document.getElementById('attachmentModal'));
    const libraryModal = new bootstrap.Modal(document.getElementById('mediaLibraryModal'));
    const list = infoModal.find('#attachments-list');
    let selectedLibraryAttachments = new Set();
    const deleteSelectedBtn = $('#deleteSelectedAttachmentBtn');
    const detachSelectedBtn = $('#detach-selected-attachment-btn');

    // --- Utility Functions ---
    function toggleDeleteButton() {
        const checked = list.find('.attachment-checkbox:checked').length;
        deleteSelectedBtn.toggleClass('d-none', checked === 0 || userType!=='admin');
        detachSelectedBtn.toggleClass('d-none', checked === 0 || !itemId || itemId===0 );
    }

    function loadAttachments() {
        list.html('<p>Loading attachments...</p>');
        $.get(`/${url}/${attachable}/${itemId}/attachments`, function (response) {
            list.empty();
            if (!response.data.data || response.data.data.length === 0) {
                list.html('<p>No attachments found.</p>');
                return;
            }
            response.data.data.forEach(att => list.append(createAttachmentHtml(att)));
            toggleDeleteButton();
        });
    }

    function createAttachmentHtml(att) {
        const previewHtml = att.thumbnail_url
            ? `<img src="${att.thumbnail_url}" class="img-thumbnail me-3" style="width:60px;height:60px;object-fit:cover;">`
            : `<i class="fas ${att.type === 'file' ? 'fa-file' : 'fa-link'} fa-2x me-3 text-secondary" style="width:60px;text-align:center;"></i>`;
        const primaryBadge = att.is_primary ? '<span class="badge bg-success ms-2">Primary</span>' : '';
        const setPrimaryBtnClass = att.is_primary ? 'disabled' : '';

        return `
            <div class="list-group-item d-flex justify-content-between align-items-center attachment-item" data-id="${att.id}">
                <div class="d-flex align-items-center">
                    <input type="checkbox" class="form-check-input me-3 attachment-checkbox">
                    <i class="fas fa-grip-vertical me-3 handle" style="cursor: grab;"></i>
                    ${previewHtml}
                    <div>
                        <a href="${att.url}" target="_blank">${att.title || att.original_name || att.url}</a> ${primaryBadge}
                        <p class="mb-0 text-muted small">${att.description || ''}</p>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary set-primary-btn ${setPrimaryBtnClass}" title="Set as Primary"><i class="ri-check-double-fill"></i></button>
                    <button class="btn btn-sm btn-outline-danger delete-att-btn" title="Delete"><i class="ri-delete-bin-line"></i></button>
                </div>
            </div>`;
    }

    function loadMediaLibrary(page = 1, search = '') {
        infoModal.find('#media-library-grid').html('<div class="text-center p-5"><span class="spinner-border"></span></div>');
        $.get(`/${url}/attachments/library?page=${page}&search=${search}`, function (response) {
            $('#media-library-grid').empty();
            response.data.data.forEach(att => {
                const isSelected = selectedLibraryAttachments.has(att.id);
                const previewHtml = att.thumbnail_url
            ? `<img src="${att.thumbnail_url}" class="card-img-top" style="aspect-ratio:1/1;object-fit:cover;">`
            : `<i class="fas ${att.type === 'file' ? 'fa-file' : 'fa-link'} fa-2x me-3 text-secondary" style="aspect-ratio:1/1;object-fit:cover;"></i>`;
            // <img src="${att.thumbnail_url}" class="card-img-top" style="aspect-ratio:1/1;object-fit:cover;">
                infoModal.find('#media-library-grid').append(`
                    <div class="col-2">
                        <div class="media-item card h-100 ${isSelected ? 'selected' : ''}" data-id="${att.id}" style="cursor:pointer;">
                            ${previewHtml}
                            <div class="media-item-check"><i class="fas fa-check-circle"></i></div>
                            <div class="card-body p-2"><p class="card-text small text-truncate" title="${att.title || att.original_name}">${att.title || att.original_name}</p></div>
                        </div>
                    </div>`);
            });
        });
    }

    // AJAX Setup
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Event Handlers
    infoModal.find('#addAttachmentBtn').on('click', () => { infoModal.find('#attachmentForm')[0].reset(); attachmentModal.show(); });
    infoModal.find('#addAttachmentFromLibraryBtn').on('click', () => { selectedLibraryAttachments.clear(); loadMediaLibrary(); libraryModal.show(); });
    infoModal.find('#attachmentForm').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $saveBtn = $form.find('button[type="submit"]');
        let $closeBtns = $form.closest('.modal').find('button[data-dismiss="modal"], .close');
        // ============ إضافة لودينج وتعطيل الأزرار ============
        $saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span> Saving...');
        $closeBtns.prop('disabled', true);
        $.ajax({
            url: `/${url}/${attachable}/${itemId}/attachments`,
            method: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: () => { attachmentModal.hide(); loadAttachments(); },
            complete: function() {
                $saveBtn.prop('disabled', false).html('Save');
                $closeBtns.prop('disabled', false);
            }

        });
    });

    list.on('click', '.delete-att-btn', function () {
        const id = $(this).closest('.attachment-item').data('id');
        if (confirm('Delete this attachment?')) {
            $.ajax({ url: `/${url}/attachments/${id}`, method: 'DELETE', success: () => $(this).closest('.attachment-item').remove() });
        }
    });

    list.on('click', '.set-primary-btn:not(.disabled)', function () {
        const id = $(this).closest('.attachment-item').data('id');
        $.post(`/${url}/${attachable}/${itemId}/attachments/${id}/set-primary`, () => loadAttachments());
    });

    // Media Library Events
    infoModal.find('#media-library-search').on('keyup', e => { if (e.key === 'Enter' || e.keyCode === 13) loadMediaLibrary(1, $(e.target).val()); });
    infoModal.find('#media-library-grid').on('click', '.media-item', function () {
        const id = $(this).data('id');
        $(this).toggleClass('selected');
        selectedLibraryAttachments.has(id) ? selectedLibraryAttachments.delete(id) : selectedLibraryAttachments.add(id);
        const count = selectedLibraryAttachments.size;
        infoModal.find('#attachSelectedBtn').prop('disabled', count === 0).text(`Attach ${count} Item(s)`);
    });

    infoModal.find('#attachSelectedBtn').on('click', function () {
        $.ajax({
            url: `/${url}/${attachable}/${itemId}/attachments/attach-existing`,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ attachment_ids: Array.from(selectedLibraryAttachments) }),
            success: () => { libraryModal.hide(); loadAttachments(); }
        });
    });
    detachSelectedBtn.on('click', function () {
        const selectedIds = list.find('.attachment-checkbox:checked')
            .map((i, el) => $(el).closest('.attachment-item').data('id'))
            .get();
        if (selectedIds.length > 0 && confirm(`Delete ${selectedIds.length} attachments?`)) {
            $.ajax({
                url: `/${url}/${attachable}/${itemId}/attachments/detach-existing`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ attachment_ids: selectedIds }),
                success: () => { loadAttachments(); }
            });
        }
    });

    // SortableJS
    new Sortable(list[0], {
        handle: '.handle', animation: 150,
        onEnd: () => {
            const ids = Array.from(list.find('.attachment-item')).map(item => $(item).data('id'));
            $.post(`/${url}/${attachable}/${itemId}/attachments/reorder`, { attachment_ids: ids });
        },
    });
    // Checkbox change
    list.on('change', '.attachment-checkbox', toggleDeleteButton);
    // Bulk Delete button
    deleteSelectedBtn.on('click', function () {
        const selectedIds = list.find('.attachment-checkbox:checked')
            .map((i, el) => $(el).closest('.attachment-item').data('id'))
            .get();

        if (selectedIds.length > 0 && confirm(`Delete ${selectedIds.length} attachments?`)) {
            $.ajax({
                url: `/${url}/attachments/bulk-delete`,
                method: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({ attachment_ids: selectedIds }),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    loadAttachments(); // Reload the list
                }
            });
        }
    });
    function init(){
        infoModal.find("#addAttachmentFromLibraryBtn").toggleClass('d-none', !itemId || itemId === 0);
        if(userType !== 'admin')
            deleteSelectedBtn.toggleClass('d-none', !itemId || itemId === 0 || userType !== 'admin');
    }
    init();
    // Initial Load
    loadAttachments();
    return {
        item: itemId,
        modal: attachmentModal,
        library: libraryModal,
        data: () => itemId || null,
    };
}