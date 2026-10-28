<div class="modal fade" id="editModal"
     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel"> Edit service</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-5">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                        <div class="card custom-card shadow-none mb-0">
                            <div class="card-body p-0">
                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <label for="service-name" class="form-label text-black">Service Name</label>
                                        <input type="text" class="form-control" id="service-name" placeholder="Name" value="service name">
                                        <label for="service-name-add" class="form-label mt-1 fs-12 op-5 text-danger mb-0">*Service Name should not exceed 30 characters</label>
                                    </div>

                                    <div class="col-xl-6">
                                        <label for="service-category" class="form-label text-black">Category</label>
                                        <select class="form-control" data-trigger name="category_id" id="service-category">
                                            <option value="">Select</option>
                                            <option value="cat1">Cat 1</option>
                                            <option value="cat2" selected>Cat 2</option>
                                            <option value="cat3">Cat 3</option>
                                            <option value="cat4">Cat 4</option>
                                            <option value="cat5">Cat 5</option>
                                            <option value="cat6">Cat 6</option>
                                        </select>
                                    </div>


                                    <div class="col-xl-6">
                                        <label for="service-active" class="form-label text-black">Active</label>
                                        <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                            <input id="service-active" name="active" type="checkbox" checked="">
                                            <label for="service-active" class="label-success"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger"
                        data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </div>
</div>
