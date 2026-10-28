{{-- <div class="tab-pane text-muted" id="about1-justified" role="tabpanel" wire:ignore.self>
    <div class="row gy-3 mt-1">
        <div class="col-xl-3">
            <h6 class="text-black"> Service Addons Category ({{ $service->addon_categories()->count() }})</h6>
        </div>
    </div>
    @foreach($service->addon_categories as $key=> $addon_category)
    <div class="row gy-3 mt-2">
        <div class="col-md-3">
            <div class="row">
                <div class="col-xl-6">
                    <label for="title" class="form-label text-black">Title</label>
                    <input disabled class="form-control bg-gray-200" type="text" name="addon_title[]" value="{{ $addon_category->title }}">
                    @error("addon_title.0")
                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-xl-6">
                    <label for="title" class="form-label text-black">Status</label>
                    <select disabled class="form-control bg-gray-200" name="addon_status[]">
                        <option selected value="">---Select Status---</option>
                        <option value="active" @if($addon_category->status == "active") selected @endif>Active</option>
                        <option value="inactive" @if($addon_category->status == "inactive") selected @endif>Inactive</option>
                    </select>

                    @error("addon_status.0")
                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-7">
            @if($addon_category->addons->count() > 0)
            <div class="row gy-3 p-4 justify-content-around mt-2 bg-light">
                <div class="col-xl-4">
                    <label for="title" class="form-label text-black">Service Addons Title</label>
                </div>

                <div class="col-xl-2">
                    <label for="title" class="form-label text-black">Service Addons Status</label>
                </div>

                <div class="col-xl-2">
                    <label for="price" class="form-label text-black">Price</label>
                </div>

                <div class="col-xl-2">
                    <label for="working_minutes" class="form-label text-black"> Working Minutes</label>
                </div>
                @foreach($addon_category->addons as $key => $item)
                <div class="row gy-2 justify-content-around bg-light">
                    <div class="col-xl-4">
                        <input disabled class="form-control" type="text" name="title[]" value="{{ $item->title }}">
                    </div>

                    <div class="col-xl-2">
                        <select disabled class="form-control " name="status[]">
                            <option selected value="">---Select Status---</option>
                            <option value="active" @if($item->status == "active") selected @endif>Active</option>
                            <option value="inactive" @if($item->status == "inactive") selected @endif>Inactive</option>
                        </select>
                    </div>

                    <div class="col-xl-2">
                        <input disabled class="form-control " type="number" step="0.05" name="price[]" value="{{ $item->price }}">
                    </div>

                    <div class="col-xl-2">
                        <input disabled class="form-control " type="number" step="1" name="working_minute[]" value="{{ $item->working_minutes }}">
                    </div>
                </div>
                @endforeach
                <hr>
            </div>
            @endif
        </div>

        <div class="col-xl-2">
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-info-transparent rounded-pill mt-4" wire:click="$emit('updateAddonCategory',{{ $addon_category->id }})" title="edit"><i class="ri-edit-line"></i></a>

            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill mt-4" title="trash" wire:click="$emit('deleteAddon',{{ $addon_category->id }})"><i class="ri-delete-bin-line"></i></a>
        </div>
    </div>
    @endforeach
    <livewire:marketplace::service.addons.modal.delete :service="$service" />
    <livewire:marketplace::service.addons.modal.create :service="$service" />
    <livewire:marketplace::service.addons.modal.update :service="$service" />
</div> --}}
