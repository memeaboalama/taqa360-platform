<div class="modal fade" id="editModal"
     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel"> Edit role</h6>
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
                                        <label for="role-name" class="form-label text-black">Role Name</label>
                                        <input type="text" class="form-control" id="role-name" placeholder="Name" value="Viewer role">
                                        <label for="role-name-add" class="form-label mt-1 fs-12 op-5 text-danger mb-0">*Role Name should not exceed 30 characters</label>
                                    </div>

                                    <h4 class="page-title fs-16">Permissions</h4>
                                    <div class="row row-cols-12">
                                        <div class="col-4">
                                            <div class="card custom-card  bg-blue-transparent">
                                                <div class="card-header justify-content-between">
                                                    <div class="card-title">
                                                        Branches
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-success" type="checkbox" value="" id="successChecked" checked="">
                                                        <label class="form-check-label" for="successChecked">
                                                            Create Branch
                                                        </label>
                                                    </div>

                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-secondary" type="checkbox" value="" id="secondaryChecked" checked="">
                                                        <label class="form-check-label" for="secondaryChecked">
                                                            Update Branch
                                                        </label>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-danger" type="checkbox" value="" id="dangerChecked" checked="">
                                                        <label class="form-check-label" for="dangerChecked">
                                                            Delete Branch
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="card custom-card  bg-blue-transparent">
                                                <div class="card-header justify-content-between">
                                                    <div class="card-title">
                                                        Categories
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-success" type="checkbox" value="" id="successChecked" checked="">
                                                        <label class="form-check-label" for="successChecked">
                                                            Create Category
                                                        </label>
                                                    </div>

                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-secondary" type="checkbox" value="" id="secondaryChecked" checked="">
                                                        <label class="form-check-label" for="secondaryChecked">
                                                            Update Category
                                                        </label>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-danger" type="checkbox" value="" id="dangerChecked" checked="">
                                                        <label class="form-check-label" for="dangerChecked">
                                                            Delete Category
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="card custom-card  bg-blue-transparent">
                                                <div class="card-header justify-content-between">
                                                    <div class="card-title">
                                                        Services
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-success" type="checkbox" value="" id="successChecked" checked="">
                                                        <label class="form-check-label" for="successChecked">
                                                            Create Service
                                                        </label>
                                                    </div>

                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-secondary" type="checkbox" value="" id="secondaryChecked" checked="">
                                                        <label class="form-check-label" for="secondaryChecked">
                                                            Update Service
                                                        </label>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input form-checked-danger" type="checkbox" value="" id="dangerChecked" checked="">
                                                        <label class="form-check-label" for="dangerChecked">
                                                            Delete Service
                                                        </label>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger"
                        data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </div>
</div>
