window.initItemSpecificationsInfo = function(options = {}) {
    // $(document).ready(function() {
    const option = options;
    const {
        id: itemId = 0,
        name: itemName = '',
        infoModal=null,
        url,
        attachable = 'items',
        userType = null,
        path = null,
        entity='item',

    } = options;
    const specModal = new bootstrap.Modal(document.getElementById('specificationModal'));

    // --- NEW variables for Library Modal ---
    const libraryModal = new bootstrap.Modal(document.getElementById('specLibraryModal'));
    const searchInput = infoModal.find('#specSearchInput');
    const searchResultDiv = infoModal.find('#specSearchResult');
    const attachBtn = infoModal.find('#attachSpecBtn');


    // --- Utility Functions ---
    function clearForm() {
        infoModal.find('#specificationForm')[0].reset();
        infoModal.find('#specificationId').val('');
        infoModal.find('#properties-container').empty();
        infoModal.find('#specificationModalLabel').text('Add Specification');
        infoModal.find('#specificationModal').find('[name=item_id]').val('');
    }

    // function addPropertyField(name = '', value = '') {
    //     const fieldHtml = `
    //         <div class="row property-field mb-2">
    //             <div class="col-md-5">
    //                 <input type="text" class="form-control" name="properties[][name]" placeholder="Property Name" value="${name}" required>
    //             </div>
    //             <div class="col-md-6">
    //                 <input type="text" class="form-control" name="properties[][value]" placeholder="Property value" value="${value}">
    //             </div>
    //             <div class="col-md-1">
    //                 <button type="button" class="btn btn-sm btn-danger remove-property-btn">X</button>
    //             </div>
    //         </div>
    //     `;
    //     infoModal.find('#properties-container').append(fieldHtml);
    // }

    function addPropertyField(name = '', value = '') {
        const placeholderName = window.lang.property_name || 'Property Name';
        const placeholderValue = window.lang.property_value || 'Property Value';
        const titleDelete = window.lang.delete || 'Delete';

        const fieldHtml = `
            <div class="row property-field mb-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" class="form-control" name="properties[][name]" placeholder="${placeholderName}" value="${name}" required>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="properties[][value]" placeholder="${placeholderValue}" value="${value}">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-property-btn border-0" title="${titleDelete}">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        `;
        infoModal.find('#properties-container').append(fieldHtml);
    }

    // --- Load Specifications ---
    function loadSpecifications(item_id = 0) {
        const queryParams= {};
        if(item_id&&item_id!=0){
            queryParams['item_id']=item_id;
        }
        else if(itemId && itemId!==0){
            queryParams['item_id']=itemId;

        }
        $.ajax({
            // url: `/${url}/specifications?item_id=${item_id==0?itemId:item_id}`,
            url: `/${url}/specifications`,
            method: 'GET',
            data: queryParams,
            success: function(response) {
                const list = infoModal.find('#specifications-list');
                list.empty();
                const data = response.data.data || [];
                if (data.length === 0) {
                    list.html('<p>No specifications found for this item.</p>');
                    return;
                }
                data.forEach(spec => {
                    const propertiesHtml = (spec.properties && spec.properties.length !==0 )?spec.properties.map(p => `<li>${p.name}: ${p.value || ''}</li>`).join(''):`<li> Not Properties In this ${spec.name}</li>`;
                    const specHtml = `
                        <div class="border p-3 mb-2">
                            <h5>${spec.name}</h5>
                            <p>${spec.description || ''}</p>
                            <ul>${propertiesHtml}</ul>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-info edit-spec-btn" data-id="${spec.id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-spec-btn" data-id="${spec.id}">Delete</button>
                            </div>
                        </div>
                    `;
                    list.append(specHtml);
                });
            },
            error: function() {
                infoModal.find('#specifications-list').html('<p class="text-danger">Failed to load specifications.</p>');
            }
        });
    }


    // 1. Open the Library Modal
    infoModal.find('#addFromLibraryBtn').on('click', function() {
        // Reset state from previous opens
        // const itemId = $(this).data('item_id');
        console.warn("Add Specifications from-library ", itemId);
        searchInput.val('');
        searchResultDiv.addClass('d-none');
        attachBtn.prop('disabled', true);
        infoModal.find('#selectedMasterSpecId').val('');
        // $('#selected_item_id').val(itemId);
        libraryModal.show();
    });

    // 2. Initialize jQuery UI Autocomplete for searching master specs
    searchInput.autocomplete({
        source: function(request, response) {
            $.ajax({
                url: `/${url}/master-specifications`,
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(res) {
                    // Map the response to the format jQuery UI expects
                    response($.map(res.data.data, function(item) {
                        return {
                            id: item.id,
                            label: item.name,
                            value: item.id
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            event.preventDefault(); // Stop the input from being populated with the ID
            searchInput.val(ui.item.label);
            infoModal.find('#selectedMasterSpecId').val(ui.item.value);
            infoModal.find('#selectedSpecName').text(ui.item.label);
            searchResultDiv.removeClass('d-none');
            attachBtn.prop('disabled', false);
        }
    });

    // 3. Handle the 'Attach to Item' button click
    attachBtn.on('click', function() {
        const masterSpecId = infoModal.find('#selectedMasterSpecId').val();
        // const itemId = $('#selected_item_id').val();
        if (!itemId) {
            alert('Please select a item first.');
            return;
        }
        if (!masterSpecId) {
            alert('Please select a specification first.');
            return;
        }

        $.ajax({
            url: `/${url}/master-specifications/attach-to-item`,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                item_id: itemId,
                master_specification_id: masterSpecId
            }),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                libraryModal.hide();
                loadSpecifications(itemId); // Reload the list to show the newly attached spec
            },
            error: function(xhr) {
                alert('An error occurred while attaching the specification.');
                console.log(xhr.responseJSON);
            }
        });
    });

    // --- Event Handlers ---
    // $('button#addSpecificationBtn.add-item-specification-btn').on('click', function() {
    infoModal.find('#addSpecificationBtn').on('click', function() {

        clearForm();
        if(itemId && itemId !== 0)
        infoModal.find('#specificationModal').find('[name=item_id]').val(itemId);
        
        // $('#itemId').val(itemId);
        // $('#specificationModal').modal({show:true});
        // bootstrap.Modal(document.getElementById('specificationModal')).show();
        // $('#specificationModal').modal('show');
        specModal.show();
        // const truck_modal = document.querySelector("specificationModal");
        // let myModal = bootstrap.Modal.getOrCreateInstance(truck_modal,{backdrop:true,show:true});
        // myModal.show({backdrop:true});
        // $('specificationModal').modal({backdrop:true}).show();
    });

    infoModal.find('#addPropertyBtn').on('click', function() {
        addPropertyField();
    });

    infoModal.find('#properties-container').on('click', '.remove-property-btn', function() {
        $(this).closest('.property-field').remove();
    });

    // Edit Button Click
    infoModal.find('#specifications-list').on('click', '.edit-spec-btn', function() {
        const specId = $(this).data('id');
        // $(document).on('click', 'a.btn.add-item-specification-btn', function() {
        // const specId = 15;
        // const itemId = $(this).data('item_id');
        clearForm();
        infoModal.find('#specificationModalLabel').text('Edit Specification');

        $.ajax({
            url: `/${url}/specifications/${specId}`,
            method: 'GET',
            success: function(response) {
                const spec = response.data;
                infoModal.find('#specificationId').val(spec.id);
                infoModal.find('#itemId').val(itemId);
                infoModal.find('#specificationName').val(spec.name);
                infoModal.find('#specificationDescription').val(spec.description);
                spec.properties.forEach(prop => addPropertyField(prop.name, prop.value || ''));
                // $('#specificationModal').modal('show');
                specModal.show();
            }
        });
    });

    // Delete Button Click
    $('#specifications-list').on('click', '.delete-spec-btn', function() {
        const specId = $(this).data('id');
        if (confirm('Are you sure you want to delete this specification?')) {
            $.ajax({
                url: `/${url}/specifications/${specId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadSpecifications(itemId);
                }
            });
        }
    });

    // Form Submission (Create & Update)
    infoModal.find('#specificationForm').on('submit', function(e) {
        e.preventDefault();
        const specId = infoModal.find('#specificationId').val();
        const url1 = specId ? `/${url}/specifications/${specId}` : `/${url}/specifications`;
        const method = specId ? 'PUT' : 'POST';

        const formData = $(this).serializeJSON(); // Requires jquery-serializejson plugin or similar
        let $form = $(this);
        let $saveBtn = $form.find('button[type="submit"]');
        let $closeBtns = $form.closest('.modal').find('button[data-dismiss="modal"], .close');
        // ============ إضافة لودينج وتعطيل الأزرار ============
        $saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span> Saving...');
        $.ajax({
            url: url1,
            method: method,
            contentType: 'application/json',
            data: JSON.stringify(formData),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                specModal.hide();
                loadSpecifications(parseInt(formData.item_id||0)||0);
            },
            error: function(xhr) {
                // Basic error handling
                alert('An error occurred. Please check the console.');
                console.log(xhr.responseJSON);
            },
            complete: function() {
                $saveBtn.prop('disabled', false).html('Save');
                $closeBtns.prop('disabled', false);
            }
        });
    });
    function init(){
        infoModal.find("#addFromLibraryBtn").toggleClass('d-none', !itemId || itemId === 0);
    }
    init();
    // --- Initial Load ---
    loadSpecifications(itemId);

    // });

    return {
        item: itemId,
        modal: specModal,
        library: libraryModal,
        data: () => itemId || null,
    };
};
