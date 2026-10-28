<div class="modal fade" id="createAddons"  wire:ignore.self
     data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel"> Create New Category Addons</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="store">
                <div class="modal-body">
                    <div class="row gx-5">
                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12">
                            <div class="card custom-card shadow-none mb-0">
                                <div class="card-body p-0">
                                    <div class="row gy-3 mt-1">
                                        <div class="col-xl-6">
                                            <h6> Service Addons Category</h6>
                                        </div>
                                        <div class="col-xl-4">
                                        </div>
                                        <div class="col-xl-2">
                                            <button class="btn text-white btn-primary btn-sm" wire:click.prevent="add({{ $increamentInputs }})">Add Row<i class="bi bi-plus-lg ms-2"></i></button>
                                        </div>
                                    </div>

                                    <div class="row gy-3 mt-2">
                                        <div class="col-xl-6">
                                            <label for="title" class="form-label text-black">Title</label>
                                            <input class="form-control bg-gray-200" type="text" name="title" wire:model="title">
                                            @error("title")
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="title" class="form-label text-black">Status</label>
                                            <select class="form-control bg-gray-200" name="status" wire:model="status">
                                                <option selected value="">---Select Status---</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                            @error("status")
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row gy-3 mt-2">
                                        <div class="col-xl-3">
                                            <label for="title" class="form-label text-black">Service Addons Title</label>
                                            <input class="form-control bg-gray-200" type="text" name="addon_title[]" wire:model="addon_title.0">
                                            @error("addon_title.0")
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-xl-3">
                                            <label for="title" class="form-label text-black">Service Addons Status</label>
                                            <select class="form-control bg-gray-200" name="addon_status[]" wire:model="addon_status.0">
                                                <option selected value="">---Select Status---</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                            @error("addon_status.0")
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-xl-2">
                                            <label for="price" class="form-label text-black">Price</label>
                                            <input class="form-control bg-gray-200" type="number" min="1" step="0.05" name="addon_price[]" wire:model="addon_price.0">
                                            @error("addon_price.0")
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-xl-2">
                                            <label for="working_minutes" class="form-label text-black"> Working Minutes</label>
                                            <input class="form-control bg-gray-200" type="number" min="1" step="1" name="addon_working_minutes[]" wire:model="addon_working_minutes.0">
                                            @error("addon_working_minutes.0")
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                    @foreach($inputs as $k => $val)
                                        <div class="row gy-3 mt-2">
                                            <div class="col-xl-3">
                                                <label for="title" class="form-label text-black">Service Addons Title</label>
                                                <input class="form-control bg-gray-200" type="text" name="addon_title[]" wire:model="addon_title.{{ $val }}">
                                                @error("addon_title.{{ $val }}")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-3">
                                                <label for="title" class="form-label text-black">Service Addons Status</label>
                                                <select class="form-control bg-gray-200" name="addon_status[]" wire:model="addon_status.{{ $val }}">
                                                    <option selected value="">---Select Status---</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>

                                                @error("addon_status.{{ $val }}")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-2">
                                                <label for="price" class="form-label text-black">Price</label>
                                                <input class="form-control bg-gray-200" type="number" step="0.05" name="addon_price[]" wire:model="addon_price.{{ $val }}">
                                                @error("addon_price.{{ $val }}")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-2">
                                                <label for="working_minutes" class="form-label text-black"> Working Minutes</label>
                                                <input class="form-control bg-gray-200" type="number" step="1" name="addon_working_minute[]" wire:model="addon_working_minutes.{{ $val }}">
                                                @error("addon_working_minute.$val }}")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-2">
                                                <button class="btn btn-icon btn-sm btn-danger-transparent rounded-pill mt-4" wire:click.prevent="remove({{ $k }})"><i class="ri-delete-bin-line"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger"
                            data-bs-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    Save
                                </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                                    Wait <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
