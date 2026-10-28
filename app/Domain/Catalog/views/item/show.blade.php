@push('css')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
    :root {
        --primary-color: #f59e0b;
        --primary-light: #fffbeb;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --border-color: #e2e8f0;
    }

    body.modal-open { padding-right: 0 !important; overflow: hidden; }
    .modal-body { scrollbar-gutter: stable; }
    .modal.show { backdrop-filter: none !important; filter: none !important; }

    #item-info-modal-wrapper { z-index: 1040; }
    .modal-backdrop:nth-of-type(2) { z-index: 1050 !important; opacity: 0.6 !important; background-color: #000 !important; }
    #specificationModal, #attachmentModal, #specLibraryModal, #mediaLibraryModal { z-index: 1070 !important; }

    .modal-dialog .modal-content {
        background-color: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 20px 60px -10px rgba(0, 0, 0, 0.15) !important;
        border-radius: 10px;
        opacity: 1 !important;
    }

    #specifications-list {
        display: flex; flex-direction: column; gap: 10px; padding: 2px;
    }
    .original-spec-row { display: none !important; }

    .spec-accordion {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        transition: border-color 0.2s ease;
        position: relative;
    }
    .spec-accordion:hover { border-color: var(--primary-color); }

    .spec-acc-header {
        display: flex;
        align-items: center;
        padding: 14px 50px 14px 110px;
        cursor: pointer;
        background: #fff;
        transition: background 0.2s;
        user-select: none;
        height: 60px;
        position: relative;
    }
    .spec-acc-header:hover { background-color: #fcfcfd; }

    .spec-accordion.active .spec-acc-header {
        background-color: #fffcf5;
        border-bottom: 1px solid var(--border-color);
    }

    .acc-icon {
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px; color: var(--primary-color); font-size: 8px;
    }

    .acc-title {
        font-size: 14px; font-weight: 700; color: var(--text-dark); white-space: nowrap;
    }

    .acc-summary {
        margin-left: 15px; padding-left: 15px;
        border-left: 2px solid #f1f5f9;
        font-size: 13px; color: var(--text-gray);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        opacity: 1; transition: opacity 0.1s;
        flex-grow: 1;
    }
    .spec-accordion.active .acc-summary { opacity: 0; display: none; }

    .acc-actions-fixed {
        position: absolute;
        left: 15px; top: 50%; transform: translateY(-50%);
        display: flex; gap: 8px; z-index: 2;
    }

    .acc-chevron-fixed {
        position: absolute;
        right: 15px; top: 50%; transform: translateY(-50%);
        color: #cbd5e1; transition: transform 0.3s; font-size: 18px; z-index: 2;
    }
    .spec-accordion.active .acc-chevron-fixed {
        transform: translateY(-50%) rotate(180deg); color: var(--primary-color);
    }

    .spec-acc-body { display: none; padding: 0; }
    .spec-accordion.active .spec-acc-body { display: block; animation: slideDown 0.2s ease-out; }

    .spec-description-box {
        padding: 16px 20px;
        background-color: #fffbf0;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 13px;
        line-height: 1.6;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .spec-desc-icon {
        color: var(--primary-color);
        font-size: 16px;
        margin-top: 2px;
    }

    .pro-table { width: 100%; border-collapse: collapse; }
    .pro-table th, .pro-table td { padding: 12px 20px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
    .pro-table tr:last-child td { border-bottom: none; }
    .pro-key { width: 25%; background-color: #fcfcfd; color: var(--text-gray); font-weight: 500; border-right: 1px solid #f1f5f9; }
    .pro-value { width: 75%; color: var(--text-dark); font-weight: 600; font-family: 'Courier New', monospace; }

    .action-icon-btn {
        width: 32px; height: 32px; border: 1px solid #f1f5f9; background: #fff;
        border-radius: 6px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s; font-size: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .btn-edit-proxy { color: #3b82f6; background-color: #eff6ff; border-color: #dbeafe; }
    .btn-edit-proxy:hover { background-color: #3b82f6; color: #fff; transform: translateY(-1px); }
    .btn-delete-proxy { color: #ef4444; background-color: #fef2f2; border-color: #fee2e2; }
    .btn-delete-proxy:hover { background-color: #ef4444; color: #fff; transform: translateY(-1px); }

    @keyframes slideDown { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

    #attachments-list .list-group-item {
        border: 1px solid #eaedf1; margin-bottom: 8px; border-radius: 8px !important;
        display: flex; justify-content: space-between; align-items: center;
    }
    .ui-front { z-index: 9999999 !important; }


    .ui-widget-content {
            z-index: 10000000 !important;
        }

        .media-item {
            position: relative;
            border: 3px solid transparent;
            transition: border-color 0.2s ease;
        }

        .media-item.selected {
            
            border-color: var(--primary-color);
        }

        .media-item .media-item-check {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 1.5rem;
            color: var(--primary-color);
            background-color: white;
            border-radius: 50%;
            display: none;
            line-height: 1;
        }

        .media-item.selected .media-item-check {
            display: block;
        }
</style>
@endpush

<div class="col-xxl-12 col-xl-12" id="item-info-modal">
    <div class="modal fade" id="item-info-modal-wrapper" tabindex="-1" aria-labelledby="item-info-modal-label" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">

                <div class="modal-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center w-100">
                        <span class="avatar avatar-md bg-warning-transparent text-warning me-3 rounded-circle">
                            <i class="ri-settings-3-line fs-18"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span class="fs-12 text-muted text-uppercase ls-1 mb-1">{{ __('admin::home.item_details') }}</span>
                            <h4 class="modal-title fw-bolder text-dark mb-0" style="font-size: 1.25rem;">
                                <span id="info-item-name"></span>
                            </h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0" id="item-info-modal-body" style="background-color: #f8f9fa; overflow-y: auto;">
                    <input type="hidden" id="item-id" name="item_id">
                    <input type="hidden" id="item-name" name="item-name">

                    <div class="container-fluid p-4">
                        <div class="row justify-content-center">
                            <div class="col-xl-11">

                                <div class="card custom-card shadow-sm border-0">
                                    <div class="card-body p-0">

                                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between bg-white rounded-top">
                                            <div class="w-100 overflow-hidden">
                                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="nav-tab" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="nav-item-specifications-tab" data-bs-toggle="tab"
                                                                data-bs-target="#nav-item-specifications" type="button" role="tab">
                                                            <i class="ri-list-check me-1 align-middle d-inline-block"></i> {{ __('admin::home.specifications') }}
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="nav-item-attachments-tab" data-bs-toggle="tab"
                                                                data-bs-target="#nav-item-attachments" type="button" role="tab">
                                                            <i class="ri-attachment-2 me-1 align-middle d-inline-block"></i> {{ __('admin::home.attachments') }}
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="p-4 bg-white min-vh-50">
                                            <div class="tab-content" id="nav-tabContent">

                                                <div class="tab-pane fade show active" id="nav-item-specifications" role="tabpanel">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <div>
                                                            <h6 class="fw-bold mb-1 text-dark">{{ __('admin::home.specifications_list') }}</h6>
                                                            <p class="text-muted fs-12 mb-0">{{ __('admin::home.detailed_attributes') }}</p>
                                                        </div>
                                                        <div class="btn-list">
                                                            <button class="btn btn-success-light btn-wave" id="addFromLibraryBtn">
                                                                <i class="ri-book-mark-line align-middle me-1"></i> {{ __('admin::home.from_library') }}
                                                            </button>
                                                            <button class="btn btn-warning btn-wave text-white" id="addSpecificationBtn">
                                                                <i class="ri-add-line align-middle me-1"></i> {{ __('admin::home.add_new') }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div id="specifications-list">
                                                        <div class="text-center p-5">
                                                            <span class="spinner-border text-warning spinner-border-sm" role="status"></span>
                                                            <span class="ms-2 text-muted">Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="nav-item-attachments" role="tabpanel">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <div>
                                                            <h6 class="fw-bold mb-1 text-dark">{{ __('admin::home.attachments_list') }}</h6>
                                                            <p class="text-muted fs-12 mb-0">{{ __('admin::home.manage_files') }}</p>
                                                        </div>
                                                        <div class="btn-list d-flex gap-2">
                                                            <button class="btn btn-danger-light btn-wave d-none" id="deleteSelectedAttachmentBtn">{{ __('admin::home.delete') }}</button>
                                                            <button class="btn btn-warning-light btn-wave d-none" id="detach-selected-attachment-btn">{{ __('admin::home.detach') }}</button>
                                                            <button class="btn btn-info-light btn-wave" id="addAttachmentFromLibraryBtn">{{ __('admin::home.library') }}</button>
                                                            <button class="btn btn-warning btn-wave text-white" id="addAttachmentBtn">{{ __('admin::home.add_new') }}</button>
                                                        </div>
                                                    </div>
                                                    <div id="attachments-list" class="list-group list-group-flush">
                                                        <div class="text-center p-5">
                                                            <span class="spinner-border text-warning spinner-border-sm" role="status"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('catalog::item.modal.specification')
                    @include('catalog::item.modal.specification-library')
                    @include('catalog::item.modal.attachment')
                    @include('catalog::item.modal.media-library')

                </div>
            </div>
        </div>
    </div>
</div>

@push('js')

<script>
    window.lang = {
        property_name: "{{ __('admin::home.property_name') }}",
        property_value: "{{ __('admin::home.property_value') }}",
        delete: "{{ __('admin::home.delete') }}"
    };
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('assets/vendor/jquery.serializejson.js') }}"></script>
<script src="{{ asset('assets/catalog/item-specifications.js') }}"></script>
<script src="{{ asset('assets/catalog/item-attachments.js') }}"></script>
<script>
    window.initItemInfoModal = function(options = {}) {
        const { id: itemId = 0, name: itemName = '', userType='{{$userType ?? "admin"}}', path='{{$path ?? "admin"}}', entity='item' } = options;
        const url=`api/v1/${path}/catalog`;
        const wrapper = $('#item-info-modal-wrapper');
        const infoModal = $('#item-info-modal');
        const infoBody = $('#item-info-modal-body');
        infoBody.find("#item-id").val(itemId);
        infoBody.find("#item-name").val(itemName);
        infoModal.find("#info-item-name").text(itemName);
        infoModal.find('#addSpecificationBtn').off('click');
        infoModal.find('#specificationForm').off('submit');
        infoModal.find('#addAttachmentBtn').off('click');
        infoModal.find('#attachmentForm').off('submit');

        let specificationCompounent = null;
        let attachmentCompounent = null;
        try {
            specificationCompounent = window.initItemSpecificationsInfo({ id: itemId, name: itemName, infoModal: infoModal, url: url, attachable: 'items', userType: userType, path: path, entity: entity });
        } catch (e) { console.error('initItemSpecificationsInfo error', e); }
        try {
            attachmentCompounent = window.initItemAttachmentsInfo({ id: itemId, name: itemName, infoModal: infoModal, url: url, attachable: 'items', userType: userType, path: path, entity: entity });
        } catch (e) { console.error('initItemAttachmentsInfo error', e); }

        const bootstrapModal = bootstrap.Modal.getOrCreateInstance(wrapper[0]);
        bootstrapModal.show();
        return { item: itemId, modal: bootstrapModal, specification: specificationCompounent, attachment: attachmentCompounent, data: () => itemId || null };
    };

    $(document).ready(function() {
        $('#item-info-modal-wrapper').on('show.bs.modal', function () { $('body').addClass('modal-open').css({ 'padding-right': '0', 'overflow': 'hidden' }); });
        $('#item-info-modal-wrapper').on('hidden.bs.modal', function () { $('body').removeClass('modal-open').css({ 'padding-right': '', 'overflow': '' }); $('.modal-backdrop').remove(); });
        $('.modal').on('hidden.bs.modal', function (e) { if ($(this).attr('id') !== 'item-info-modal-wrapper') { if ($('#item-info-modal-wrapper').hasClass('show')) { e.stopPropagation(); $('body').addClass('modal-open').css('padding-right', '0'); } } });

        const targetNode = document.getElementById('specifications-list');
        if(targetNode) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        $(mutation.addedNodes).each(function() {
                            const $node = $(this);
                            if ($node.find('h5').length > 0 && !$node.hasClass('spec-accordion-wrapper')) {
                                buildAccordionProxy($node);
                            }
                        });
                    }
                });
            });
            observer.observe(targetNode, { childList: true });
        }
    });

    function buildAccordionProxy($originalNode) {
        $originalNode.addClass('original-spec-row').hide();

        const title = $originalNode.find('h5').text();
        const desc = $originalNode.find('p').text();
        const $props = $originalNode.find('ul li');
        const $originalEditBtn = $originalNode.find('button:contains("Edit"), button.edit-spec-btn');
        const $originalDeleteBtn = $originalNode.find('button:contains("Delete"), button.delete-spec-btn');

        let summaryText = [];
        let tableRows = '';

        if ($props.length > 0) {
            $props.each(function() {
                const text = $(this).text();
                summaryText.push(text);
                let parts = text.split(':');
                let key = parts[0].trim();
                let val = parts.length > 1 ? parts[1].trim() : '';
                if(val === '') { val = key; key = 'Property'; }
                tableRows += `<tr><td class="pro-key">${key}</td><td class="pro-value">${val}</td></tr>`;
            });
        } else {
            tableRows = '<tr><td colspan="2" class="text-center text-muted p-3 font-italic">{{ __("admin::home.no_properties_defined") }}</td></tr>';
        }

        const summaryString = summaryText.length > 0 ? summaryText.join(' • ') : '{{ __("admin::home.no_properties") }}';

        let descriptionHtml = '';
        if(desc && desc.trim() !== '') {
            descriptionHtml = `
                <div class="spec-description-box">
                    <div class="spec-desc-icon"><i class="ri-text-wrap"></i></div>
                    <div>${desc}</div>
                </div>
            `;
        }

        const $newUI = $(`
            <div class="spec-accordion-wrapper mb-3">
                <div class="spec-accordion">
                    <div class="spec-acc-header">
                        <div class="acc-actions-fixed" onclick="event.stopPropagation()">
                            <button class="action-icon-btn btn-edit-proxy" title="{{ __('admin::home.edit') }}"><i class="ri-pencil-line"></i></button>
                            <button class="action-icon-btn btn-delete-proxy" title="{{ __('admin::home.delete') }}"><i class="ri-delete-bin-line"></i></button>
                        </div>

                        <div class="acc-icon"><i class="ri-checkbox-blank-circle-fill"></i></div>
                        <div class="acc-title">${title}</div>
                        <div class="acc-summary">${summaryString}</div>

                        <div class="acc-chevron-fixed"><i class="ri-arrow-down-s-line"></i></div>
                    </div>

                    <div class="spec-acc-body">
                        ${descriptionHtml}
                        <table class="pro-table">
                            <tbody>${tableRows}</tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);

        $newUI.find('.spec-acc-header').on('click', function() {
            $(this).closest('.spec-accordion').toggleClass('active');
        });

        $newUI.find('.btn-edit-proxy').on('click', function(e) {
            e.stopPropagation();
            $originalEditBtn.trigger('click');
        });

        $newUI.find('.btn-delete-proxy').on('click', function(e) {
            e.stopPropagation();
            if(confirm('{{ __("admin::home.confirm_delete_spec") }}')) $originalDeleteBtn.trigger('click');
        });

        $originalNode.after($newUI);
    }
</script>
@endpush
