<div class="modal fade" id="specificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="specificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="specificationModalLabel">{{ __('admin::home.add_specification') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="specificationForm">
                <div class="modal-body">
                    <input type="hidden" id="specificationId" name="id">
                    <input type="hidden" id="itemId" name="item_id">

                    <div class="card custom-card shadow-none border mb-0">
                        <div class="card-body">

                            <h6 class="fw-bold mb-3 text-primary"><i class="ri-file-info-line me-1"></i> {{ __('admin::home.basic_info') }}</h6>
                            <div class="row gy-3">
                                <div class="col-12">
                                    <label for="specificationName" class="form-label text-black">
                                        {{ __('admin::home.specification_name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="specificationName" name="name" required placeholder="{{ __('admin::home.specification_name_placeholder') }}">
                                </div>
                                <div class="col-12">
                                    <label for="specificationDescription" class="form-label text-black">
                                        {{ __('admin::home.description') }}
                                    </label>
                                    <textarea class="form-control" id="specificationDescription" name="description" rows="2" placeholder="{{ __('admin::home.description_placeholder') }}"></textarea>
                                </div>
                            </div>

                            <hr class="my-4 dashed">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0 text-primary"><i class="ri-list-settings-line me-1"></i> {{ __('admin::home.properties_list') }}</h6>
                                <button type="button" class="btn btn-sm btn-success-light btn-wave" id="addPropertyBtn">
                                    <i class="bi bi-plus-lg"></i> {{ __('admin::home.add_property') }}
                                </button>
                            </div>

                            <div id="properties-container">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        {{ __('admin::home.close') }}
                    </button>
                    <button type="submit" class="btn btn-success">
                        <span class="indicator-label">{{ __('admin::home.save_changes') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
