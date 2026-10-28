<div class="modal fade" id="createModal"
     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel"> Create new branch</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-5">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                        <div class="card custom-card shadow-none mb-0">
                            <div class="card-body p-0">
                                <div class="row gy-3">
                                    <div class="col-xl-8">
                                        <label for="branch-name" class="form-label text-black">Branch Name</label>
                                        <input type="text" class="form-control" id="branch-name" placeholder="Name" value="">
                                        <label for="branch-name-add" class="form-label mt-1 fs-12 op-5 text-danger mb-0">*Branch Name should not exceed 30 characters</label>
                                    </div>

                                    <div class="col-xl-4">
                                        <label for="branch-phone" class="form-label text-black">Phone Number</label>
                                        <input type="text" class="form-control" id="branch-phone" placeholder="phone" value="">
                                    </div>

                                    <div class="col-xl-4">
                                        <label for="branch-country" class="form-label text-black">Country</label>
                                        <select class="form-control" data-trigger name="country_id" id="branch-country">
                                            <option value="">Select</option>
                                            <option value="eg">Egypt</option>
                                            <option value="sa">Saudi Arabia</option>
                                            <option value="en">England</option>
                                            <option value="qr">Qatar</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-4">
                                        <label for="branch-city" class="form-label text-black">City</label>
                                        <select class="form-control" data-trigger name="city_id" id="branch-city">
                                            <option value="">Select</option>
                                            <option value="cai">Cairo</option>
                                            <option value="alex">Alex</option>
                                            <option value="new-giza">New Giza</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="branch-active" class="form-label text-black">Active</label>
                                        <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                            <input id="branch-active" name="active" type="checkbox" checked="">
                                            <label for="branch-active" class="label-success"></label>
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
                <button type="button" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>
